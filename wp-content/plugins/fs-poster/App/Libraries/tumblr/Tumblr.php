<?php

namespace FSPoster\App\Libraries\tumblr;

use Exception;
use Tumblr\API\Client;
use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Session;
use FSPoster\App\Providers\Request;
use FSPoster\App\Providers\SocialNetwork;

class Tumblr extends SocialNetwork
{
	private static $apps = [];

	/**
	 * @return string
	 */
	public static function callbackURL ()
	{
		return site_url() . '/?tumblr_callback=1';
	}

	/**
	 * @param integer $appId
	 * @param string $consumerKey
	 * @param string $consumerSecret
	 * @param string $accessToken
	 * @param string $accessTokenSecret
	 * @param string $proxy
	 */
	public static function authorize ( $appId, $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret, $proxy )
	{
		$client = new Client( $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret, $proxy );

		try
		{
			$me = $client->getUserInfo();
		}
		catch ( Exception $e )
		{
			self::error( $e->getMessage() );
		}

		$username = $me->user->name;

		$checkLoginRegistered = DB::fetch( 'accounts', [
			'blog_id'  => Helper::getBlogId(),
			'user_id'  => get_current_user_id(),
			'driver'   => 'tumblr',
			'username' => $username
		] );

		$dataSQL = [
			'blog_id'  => Helper::getBlogId(),
			'user_id'  => get_current_user_id(),
			'name'     => $username,
			'driver'   => 'tumblr',
			'username' => $username,
			'proxy'    => $proxy
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
			'account_id'          => $accId,
			'app_id'              => $appId,
			'access_token'        => $accessToken,
			'access_token_secret' => $accessTokenSecret
		] );

		foreach ( $me->user->blogs as $blogInf )
		{
			DB::DB()->insert( DB::table( 'account_nodes' ), [
				'blog_id'      => Helper::getBlogId(),
				'user_id'      => get_current_user_id(),
				'driver'       => 'tumblr',
				'screen_name'  => $blogInf->name,
				'account_id'   => $accId,
				'node_type'    => 'blog',
				'node_id'      => $blogInf->name,
				'name'         => $blogInf->name,
				'access_token' => NULL,
				'category'     => $blogInf->primary ? 'primary' : 'not-primary'
			] );
		}

		self::closeWindow();
	}

	/**
	 * @param integer $appId
	 *
	 * @return mixed
	 */
	private static function getAppInf ( $appId )
	{
		if ( ! isset( self::$apps[ $appId ] ) )
		{
			self::$apps[ $appId ] = DB::fetch( 'apps', $appId );
		}

		return self::$apps[ $appId ];
	}

	/**
	 * @param array $blogInfo
	 * @param string $type
	 * @param string $title
	 * @param string $message
	 * @param string $link
	 * @param array $images
	 * @param string $video
	 * @param string $accessToken
	 * @param string $accessTokenSecret
	 * @param integer $appId
	 * @param string $proxy
	 *
	 * @return array
	 */
	public static function sendPost ( $blogInfo, $type, $title, $message, $link, $images, $video, $accessToken, $accessTokenSecret, $appId, $proxy )
	{
		$appInf = self::getAppInf( $appId );

		$sendData = [];

		$client = new Client( $appInf[ 'app_key' ], $appInf[ 'app_secret' ], $accessToken, $accessTokenSecret, $proxy );

		if ( $type === 'image' )
		{
			$sendData[ 'type' ] = 'photo';
			if ( ! empty( $link ) )
			{
				$sendData[ 'link' ] = $link;
			}
			$sendData[ 'data' ]    = $images;
			$sendData[ 'caption' ] = $message;
		}
		else if ( $type === 'video' )
		{
			$sendData[ 'type' ]    = 'video';
			$sendData[ 'data' ]    = $video;
			$sendData[ 'caption' ] = $message;
		}
		else
		{
			$sendData[ 'type' ]        = 'link';
			$sendData[ 'title' ]       = $title;
			$sendData[ 'url' ]         = $link;
			$sendData[ 'description' ] = $message;
		}

		try
		{
			$result = $client->createPost( $blogInfo[ 'node_id' ], $sendData );
		}
		catch ( Exception $e )
		{
			return [
				'status'    => 'error',
				'error_msg' => esc_html( $e->getMessage() )
			];
		}

		return [
			'status' => 'ok',
			'id'     => $type === 'video' ? '' : strval( $result->id )
		];
	}

	/**
	 * @param integer $appId
	 *
	 * @return string
	 */
	public static function getLoginURL ( $appId )
	{
		$proxy = Request::get( 'proxy', '', 'string' );

		Session::set( 'app_id', $appId );
		Session::set( 'proxy', $proxy );

		$appInf = DB::fetch( 'apps', [ 'id' => $appId, 'driver' => 'tumblr' ] );
		if ( ! $appInf )
		{
			self::error( esc_html__( 'Error! The App isn\'t found!' ) );
		}

		$consumerKey    = urlencode( $appInf[ 'app_key' ] );
		$consumerSecret = urlencode( $appInf[ 'app_secret' ] );
		$callbackUrl    = self::callbackUrl();

		$client = new Client( $consumerKey, $consumerSecret, NULL, NULL, $proxy );

		$requestHandler = $client->getRequestHandler();
		$requestHandler->setBaseUrl( 'https://www.tumblr.com/' );

		try
		{
			$resp = $requestHandler->request( 'POST', 'oauth/request_token', [
				'oauth_callback' => $callbackUrl
			] );
		}
		catch ( Exception $e )
		{
			self::error( $e->getMessage() );
		}

		$result = (string) $resp->body;
		parse_str( $result, $keys );

		Session::set( 'tmp_oauth_token', $keys[ 'oauth_token' ] );
		Session::set( 'tmp_oauth_token_secret', $keys[ 'oauth_token_secret' ] );

		return 'https://www.tumblr.com/oauth/authorize?oauth_token=' . $keys[ 'oauth_token' ];
	}

	/**
	 * @return bool
	 */
	public static function getAccessToken ()
	{
		$appId                  = (int) Session::get( 'app_id' );
		$tmp_oauth_token        = Session::get( 'tmp_oauth_token' );
		$tmp_oauth_token_secret = Session::get( 'tmp_oauth_token_secret' );

		if ( empty( $appId ) || empty( $tmp_oauth_token ) || empty( $tmp_oauth_token_secret ) )
		{
			return FALSE;
		}

		$code = Request::get( 'oauth_verifier', '', 'string' );

		if ( empty( $code ) )
		{
			$error_message = Request::get( 'error_message', '', 'str' );

			self::error( $error_message );
		}

		$appInf         = DB::fetch( 'apps', [ 'id' => $appId, 'driver' => 'tumblr' ] );
		$consumerKey    = urlencode( $appInf[ 'app_key' ] );
		$consumerSecret = urlencode( $appInf[ 'app_secret' ] );

		$proxy = Session::get( 'proxy' );

		Session::remove( 'app_id' );
		Session::remove( 'tmp_oauth_token' );
		Session::remove( 'tmp_oauth_token_secret' );
		Session::remove( 'proxy' );

		$client = new Client( $consumerKey, $consumerSecret, $tmp_oauth_token, $tmp_oauth_token_secret, $proxy );

		$requestHandler = $client->getRequestHandler();
		$requestHandler->setBaseUrl( 'https://www.tumblr.com/' );

		try
		{
			$resp = $requestHandler->request( 'POST', 'oauth/access_token', [ 'oauth_verifier' => $code ] );
		}
		catch ( Exception $e )
		{
			self::error( $e->getMessage() );
		}

		$out  = (string) $resp->body;
		$data = [];
		parse_str( $out, $data );

		$access_token        = $data[ 'oauth_token' ];
		$access_token_secret = $data[ 'oauth_token_secret' ];

		self::authorize( $appId, $consumerKey, $consumerSecret, $access_token, $access_token_secret, $proxy );
	}

	/**
	 * @param integer $post_id
	 * @param string $accessToken
	 * @param string $proxy
	 *
	 * @return array
	 */
	public static function getStats ( $post_id, $accessToken, $proxy )
	{
		return [
			'comments' => 0,
			'like'     => 0,
			'shares'   => 0,
			'details'  => 0
		];
	}

	/**
	 * @param string $accessToken
	 * @param string $accessTokenSecret
	 * @param integer $appId
	 * @param string $proxy
	 *
	 * @return array
	 */
	public static function checkAccount ( $accessToken, $accessTokenSecret, $appId, $proxy )
	{
		$result = [
			'error'     => TRUE,
			'error_msg' => NULL
		];
		$appInf = self::getAppInf( $appId );

		$client = new Client( $appInf[ 'app_key' ], $appInf[ 'app_secret' ], $accessToken, $accessTokenSecret, $proxy );

		try
		{
			$client->getUserInfo();

			$result[ 'error' ] = FALSE;
		}
		catch ( Exception $e )
		{
			$result[ 'error_msg' ] = $e->getMessage();
		}

		return $result;
	}
}