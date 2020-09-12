<?php

namespace FSPoster\App\Libraries\linkedin;

use Exception;
use FSP_GuzzleHttp\Client;
use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Curl;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;
use FSPoster\App\Providers\Session;
use FSPoster\App\Providers\SocialNetwork;

class Linkedin extends SocialNetwork
{
	/**
	 * @param array $node_info
	 * @param string $type
	 * @param string $message
	 * @param string $link
	 * @param array $images
	 * @param string $video
	 * @param string $accessToken
	 * @param string $proxy
	 *
	 * @return array
	 */
	public static function sendPost ( $profileId, $node_info, $type, $message, $title, $link, $images, $video, $accessToken, $proxy )
	{
		$client = new Client();

		if ( Helper::getOption( 'linkedin_autocut_text', '1' ) == 1 && mb_strlen( $message ) > 1300 )
		{
			$message = mb_substr( $message, 0, 1297 ) . '...';
		}

		$sendData = [
			'lifecycleState'  => 'PUBLISHED',
			'specificContent' => [
				'com.linkedin.ugc.ShareContent' => [
					'shareCommentary'    => [ 'text' => $message ],
					'shareMediaCategory' => 'ARTICLE'
				]
			],
			'visibility'      => [ 'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC' ]
		];

		if ( isset( $node_info[ 'node_type' ] ) && $node_info[ 'node_type' ] === 'company' )
		{
			$sendData[ 'author' ] = 'urn:li:organization:' . $node_info[ 'node_id' ];
		}
		else if ( isset( $node_info[ 'node_type' ] ) && $node_info[ 'node_type' ] === 'company' )
		{
			$sendData[ 'author' ]          = 'urn:li:person:' . $profileId;
			$sendData[ 'containerEntity' ] = 'urn:li:group:' . $node_info[ 'node_id' ];
		}
		else
		{
			$sendData[ 'author' ] = 'urn:li:person:' . $node_info[ 'profile_id' ];
		}

		if ( $type === 'link' && ! empty( $link ) )
		{
			$sendData[ 'specificContent' ][ 'com.linkedin.ugc.ShareContent' ][ 'media' ] = [
				[
					'status'      => 'READY',
					'originalUrl' => $link,
					'description' => [ 'text' => $message ],
					'title'       => [ 'text' => $title ]
				]
			];

			if ( ! empty( $images ) && is_array( $images ) )
			{
				$sendData[ 'specificContent' ][ 'com.linkedin.ugc.ShareContent' ][ 'media' ][ 0 ][ 'thumbnails' ] = [ [ 'url' => reset( $images ) ] ];
			}
		}
		else if ( $type === 'image' && ! empty( $images ) && is_array( $images ) )
		{
			$send_upload_data      = [
				'registerUploadRequest' => [
					'owner'                    => $sendData[ 'author' ],
					'recipes'                  => [
						'urn:li:digitalmediaRecipe:feedshare-image'
					],
					'serviceRelationships'     => [
						[
							'identifier'       => 'urn:li:userGeneratedContent',
							'relationshipType' => 'OWNER'
						]
					],
					'supportedUploadMechanism' => [
						'SYNCHRONOUS_UPLOAD'
					]
				]
			];
			$uploaded_images       = [];
			$uploaded_images_count = 0;

			foreach ( $images as $imageURL )
			{
				if ( $uploaded_images_count > 4 )
				{
					break;
				}

				if ( empty( $imageURL ) || ! is_string( $imageURL ) )
				{
					continue;
				}

				try
				{
					$result = self::cmd( 'assets?action=registerUpload', 'POST', $accessToken, $send_upload_data, $proxy );

					if ( ! isset( $result[ 'value' ][ 'uploadMechanism' ][ 'com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest' ][ 'uploadUrl' ] ) || empty( $result[ 'value' ][ 'uploadMechanism' ][ 'com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest' ][ 'uploadUrl' ] ) )
					{
						throw new Exception();
					}

					$uploadURL = $result[ 'value' ][ 'uploadMechanism' ][ 'com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest' ][ 'uploadUrl' ];
					$mediaID   = explode( ':', $result[ 'value' ][ 'asset' ] )[ 3 ];

					$resp = $client->request( 'PUT', $uploadURL, [
						'body'    => Curl::getContents( $imageURL ),
						'headers' => [
							'Authorization' => 'Bearer ' . $accessToken,
							'proxy'         => empty( $proxy ) ? NULL : $proxy,
						]
					] );

					$mediaStatus = self::cmd( 'assets/' . $mediaID, 'GET', $accessToken, [], $proxy );

					if ( isset( $mediaStatus[ 'recipes' ][ 0 ][ 'status' ] ) && $mediaStatus[ 'recipes' ][ 0 ][ 'status' ] === 'AVAILABLE' )
					{
						$uploaded_images[] = $result[ 'value' ][ 'asset' ];
					}
					else
					{
						throw new Exception();
					}

					$uploaded_images_count++;
				}
				catch ( Exception $e )
				{
				}
			}

			$sendData[ 'specificContent' ][ 'com.linkedin.ugc.ShareContent' ][ 'shareMediaCategory' ] = 'IMAGE';
			$sendData[ 'specificContent' ][ 'com.linkedin.ugc.ShareContent' ][ 'media' ]              = [];

			foreach ( $uploaded_images as $uplImage )
			{
				$sendData[ 'specificContent' ][ 'com.linkedin.ugc.ShareContent' ][ 'media' ][] = [
					'media'  => $uplImage,
					'status' => 'READY',
				];
			}
		}
		else if ( $type === 'video' )
		{
			$sendData[ 'specificContent' ][ 'com.linkedin.ugc.ShareContent' ][ 'media' ] = [
				[
					'status'      => 'READY',
					'originalUrl' => $video,
					'description' => [ 'text' => $message ],
					'title'       => [ 'text' => $title ],
					'thumbnails'  => [ [ 'url' => $video ] ]
				]
			];
		}
		else
		{
			$sendData[ 'specificContent' ][ 'com.linkedin.ugc.ShareContent' ][ 'shareMediaCategory' ] = 'NONE';
		}

		$result = self::cmd( 'ugcPosts', 'POST', $accessToken, $sendData, $proxy );

		if ( isset( $result[ 'error' ] ) && isset( $result[ 'error' ][ 'message' ] ) )
		{
			$result2 = [
				'status'    => 'error',
				'error_msg' => $result[ 'error' ][ 'message' ]
			];
		}
		else if ( isset( $result[ 'message' ] ) )
		{
			$result2 = [
				'status'    => 'error',
				'error_msg' => isset( $result[ 'message' ] ) ? $result[ 'message' ] : 'Error!'
			];
		}
		else
		{
			$result2 = [
				'status' => 'ok',
				'id'     => $result[ 'id' ]
			];
		}

		return $result2;
	}

	/**
	 * @param string $cmd
	 * @param string $method
	 * @param string $accessToken
	 * @param array $data
	 * @param string $proxy
	 *
	 * @return array|mixed|object|string|void
	 */
	public static function cmd ( $cmd, $method, $accessToken, array $data = [], $proxy = '' )
	{
		$url = 'https://api.linkedin.com/v2/' . $cmd;

		$method = $method === 'POST' ? 'POST' : ( $method === 'DELETE' ? 'DELETE' : 'GET' );

		$headers = [
			'Connection'                => 'Keep-Alive',
			'X-li-format'               => 'json',
			'Content-Type'              => 'application/json',
			'X-RestLi-Protocol-Version' => '2.0.0',
			'Authorization'             => 'Bearer ' . $accessToken
		];

		if ( $method === 'POST' )
		{
			$data = json_encode( $data );
		}

		$data1 = Curl::getContents( $url, $method, $data, $headers, $proxy );
		$data  = json_decode( $data1, TRUE );

		if ( ! is_array( $data ) )
		{
			$data = [
				'error' => [ 'message' => 'Error data!' ]
			];
		}

		return $data;
	}

	/**
	 * @param integer $appId
	 *
	 * @return string
	 */
	public static function getLoginURL ( $appId )
	{
		Session::set( 'app_id', $appId );
		Session::set( 'proxy', Request::get( 'proxy', '', 'string' ) );

		$appInf = DB::fetch( 'apps', [ 'id' => $appId, 'driver' => 'linkedin' ] );
		$appId  = $appInf[ 'app_id' ];

		$permissions = self::getScope();

		$callbackUrl = self::callbackUrl();

		return "https://www.linkedin.com/oauth/v2/authorization?redirect_uri={$callbackUrl}&scope={$permissions}&response_type=code&client_id={$appId}&state=" . uniqid();
	}

	/**
	 * @return string
	 */
	public static function getScope ()
	{
		$permissions = [ 'r_basicprofile', 'rw_organization_admin', 'w_member_social', 'w_organization_social' ];

		return implode( ',', array_map( 'urlencode', $permissions ) );
	}

	/**
	 * @return string
	 */
	public static function callbackURL ()
	{
		return site_url() . '/?linkedin_callback=1';
	}

	/**
	 * @return bool
	 */
	public static function getAccessToken ()
	{
		$appId = (int) Session::get( 'app_id' );

		if ( empty( $appId ) )
		{
			return FALSE;
		}

		$code = Request::get( 'code', '', 'string' );

		if ( empty( $code ) )
		{
			$error_description = Request::get( 'error_description', '', 'str' );

			self::error( $error_description );
		}

		$proxy = Session::get( 'proxy' );

		Session::remove( 'app_id' );
		Session::remove( 'proxy' );

		$appInf    = DB::fetch( 'apps', [ 'id' => $appId, 'driver' => 'linkedin' ] );
		$appSecret = $appInf[ 'app_secret' ];
		$appId2    = $appInf[ 'app_id' ];

		$token_url = "https://www.linkedin.com/oauth/v2/accessToken?" . "client_id=" . $appId2 . "&redirect_uri=" . urlencode( self::callbackUrl() ) . "&client_secret=" . $appSecret . "&code=" . $code . '&grant_type=authorization_code';

		$response = Curl::getURL( $token_url, $proxy );
		$params   = json_decode( $response, TRUE );

		if ( isset( $params[ 'error' ][ 'message' ] ) )
		{
			self::error( $params[ 'error' ][ 'message' ] );
		}

		$access_token = esc_html( $params[ 'access_token' ] );
		$expireIn     = Date::dateTimeSQL( 'now', '+' . (int) $params[ 'expires_in' ] . ' seconds' );

		self::authorize( $appId, $access_token, $expireIn, $proxy );
	}

	/**
	 * @param integer $appId
	 * @param string $accessToken
	 * @param string $scExpireIn
	 * @param string $proxy
	 */
	public static function authorize ( $appId, $accessToken, $scExpireIn, $proxy )
	{
		$me = self::cmd( 'me', 'GET', $accessToken, [], $proxy );

		if ( isset( $me[ 'error' ] ) && isset( $me[ 'error' ][ 'message' ] ) )
		{
			Helper::response( FALSE, $me[ 'error' ][ 'message' ] );
		}

		$meId = $me[ 'id' ];

		$checkLoginRegistered = DB::fetch( 'accounts', [
			'blog_id'    => Helper::getBlogId(),
			'user_id'    => get_current_user_id(),
			'driver'     => 'linkedin',
			'profile_id' => $meId
		] );

		$dataSQL = [
			'blog_id'     => Helper::getBlogId(),
			'user_id'     => get_current_user_id(),
			'name'        => ( isset( $me[ 'localizedFirstName' ] ) ? $me[ 'localizedFirstName' ] : '-' ) . ' ' . ( isset( $me[ 'localizedLastName' ] ) ? $me[ 'localizedLastName' ] : '' ),
			'driver'      => 'linkedin',
			'profile_id'  => $meId,
			'profile_pic' => isset( $me[ 'profilePicture' ][ 'displayImage' ] ) ? $me[ 'profilePicture' ][ 'displayImage' ] : '',
			//'username'		=>	str_replace(['https://www.linkedin.com/in/', 'http://www.linkedin.com/in/'] , '' , $me['publicProfileUrl']),
			'proxy'       => $proxy
		];

		if ( ! $checkLoginRegistered )
		{
			DB::DB()->insert( DB::table( 'accounts' ), $dataSQL );

			$accId = DB::DB()->insert_id;
		}
		else
		{
			$accId = $checkLoginRegistered[ 'id' ];

			DB::DB()->update( DB::table( 'accounts' ), $dataSQL, [ 'id' => $accId ] );

			DB::DB()->delete( DB::table( 'account_access_tokens' ), [ 'account_id' => $accId, 'app_id' => $appId ] );

			DB::DB()->delete( DB::table( 'account_nodes' ), [ 'account_id' => $accId ] );
		}

		// acccess token
		DB::DB()->insert( DB::table( 'account_access_tokens' ), [
			'account_id'   => $accId,
			'app_id'       => $appId,
			'expires_on'   => $scExpireIn,
			'access_token' => $accessToken
		] );

		// my pages load
		$companiesList = self::cmd( 'organizationalEntityAcls', 'GET', $accessToken, [
			'q'          => 'roleAssignee',
			'role'       => 'ADMINISTRATOR',
			'projection' => '(elements*(organizationalTarget~(id,localizedName,vanityName,logoV2)))'
		], $proxy );

		if ( isset( $companiesList[ 'elements' ] ) && is_array( $companiesList[ 'elements' ] ) )
		{
			foreach ( $companiesList[ 'elements' ] as $companyInf )
			{
				DB::DB()->insert( DB::table( 'account_nodes' ), [
					'blog_id'    => Helper::getBlogId(),
					'user_id'    => get_current_user_id(),
					'driver'     => 'linkedin',
					'account_id' => $accId,
					'node_type'  => 'company',
					'node_id'    => isset( $companyInf[ 'organizationalTarget~' ][ 'id' ] ) ? $companyInf[ 'organizationalTarget~' ][ 'id' ] : 0,
					'name'       => isset( $companyInf[ 'organizationalTarget~' ][ 'localizedName' ] ) ? $companyInf[ 'organizationalTarget~' ][ 'localizedName' ] : '-',
					'category'   => isset( $companyInf[ 'organizationalTarget~' ][ 'organizationType' ] ) && is_string( $companyInf[ 'organizationalTarget~' ][ 'organizationType' ] ) ? $companyInf[ 'organizationalTarget~' ][ 'organizationType' ] : '',
					'cover'      => isset( $companyInf[ 'organizationalTarget~' ][ 'logoV2' ][ 'cropped' ] ) && is_string( $companyInf[ 'organizationalTarget~' ][ 'logoV2' ][ 'cropped' ] ) ? $companyInf[ 'organizationalTarget~' ][ 'logoV2' ][ 'cropped' ] : '',
				] );
			}
		}

		self::closeWindow();
	}

	/**
	 * @param integer $post_id
	 *
	 * @return array
	 */
	public static function getStats ( $post_id, $proxy )
	{
		return [
			'comments' => 0,
			'like'     => 0,
			'shares'   => 0,
			'details'  => ''
		];
	}

	/**
	 * @param string $accessToken
	 * @param string $proxy
	 *
	 * @return array
	 */
	public static function checkAccount ( $accessToken, $proxy )
	{
		$result = [
			'error'     => TRUE,
			'error_msg' => NULL
		];
		$me     = self::cmd( 'me', 'GET', $accessToken, [], $proxy );

		if ( isset( $me[ 'error' ] ) && isset( $me[ 'error' ][ 'message' ] ) )
		{
			$result[ 'error_msg' ] = $me[ 'error' ][ 'message' ];
		}
		else if ( ! isset( $me[ 'error' ] ) )
		{
			$result[ 'error' ] = FALSE;
		}

		return $result;
	}
}