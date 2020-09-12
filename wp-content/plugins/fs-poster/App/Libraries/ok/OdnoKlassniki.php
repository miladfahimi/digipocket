<?php

namespace FSPoster\App\Libraries\ok;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Curl;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;
use FSPoster\App\Providers\Session;
use FSPoster\App\Providers\SocialNetwork;

class OdnoKlassniki extends SocialNetwork
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
	public static function sendPost ( $node_info, $type, $message, $link, $images, $video, $accessToken, $appPublicKey, $appPrivateKey, $proxy )
	{
		$sendData = [
			'text_link_preview' => TRUE
		];

		if ( isset( $node_info[ 'node_type' ] ) && $node_info[ 'node_type' ] === 'group' )
		{
			$sendData[ 'gid' ]  = $node_info[ 'node_id' ];
			$sendData[ 'type' ] = 'GROUP_THEME';
		}

		$sendData[ 'attachment' ] = [
			'media' => []
		];

		if ( ! empty( $message ) )
		{
			$sendData[ 'attachment' ][ 'media' ][] = [ 'type' => 'text', 'text' => $message ];
		}

		if ( ! empty( $link ) && $type === 'link' )
		{
			$sendData[ 'attachment' ][ 'media' ][] = [ 'type' => 'link', 'url' => $link ];
		}

		if ( $type === 'image' )
		{
			$uplServerSendData = [ 'count' => count( $images ) ];

			if ( isset( $node_info[ 'node_type' ] ) && $node_info[ 'node_type' ] === 'group' )
			{
				$uplServerSendData[ 'gid' ] = $node_info[ 'node_id' ];
			}

			$uplServer = self::cmd( 'photosV2.getUploadUrl', 'GET', $accessToken, $appPublicKey, $appPrivateKey, $uplServerSendData, $proxy );

			if ( isset( $uplServer[ 'upload_url' ] ) )
			{
				$uplServer = $uplServer[ 'upload_url' ];

				$images2 = [];
				$i       = 0;
				foreach ( $images as $imageURL )
				{
					$i++;
					if ( function_exists( 'curl_file_create' ) )
					{
						$images2[ 'pic' . $i ] = curl_file_create( $imageURL );
					}
					else
					{
						$images2[ 'pic' . $i ] = '@' . $imageURL;
					}
				}

				$uploadFile = Curl::getContents( $uplServer, 'POST', $images2, [], $proxy );
				$uploadFile = json_decode( $uploadFile, TRUE );

				$okMediaJson           = [];
				$okMediaJson[ 'type' ] = 'photo';
				$okMediaJson[ 'list' ] = [];

				foreach ( $uploadFile[ 'photos' ] as $photoTok )
				{
					$okMediaJson[ 'list' ][] = [ 'id' => $photoTok[ 'token' ] ];
				}

				$sendData[ 'attachment' ][ 'media' ][] = $okMediaJson;
			}
		}
		else if ( $type === 'video' )
		{
			$uplServerSendData = [
				'file_name' => mb_substr( $message, 0, 50, 'UTF-8' ),
				'file_size' => 0
			];

			if ( isset( $node_info[ 'node_type' ] ) && $node_info[ 'node_type' ] === 'group' )
			{
				$uplServerSendData[ 'gid' ] = $node_info[ 'node_id' ];
			}

			$videoUplServer = self::cmd( 'video.getUploadUrl', 'GET', $accessToken, $appPublicKey, $appPrivateKey, $uplServerSendData, $proxy );

			if ( isset( $videoUplServer[ 'upload_url' ] ) )
			{
				$videoId   = $videoUplServer[ 'video_id' ];
				$uploadURL = $videoUplServer[ 'upload_url' ];

				$uploadFile = Curl::getContents( $uploadURL, 'POST', [
					'file' => function_exists( 'curl_file_create' ) ? curl_file_create( $video ) : '@' . $video
				], [], $proxy );
				$uploadFile = json_decode( $uploadFile, TRUE );

				$okMediaJson              = [];
				$okMediaJson[ 'type' ]    = 'movie-reshare';
				$okMediaJson[ 'movieId' ] = $videoId;

				$sendData[ 'attachment' ][ 'media' ][] = $okMediaJson;
			}
		}

		$sendData[ 'attachment' ] = json_encode( $sendData[ 'attachment' ] );

		$endPoint = 'mediatopic.post';

		$result = self::cmd( $endPoint, 'POST', $accessToken, $appPublicKey, $appPrivateKey, $sendData, $proxy );

		if ( isset( $result[ 'error_msg' ] ) )
		{
			$result2 = [
				'status'    => 'error',
				'error_msg' => $result[ 'error_msg' ]
			];
		}
		else
		{
			if ( isset( $node_info[ 'node_type' ] ) && $node_info[ 'node_type' ] === 'group' )
			{
				$pIdFull = $node_info[ 'node_id' ] . '/topic/' . $result[ 'id' ];
			}
			else
			{
				$pIdFull = $node_info[ 'profile_id' ] . '/statuses/' . $result[ 'id' ];
			}

			$result2 = [
				'status' => 'ok',
				'id'     => $pIdFull
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
	public static function cmd ( $cmd, $method, $accessToken, $appPublicKey, $appPrivateKey, array $data = [], $proxy = '' )
	{
		$data[ "application_key" ] = $appPublicKey;
		$data[ "method" ]          = $cmd;
		$data[ "sig" ]             = self::calcSignature( $cmd, $data, $accessToken, $appPrivateKey );
		$data[ 'access_token' ]    = $accessToken;

		$url = 'https://api.odnoklassniki.ru/fb.do';

		$method = $method === 'POST' ? 'POST' : ( $method === 'DELETE' ? 'DELETE' : 'GET' );

		$data1 = Curl::getContents( $url, $method, $data, [], $proxy, TRUE );
		$data  = json_decode( $data1, TRUE );

		if ( ! is_array( $data ) )
		{
			if ( is_numeric( $data ) )
			{
				$data = [ 'id' => $data ];
			}
			else
			{
				$data = [
					'error' => [ 'message' => 'Error data!' ]
				];
			}
		}

		return $data;
	}

	private static function calcSignature ( $methodName, $parameters, $accessToken, $appPrivateKey )
	{
		ksort( $parameters );

		$requestStr = '';
		foreach ( $parameters as $key => $value )
		{
			$requestStr .= $key . '=' . $value;
		}

		$requestStr .= md5( $accessToken . $appPrivateKey );

		return md5( $requestStr );
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

		$appInf = DB::fetch( 'apps', [ 'id' => $appId, 'driver' => 'ok' ] );
		$appId  = $appInf[ 'app_id' ];

		$permissions = self::getScope();

		$callbackUrl = self::callbackUrl();

		return "https://www.odnoklassniki.ru/oauth/authorize?client_id={$appId}&scope={$permissions}&response_type=code&redirect_uri={$callbackUrl}";
	}

	/**
	 * @return string
	 */
	public static function getScope ()
	{
		return 'VALUABLE_ACCESS,SET_STATUS,PHOTO_CONTENT,LONG_ACCESS_TOKEN,PUBLISH_TO_STREAM,GROUP_CONTENT,VIDEO_CONTENT';
	}

	/**
	 * @return string
	 */
	public static function callbackURL ()
	{
		return site_url() . '/?ok_callback=1';
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
			$error_message = Request::get( 'error_message', '', 'str' );

			self::error( $error_message );
		}

		$proxy = Session::get( 'proxy' );

		Session::remove( 'app_id' );
		Session::remove( 'proxy' );

		$appInf    = DB::fetch( 'apps', [ 'id' => $appId, 'driver' => 'ok' ] );
		$appSecret = $appInf[ 'app_secret' ];
		$appId2    = $appInf[ 'app_id' ];

		$token_url = 'https://api.odnoklassniki.ru/oauth/token.do';

		$postData = [
			'code'          => $code,
			'redirect_uri'  => self::callbackUrl(),
			'grant_type'    => 'authorization_code',
			'client_id'     => $appId2,
			'client_secret' => $appSecret
		];

		$response = Curl::getContents( $token_url, 'POST', $postData, [], $proxy, TRUE );
		$params   = json_decode( $response, TRUE );

		if ( isset( $params[ 'error_description' ] ) )
		{
			self::error( $params[ 'error_description' ] );
		}

		$access_token  = esc_html( $params[ 'access_token' ] );
		$refresh_token = esc_html( $params[ 'refresh_token' ] );

		$expireIn = Date::dateTimeSQL( 'now', '+' . (int) $params[ 'expires_in' ] . ' seconds' );

		self::authorize( $appId, $appInf[ 'app_key' ], $appInf[ 'app_secret' ], $access_token, $refresh_token, $expireIn, $proxy );
	}

	/**
	 * @param integer $appId
	 * @param string $accessToken
	 * @param string $scExpireIn
	 * @param string $proxy
	 */
	public static function authorize ( $appId, $appPublicKey, $appPrivateKey, $accessToken, $refreshToken, $scExpireIn, $proxy )
	{
		$me = self::cmd( 'users.getCurrentUser', 'GET', $accessToken, $appPublicKey, $appPrivateKey, [], $proxy );

		if ( isset( $me[ 'error_msg' ] ) )
		{
			Helper::response( FALSE, $me[ 'error_msg' ] );
		}

		$meId = $me[ 'uid' ];

		$checkLoginRegistered = DB::fetch( 'accounts', [
			'blog_id'    => Helper::getBlogId(),
			'user_id'    => get_current_user_id(),
			'driver'     => 'ok',
			'profile_id' => $meId
		] );

		$dataSQL = [
			'blog_id'     => Helper::getBlogId(),
			'user_id'     => get_current_user_id(),
			'name'        => $me[ 'name' ],
			'driver'      => 'ok',
			'profile_id'  => $meId,
			'profile_pic' => $me[ 'pic_1' ],
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
			'account_id'    => $accId,
			'app_id'        => $appId,
			'expires_on'    => $scExpireIn,
			'access_token'  => $accessToken,
			'refresh_token' => $refreshToken
		] );

		// fetch groups list
		$groupIDsList = self::cmd( 'group.getUserGroupsV2', 'GET', $accessToken, $appPublicKey, $appPrivateKey, [ 'count' => 100 ], $proxy );
		$ids_arr      = [];

		if ( isset( $groupIDsList[ 'groups' ] ) && is_array( $groupIDsList[ 'groups' ] ) )
		{
			foreach ( $groupIDsList[ 'groups' ] as $groupIdInf )
			{
				$ids_arr[] = $groupIdInf[ 'groupId' ];
			}
		}

		if ( ! empty( $ids_arr ) )
		{
			$ids_arr    = implode( ',', $ids_arr );
			$groupsList = self::cmd( 'group.getInfo', 'GET', $accessToken, $appPublicKey, $appPrivateKey, [ 'uids'   => $ids_arr,
			                                                                                                'fields' => 'pic_avatar,uid,name'
			], $proxy );
			foreach ( $groupsList as $groupInf )
			{
				DB::DB()->insert( DB::table( 'account_nodes' ), [
					'blog_id'    => Helper::getBlogId(),
					'user_id'    => get_current_user_id(),
					'driver'     => 'ok',
					'account_id' => $accId,
					'node_type'  => 'group',
					'node_id'    => $groupInf[ 'uid' ],
					'name'       => $groupInf[ 'name' ],
					'cover'      => $groupInf[ 'picAvatar' ],
					'category'   => 'group'
				] );
			}
		}

		self::closeWindow();
	}

	/**
	 * @param $tokenInfo
	 */
	public static function accessToken ( $tokenInfo )
	{
		if ( ( Date::epoch() + 30 ) > Date::epoch( $tokenInfo[ 'expires_on' ] ) )
		{
			return self::refreshToken( $tokenInfo );
		}

		return $tokenInfo[ 'access_token' ];
	}

	/**
	 * @param array $tokenInfo
	 *
	 * @return string
	 */
	public static function refreshToken ( $tokenInfo )
	{
		$appId = $tokenInfo[ 'app_id' ];

		$account_info = DB::fetch( 'accounts', $tokenInfo[ 'account_id' ] );
		$proxy        = $account_info[ 'proxy' ];

		$appInf       = DB::fetch( 'apps', $appId );
		$appId2       = $appInf[ 'app_id' ];
		$appSecret    = $appInf[ 'app_secret' ];
		$refreshToken = $tokenInfo[ 'refresh_token' ];

		$url = 'https://api.odnoklassniki.ru/oauth/token.do';

		$postData = [
			'refresh_token' => $refreshToken,
			'grant_type'    => 'refresh_token',
			'client_id'     => $appId2,
			'client_secret' => $appSecret,
		];

		$response = Curl::getContents( $url, 'POST', $postData, [], $proxy, TRUE );
		$params   = json_decode( $response, TRUE );

		if ( isset( $params[ 'error_description' ] ) )
		{
			return FALSE;
		}

		$access_token = esc_html( $params[ 'access_token' ] );
		$expiresIn    = Date::dateTimeSQL( 'now', '+' . (int) $params[ 'expires_in' ] . ' seconds' );

		DB::DB()->update( DB::table( 'account_access_tokens' ), [
			'access_token' => $access_token,
			'expires_on'   => $expiresIn
		], [ 'id' => $tokenInfo[ 'id' ] ] );

		$tokenInfo[ 'access_token' ] = $access_token;
		$tokenInfo[ 'expires_on' ]   = $expiresIn;

		return $access_token;
	}

	/**
	 * @param integer $post_id
	 *
	 * @return array
	 */
	public static function getStats ( $post_id, $accessToken, $appPublicKey, $appPrivateKey, $proxy )
	{
		$result = self::cmd( 'mediatopic.getByIds', 'GET', $accessToken, $appPublicKey, $appPrivateKey, [
			'topic_ids' => $post_id,
			'fields'    => 'media_topic.*'
		], $proxy );

		return [
			'comments' => isset( $result[ 'media_topics' ][ 0 ][ 'discussion_summary' ][ 'comments_count' ] ) ? $result[ 'media_topics' ][ 0 ][ 'discussion_summary' ][ 'comments_count' ] : 0,
			'like'     => isset( $result[ 'media_topics' ][ 0 ][ 'like_summary' ][ 'count' ] ) ? $result[ 'media_topics' ][ 0 ][ 'like_summary' ][ 'count' ] : 0,
			'shares'   => isset( $result[ 'media_topics' ][ 0 ][ 'reshare_summary' ][ 'count' ] ) ? $result[ 'media_topics' ][ 0 ][ 'reshare_summary' ][ 'count' ] : 0,
			'details'  => ''
		];
	}

	/**
	 * @param string $accessToken
	 * @param string $appPublicKey
	 * @param string $appPrivateKey
	 * @param string $proxy
	 *
	 * @return array
	 */
	public static function checkAccount ( $accessToken, $appPublicKey, $appPrivateKey, $proxy )
	{
		$result = [
			'error'     => TRUE,
			'error_msg' => NULL
		];
		$me     = self::cmd( 'users.getCurrentUser', 'GET', $accessToken, $appPublicKey, $appPrivateKey, [], $proxy );

		if ( isset( $me[ 'error_msg' ] ) )
		{
			$result[ 'error_msg' ] = $me[ 'error_msg' ];
		}
		else if ( ! isset( $me[ 'error_msg' ] ) )
		{
			$result[ 'error' ] = FALSE;
		}

		return $result;
	}
}
