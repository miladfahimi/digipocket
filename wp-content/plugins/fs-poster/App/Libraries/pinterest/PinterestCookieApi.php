<?php

namespace FSPoster\App\Libraries\pinterest;

use Exception;
use FSP_GuzzleHttp\Client;
use FSP_GuzzleHttp\Cookie\CookieJar;
use FSPoster\App\Providers\Helper;

class PinterestCookieApi
{
	/**
	 * @var Client
	 */
	private $client;
	private $cookie;
	private $proxy;
	private $domain = 'www.pinterest.com';

	public function __construct ( $cookie, $proxy )
	{
		$this->cookie = $cookie;
		$this->proxy  = $proxy;

		$this->setClient();
		$this->findDomainAlias();
	}

	private function setClient ( $max_redirects = 0 )
	{
		$csrf_token = base64_encode( microtime( 1 ) . rand( 0, 99999 ) );

		$cookieJar = new CookieJar( FALSE, [
			[
				"Name"     => "_pinterest_sess",
				"Value"    => $this->cookie,
				"Domain"   => '.' . $this->domain,
				"Path"     => "/",
				"Max-Age"  => NULL,
				"Expires"  => NULL,
				"Secure"   => FALSE,
				"Discard"  => FALSE,
				"HttpOnly" => FALSE,
				"Priority" => "HIGH"
			],
			[
				"Name"     => "csrftoken",
				"Value"    => $csrf_token,
				"Domain"   => '.' . $this->domain,
				"Path"     => "/",
				"Max-Age"  => NULL,
				"Expires"  => NULL,
				"Secure"   => FALSE,
				"Discard"  => FALSE,
				"HttpOnly" => FALSE,
				"Priority" => "HIGH"
			]
		] );

		$this->client = new Client( [
			'cookies'         => $cookieJar,
			'allow_redirects' => [ 'max' => $max_redirects ],
			'proxy'           => empty( $this->proxy ) ? NULL : $this->proxy,
			'verify'          => FALSE,
			'http_errors'     => FALSE,
			'headers'         => [
				'User-Agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0',
				'x-CSRFToken' => $csrf_token
			]
		] );
	}

	private function findDomainAlias ()
	{
		$result     = $this->client->get( 'https://' . $this->domain );
		$locationTo = $result->getHeader( 'Location' );

		if ( isset( $locationTo[ 0 ] ) && is_string( $locationTo[ 0 ] ) )
		{
			$domain       = parse_url( $locationTo[ 0 ] );
			$this->domain = $domain[ 'host' ];
		}

		$this->setClient( 10 );
	}

	public function sendPost ( $boardId, $title, $message, $link, $images )
	{
		$imageUrl = reset( $images );
		if ( empty( $imageUrl ) )
		{
			return [
				'status'    => 'error',
				'error_msg' => esc_html__( 'An image is required to pin on board!' )
			];
		}

		$uploadedImage = $this->uploadImage( $imageUrl );
		if ( $uploadedImage[ 0 ] == FALSE )
		{
			return [
				'status'    => 'error',
				'error_msg' => $uploadedImage[ 1 ]
			];
		}

		$sendData = [
			"options" => [
				"board_id"            => $boardId,
				"field_set_key"       => "create_success",
				"skip_pin_create_log" => TRUE,
				"description"         => $message,
				"link"                => $link,
				"title"               => $title,
				"image_url"           => $uploadedImage[ 1 ],
				"method"              => "uploaded",
				"upload_metric"       => [ "source" => "pinner_upload_standalone" ]
			],
			"context" => []
		];

		try
		{
			$response = (string) $this->client->post( 'https://' . $this->domain . '/resource/PinResource/create/', [
				'form_params' => [
					'data' => json_encode( $sendData )
				]
			] )->getBody();
			$response = json_decode( $response, TRUE );
		}
		catch ( Exception $e )
		{
			$response = [];
		}

		$pinId = isset( $response[ 'resource_response' ][ 'data' ][ 'id' ] ) ? $response[ 'resource_response' ][ 'data' ][ 'id' ] : '';
		if ( empty( $pinId ) )
		{
			return [
				'status'    => 'error',
				'error_msg' => $this->errorMessage( $response )
			];
		}
		else
		{
			return [
				'status' => 'ok',
				'id'     => $pinId
			];
		}
	}

	private function uploadImage ( $image )
	{
		try
		{
			$response = (string) $this->client->post( 'https://' . $this->domain . '/upload-image/', [
				'multipart' => [
					[
						'name'     => 'img',
						'contents' => file_get_contents( $image ),
						'filename' => 'blob',
						'headers'  => [ 'Content-Type' => 'image/jpeg' ]
					]
				]
			] )->getBody();
			$response = json_decode( $response, TRUE );
		}
		catch ( Exception $e )
		{
			return [ FALSE, $e->getMessage() ];
		}

		return isset( $response[ 'image_url' ] ) ? [ TRUE, $response[ 'image_url' ] ] : [
			FALSE,
			$this->errorMessage( $response )
		];
	}

	private function errorMessage ( $result, $defaultError = '' )
	{
		if ( isset( $result[ 'resource_response' ][ 'message' ] ) && is_string( $result[ 'resource_response' ][ 'message' ] ) )
		{
			return esc_html( $result[ 'resource_response' ][ 'message' ] );
		}

		if ( isset( $result[ 'resource_response' ][ 'error' ][ 'message' ] ) && is_string( $result[ 'resource_response' ][ 'error' ][ 'message' ] ) )
		{
			return esc_html( $result[ 'resource_response' ][ 'error' ][ 'message' ] . ( isset( $result[ 'resource_response' ][ 'error' ][ 'message_detail' ] ) && is_string( $result[ 'resource_response' ][ 'error' ][ 'message_detail' ] ) ? ' ' . $result[ 'resource_response' ][ 'error' ][ 'message_detail' ] : '' ) );
		}

		if ( ! empty( $defaultError ) )
		{
			return $defaultError;
		}

		return esc_html__( 'Couldn\'t upload the image!' );
	}

	public function getAccountData ()
	{
		try
		{
			$response = (string) $this->client->get( 'https://' . $this->domain . '/resource/HomefeedBadgingResource/get/' )->getBody();
		}
		catch ( Exception $e )
		{
			$response = '';
		}

		$result = json_decode( $response, TRUE );

		$id        = isset( $result[ 'client_context' ][ 'user' ][ 'id' ] ) ? $result[ 'client_context' ][ 'user' ][ 'id' ] : '';
		$image     = isset( $result[ 'client_context' ][ 'user' ][ 'image_medium_url' ] ) ? $result[ 'client_context' ][ 'user' ][ 'image_medium_url' ] : '';
		$username  = isset( $result[ 'client_context' ][ 'user' ][ 'username' ] ) ? $result[ 'client_context' ][ 'user' ][ 'username' ] : '';
		$full_name = isset( $result[ 'client_context' ][ 'user' ][ 'full_name' ] ) ? $result[ 'client_context' ][ 'user' ][ 'full_name' ] : '';

		if ( empty( $id ) || empty( $username ) )
		{
			Helper::response( FALSE, $this->errorMessage( $result, esc_html__( 'Error! Please check the data and try again!', 'fs-poster' ) ) );
		}

		return [
			'id'          => $id,
			'full_name'   => $full_name,
			'profile_pic' => $image,
			'username'    => $username
		];
	}

	public function getBoards ( $userName )
	{
		$data = [
			"options" => [
				"isPrefetch"           => FALSE,
				"privacy_filter"       => "all",
				"sort"                 => "custom",
				"field_set_key"        => "profile_grid_item",
				"username"             => $userName,
				"page_size"            => 25,
				"group_by"             => "visibility",
				"include_archived"     => TRUE,
				"redux_normalize_feed" => TRUE
			],
			"context" => []
		];

		$boards_arr = [];
		$bookmark   = '';

		while ( TRUE )
		{
			if ( ! empty( $bookmark ) )
			{
				$data[ 'options' ][ 'bookmarks' ] = [ $bookmark ];
			}

			try
			{
				$response = (string) $this->client->get( 'https://' . $this->domain . '/resource/BoardsResource/get/?data=' . urlencode( json_encode( $data ) ) )->getBody();
				$response = json_decode( $response, TRUE );
			}
			catch ( Exception $e )
			{
				$response = [];
			}

			if ( ! isset( $response[ 'resource_response' ][ 'data' ] ) || ! is_array( $response[ 'resource_response' ][ 'data' ] ) )
			{
				$boards = [];
			}
			else
			{
				$boards = $response[ 'resource_response' ][ 'data' ];
			}

			foreach ( $boards as $board )
			{
				$boards_arr[] = [
					'id'    => $board[ 'id' ],
					'name'  => $board[ 'name' ],
					'url'   => ltrim( $board[ 'url' ], '/' ),
					'cover' => isset( $board[ 'image_cover_url' ] ) ? $board[ 'image_cover_url' ] : ''
				];
			}

			if ( isset( $response[ 'resource_response' ][ 'bookmark' ] ) && is_string( $response[ 'resource_response' ][ 'bookmark' ] ) && ! empty( $response[ 'resource_response' ][ 'bookmark' ] ) && $response[ 'resource_response' ][ 'bookmark' ] != '-end-' )
			{
				$bookmark = $response[ 'resource_response' ][ 'bookmark' ];
			}
			else
			{
				break;
			}
		}

		return $boards_arr;
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

		try
		{
			$response = (string) $this->client->get( 'https://' . $this->domain . '/resource/HomefeedBadgingResource/get/' )->getBody();
		}
		catch ( Exception $e )
		{
			$response = '';
		}

		$json_result = json_decode( $response, TRUE );

		$id       = isset( $json_result[ 'client_context' ][ 'user' ][ 'id' ] ) ? $json_result[ 'client_context' ][ 'user' ][ 'id' ] : '';
		$username = isset( $json_result[ 'client_context' ][ 'user' ][ 'username' ] ) ? $json_result[ 'client_context' ][ 'user' ][ 'username' ] : '';

		if ( empty( $id ) || empty( $username ) )
		{
			$result[ 'error_msg' ] = esc_html__( 'Error! Please check the data and try again!', 'fs-poster' );
		}
		else
		{
			$result[ 'error' ] = FALSE;
		}

		return $result;
	}
}
