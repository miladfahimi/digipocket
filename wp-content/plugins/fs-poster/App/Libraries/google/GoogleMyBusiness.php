<?php

namespace FSPoster\App\Libraries\google;

use Exception;
use FSP_GuzzleHttp\Client;
use FSP_GuzzleHttp\Cookie\CookieJar;

class GoogleMyBusiness
{
	private $at;
	private $cookies;
	private $client;
	private $main_page_html;

	public function __construct ( $sid, $hsid, $ssid, $proxy = '' )
	{
		$this->cookies = [
			[
				"Name"     => "SID",
				"Value"    => $sid,
				"Domain"   => ".google.com",
				"Path"     => "/",
				"Max-Age"  => NULL,
				"Expires"  => NULL,
				"Secure"   => TRUE,
				"Discard"  => FALSE,
				"HttpOnly" => TRUE
			],
			[
				"Name"     => "HSID",
				"Value"    => $hsid,
				"Domain"   => ".google.com",
				"Path"     => "/",
				"Max-Age"  => NULL,
				"Expires"  => NULL,
				"Secure"   => TRUE,
				"Discard"  => FALSE,
				"HttpOnly" => FALSE
			],
			[
				"Name"     => "SSID",
				"Value"    => $ssid,
				"Domain"   => ".google.com",
				"Path"     => "/",
				"Max-Age"  => NULL,
				"Expires"  => NULL,
				"Secure"   => TRUE,
				"Discard"  => FALSE,
				"HttpOnly" => FALSE
			]
		];

		$cookieJar = new CookieJar( FALSE, $this->cookies );

		$this->client = new Client( [
			'cookies'         => $cookieJar,
			'allow_redirects' => [ 'max' => 20 ],
			'proxy'           => empty( $proxy ) ? NULL : $proxy,
			'verify'          => FALSE,
			'http_errors'     => FALSE,
			'headers'         => [ 'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko/20100101 Firefox/67.0' ]
		] );
	}

	public function getUserInfo ( $html = NULL )
	{
		if ( is_null( $html ) )
		{
			$html = $this->getMainPageHTML();
		}

		$html = str_replace( "\n", "", $html );

		preg_match( '/window\.WIZ_global_data \= (\{.*?\})\;/mi', $html, $matches );
		if ( isset( $matches[ 1 ] ) )
		{
			$jsonInf = json_decode( str_replace( [ '\x', "'", ',]' ], [ '', '"', ']' ], $matches[ 1 ] ), TRUE );

			preg_match( '/url\((https?\:\/\/.+googleusercontent\.com.+)\)/Ui', $html, $profilePhoto );

			$accountId    = isset( $jsonInf[ 'S06Grb' ] ) ? $jsonInf[ 'S06Grb' ] : NULL;
			$accountEmail = isset( $jsonInf[ 'oPEP7c' ] ) ? $jsonInf[ 'oPEP7c' ] : NULL;

			$userInfo = [
				'id'            => $accountId,
				'name'          => $accountEmail,
				'email'         => $accountEmail,
				'profile_image' => isset( $profilePhoto[ 1 ] ) ? $profilePhoto[ 1 ] : NULL
			];

		}
		else
		{
			$userInfo = [
				'id'            => NULL,
				'name'          => NULL,
				'email'         => NULL,
				'profile_image' => NULL
			];
		}

		return $userInfo;
	}

	public function sendPost ( $postTo, $text, $link = NULL, $linkButton = 'LEARN_MORE', $imageURL = '', $productName = NULL, $productPrice = NULL, $productCurrency = NULL, $productCategory = NULL )
	{
		if ( ! $this->getAT() )
		{
			return [
				'status'    => 'error',
				'error_msg' => 'Error! Google My Business session expired! Please remove and add your account again.'
			];
		}

		$isProduct = is_null( $productName ) ? FALSE : TRUE;

		if ( $isProduct )
		{
			$productPrice  = explode( '.', (string) $productPrice );
			$productPrice1 = (int) $productPrice[ 0 ];
			$productPrice2 = isset( $productPrice[ 1 ] ) ? (int) substr( $productPrice[ 1 ] . '000000000', 0, 9 ) : NULL;

			$fReqParam = [
				[
					[
						"u72bYd",
						json_encode( [
							$postTo,
							[
								NULL,
								$productName,
								$text,
								$productCategory,
								[
									[ $productCurrency, $productPrice1, $productPrice2 ],
									[ $productCurrency, $productPrice1, $productPrice2 ]
								],
								$this->uploadPhoto( $postTo, $imageURL, TRUE )[ 0 ],
								( ! empty( $link ) && $linkButton != '-' ? [ $link, $linkButton ] : NULL )
							]
						] ),
						NULL,
						"generic"
					]
				]
			];
		}
		else
		{
			$fReqParam = [
				[
					[
						"h6IfIc",
						json_encode( [
								$postTo,
								[
									NULL,
									$text,
									NULL,
									NULL,
									( ! empty( $link ) && $linkButton != '-' ? [
										NULL,
										$link,
										$linkButton,
										$linkButton
									] : NULL ),
									[],
									NULL,
									NULL,
									NULL,
									NULL,
									NULL,
									NULL,
									NULL,
									$this->uploadPhoto( $postTo, $imageURL ),
									1,
									NULL,
									NULL
								]
							] ),
						NULL,
						"generic"
					]
				]
			];
		}

		try
		{
			$post = (string) $this->client->request( 'POST', 'https://business.google.com/_/GeoMerchantFrontendUi/data/batchexecute', [
				'form_params' => [ 'f.req' => json_encode( $fReqParam ), 'at' => $this->getAT() ],
				'headers'     => [ 'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8' ]
			] )->getBody();
		}
		catch ( Exception $e )
		{
			return [
				'status'    => 'error',
				'error_msg' => 'Error! ' . $e->getMessage()
			];
		}

		if ( ! $isProduct )
		{
			preg_match( '/localPosts\/([0-9]+)/', $post, $post_id );

			if ( ! isset( $post_id[ 1 ] ) )
			{
				$getErrorMessage = json_decode( str_replace( [ ")]}'", "\n", '\n' ], '', $post ), TRUE );
				$errorMessage    = isset( $getErrorMessage[ 0 ][ 5 ][ 2 ][ 0 ][ 1 ][ 0 ][ 0 ][ 2 ] ) ? 'Error: ' . $getErrorMessage[ 0 ][ 5 ][ 2 ][ 0 ][ 1 ][ 0 ][ 0 ][ 2 ] : esc_html__( 'Error! It couldn\'t share the post!', 'fs-poster' );

				return [
					'status'    => 'error',
					'error_msg' => $errorMessage
				];
			}
		}

		return [
			'status' => 'ok',
			'id'     => $postTo . ( $isProduct ? ':product' : ':post' )
		];
	}

	private function getAT ()
	{
		if ( is_null( $this->at ) )
		{
			$plusMainPage = $this->getMainPageHTML();

			preg_match( '/\"SNlM0e\":\"([^\"]+)/', $plusMainPage, $at );
			$this->at = isset( $at[ 1 ] ) ? $at[ 1 ] : NULL;
		}

		return $this->at;
	}

	public function uploadPhoto ( $location, $imageUrl = NULL, $forProduct = FALSE )
	{
		if ( empty( $imageUrl ) )
		{
			return NULL;
		}

		$fReqParam = [
			[
				[
					"iWixD",
					json_encode( [
							$location,
							$imageUrl,
							[ NULL, $imageUrl, NULL, NULL, 1 ]
						] ),
					NULL,
					"generic"
				]
			]
		];

		try
		{
			$uploadData = (string) $this->client->request( 'POST', 'https://business.google.com/_/GeoMerchantFrontendUi/data/batchexecute', [
				'form_params' => [ 'f.req' => json_encode( $fReqParam ), 'at' => $this->getAT() ],
				'headers'     => [ 'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8' ]
			] )->getBody();
		}
		catch ( Exception $e )
		{
			$uploadData = '';
		}

		$uploadData = str_replace( [ ")]}'", "\n", '\n' ], '', $uploadData );
		$uploadData = json_decode( $uploadData );
		$uploadData = isset( $uploadData[ 0 ][ 2 ] ) ? $uploadData[ 0 ][ 2 ] : "[]";

		$uploadData = json_decode( $uploadData );

		$uploadData = isset( $uploadData[ 2 ] ) ? [ $uploadData[ 2 ] ] : NULL;

		if ( $forProduct )
		{
			if ( ! isset( $uploadData[ 0 ][ 1 ] ) || ! isset( $uploadData[ 0 ][ 5 ] ) )
			{
				return NULL;
			}

			$imgURL   = $uploadData[ 0 ][ 1 ];
			$imgSizes = $uploadData[ 0 ][ 5 ];

			$fReqParam = [
				[
					[
						"I3qire",
						json_encode( [
							$location,
							[
								$imgURL,
								$imgSizes,
								NULL
							]
						] ),
						NULL,
						"generic"
					]
				]
			];

			try
			{
				$uploadData = (string) $this->client->request( 'POST', 'https://business.google.com/_/GeoMerchantFrontendUi/data/batchexecute', [
					'form_params' => [ 'f.req' => json_encode( $fReqParam ), 'at' => $this->getAT() ],
					'headers'     => [ 'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8' ]
				] )->getBody();
			}
			catch ( Exception $e )
			{
				$uploadData = '';
			}

			$uploadData = str_replace( [ "\n", '\n' ], '', $uploadData );

			$uploadData = preg_replace( '/^.+\[/iU', '[', $uploadData );
			$uploadData = preg_replace( '/[0-9]+\[.+$/iU', '', $uploadData );
			$uploadData = json_decode( $uploadData, TRUE );

			if ( ! is_array( $uploadData ) || ! isset( $uploadData[ 0 ][ 2 ] ) )
			{
				return NULL;
			}

			$uploadData = json_decode( $uploadData[ 0 ][ 2 ], TRUE );

			return isset( $uploadData[ 0 ] ) ? [ $uploadData[ 0 ] ] : NULL;
		}

		return $uploadData;
	}

	public function getMyLocations ()
	{
		$locations_arr = [];

		foreach ( $this->getLocationGroups() as $groupInf )
		{
			$locationsInGroup = $this->getLocationsByGroup( $groupInf[ 0 ], $groupInf[ 1 ] );

			$locations_arr = array_merge( $locations_arr, $locationsInGroup );
		}

		return $locations_arr;
	}

	private function getLocationGroups ()
	{
		$html = str_replace( "\n", "", $this->getMainPageHTML() );

		if ( ! preg_match( '/AF_initDataCallback\((\{key: \'ds:3.+\})\);/Umi', $html, $locationGroups ) )
		{
			return [];
		}

		$locationGroups = preg_replace( '/([\{\, ])([a-zA-Z0-9\_]+)\:/i', '$1"$2":', $locationGroups[ 1 ] );
		$locationGroups = str_replace( [ '\x', "'", ',]' ], [ '', '"', ']' ], $locationGroups );

		$jsonInf = json_decode( $locationGroups, TRUE );

		if ( ! isset( $jsonInf[ 'data' ][ 0 ] ) || ! is_array( $jsonInf[ 'data' ][ 0 ] ) )
		{
			return [];
		}

		$groups = $jsonInf[ 'data' ][ 0 ];

		$groupsArr = [];
		foreach ( $groups as $group )
		{
			if ( isset( $group[ 0 ][ 0 ] ) && isset( $group[ 0 ][ 1 ] ) )
			{
				$groupsArr[] = [ $group[ 0 ][ 0 ], $group[ 0 ][ 1 ] ];
			}
		}

		return $groupsArr;
	}

	private function getLocationsByGroup ( $groupId, $groupName, $nextPageToken = NULL )
	{
		if ( is_null( $nextPageToken ) )
		{
			$fReqParam = [
				[
					[
						"VlgRab",
						"[[null,[],\"" . $groupId . "\",null,null,[],null,[],null,[]],100]",
						NULL,
						"1"
					]
				]
			];
		}
		else
		{
			$fReqParam = [ [ [ "VlgRab", "[null,100,\"" . $nextPageToken . "\"]", NULL, "1" ] ] ];
		}

		try
		{
			$getLocationsJson = (string) $this->client->request( 'POST', 'https://business.google.com/_/GeoMerchantFrontendUi/data/batchexecute', [
				'form_params' => [ 'f.req' => json_encode( $fReqParam ), 'at' => $this->getAT() ],
				'headers'     => [ 'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8' ]
			] )->getBody();
		}
		catch ( Exception $e )
		{
			$getLocationsJson = '';
		}

		$getLocationsJson = str_replace( [ "\n", '\n' ], '', $getLocationsJson );

		$getLocationsJson = preg_replace( '/^.+\[/iU', '[', $getLocationsJson );
		$getLocationsJson = preg_replace( '/[0-9]+\[.+$/iU', '', $getLocationsJson );
		$getLocationsJson = json_decode( $getLocationsJson, TRUE );

		if ( ! is_array( $getLocationsJson ) || ! isset( $getLocationsJson[ 0 ][ 2 ] ) )
		{
			return [];
		}

		$locationsJson = json_decode( $getLocationsJson[ 0 ][ 2 ], TRUE );

		if ( ! is_array( $locationsJson ) || ! isset( $locationsJson[ 0 ] ) || ! is_array( $locationsJson[ 0 ] ) )
		{
			return [];
		}

		$locationsArr = [];

		foreach ( $locationsJson[ 0 ] as $locationInf )
		{
			$locationsArr[] = [
				'id'       => $locationInf[ 1 ],
				'name'     => $locationInf[ 3 ],
				'category' => $groupName
			];
		}

		if ( isset( $locationsJson[ 1 ] ) && ! empty( $locationsJson[ 1 ] ) )
		{
			$locationsArr = array_merge( $locationsArr, $this->getLocationsByGroup( $groupId, $groupName, $locationsJson[ 1 ] ) );
		}

		return $locationsArr;
	}

	private function getMainPageHTML ()
	{
		if ( is_null( $this->main_page_html ) )
		{
			try
			{
				$this->main_page_html = (string) $this->client->request( 'GET', 'https://business.google.com/locations' )->getBody();
			}
			catch ( Exception $e )
			{
				$this->main_page_html = '';
			}
		}

		return $this->main_page_html;
	}

	/**
	 * @return array
	 */
	public function checkAccount ()
	{
		$result = [
			'error'     => TRUE,
			'error_msg' => NULL
		];

		if ( $this->getAT() )
		{
			$result[ 'error' ] = FALSE;
		}

		return $result;
	}
}
