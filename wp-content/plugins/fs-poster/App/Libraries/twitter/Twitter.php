<?php

namespace FSPoster\App\Libraries\twitter;

use Exception;
use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;
use FSPoster\App\Providers\Session;
use Abraham\TwitterOAuth\TwitterOAuth;
use FSPoster\App\Providers\SocialNetwork;

class Twitter extends SocialNetwork
{
	/**
	 * @param int $appId
	 * @param string $type
	 * @param string $message
	 * @param string $link
	 * @param array $images
	 * @param string $video
	 * @param string $accessToken
	 * @param string $accessTokenSecret
	 * @param string $proxy
	 *
	 * @return array
	 */
	public static function sendPost ( $appId, $type, $message, $link, $images, $video, $accessToken, $accessTokenSecret, $proxy )
	{
		$appInfo = DB::fetch( 'apps', [ 'id' => $appId, 'driver' => 'twitter' ] );
		if ( ! $appInfo )
		{
			return [
				'status'    => 'error',
				'error_msg' => 'Error! There isn\'t a Twitter App!'
			];
		}

		$parameters[ 'status' ] = $message;

		$connection = new TwitterOAuth( $appInfo[ 'app_key' ], $appInfo[ 'app_secret' ], $accessToken, $accessTokenSecret, $proxy );

		if ( $type === 'link' )
		{
			$parameters[ 'status' ] .= "\n" . $link;
		}

		if ( $type === 'image' && ! empty( $images ) && is_array( $images ) )
		{
			$uplaodedImages = [];
			$c              = 0;
			foreach ( $images as $imageURL )
			{
				if ( $c > 4 )
				{
					break;
				}

				if ( empty( $imageURL ) || ! is_string( $imageURL ) )
				{
					continue;
				}

				try
				{
					$uploadImage = $connection->upload( 'media/upload', [
						'media'      => $imageURL,
						'media_type' => ''
					], TRUE );

					if ( isset( $uploadImage->media_id_string ) && ! empty( $uploadImage->media_id_string ) && is_string( $uploadImage->media_id_string ) )
					{
						$uplaodedImages[] = $uploadImage->media_id_string;

						$c++;
					}
				}
				catch ( Exception $e )
				{

				}
			}

			if ( ! empty( $uplaodedImages ) )
			{
				$parameters[ 'media_ids' ] = implode( ',', $uplaodedImages );
			}
		}

		if ( $type === 'video' && ! empty( $video ) && is_string( $video ) )
		{
			try
			{
				$uploadImage = $connection->upload( 'media/upload', [
					'media'          => $video,
					'media_type'     => mime_content_type( $video ),
					'media_category' => 'tweet_video'
				], TRUE );

				if ( isset( $uploadImage->media_id_string ) && ! empty( $uploadImage->media_id_string ) && is_string( $uploadImage->media_id_string ) )
				{
					$parameters[ 'media_ids' ] = $uploadImage->media_id_string;
				}
			}
			catch ( Exception $e )
			{

			}
		}

		try
		{
			$result = $connection->post( 'statuses/update', $parameters );
		}
		catch ( Exception $e )
		{
			return [
				'status'    => 'error',
				'error_msg' => esc_html( $e->getMessage() )
			];
		}

		if ( $connection->getLastHttpCode() == 200 )
		{
			return [
				'status' => 'ok',
				'id'     => isset( $result->id_str ) && is_string( $result->id_str ) ? (string) $result->id_str : ''
			];
		}
		else if ( isset( $result->errors ) && is_array( $result->errors ) && ! empty( $result->errors ) )
		{
			$error    = reset( $result->errors );
			$errorMsg = isset( $error->message ) && is_string( $error->message ) ? (string) $error->message : 'Error! (-)';

			return [
				'status'    => 'error',
				'error_msg' => $errorMsg
			];
		}
		else
		{
			return [
				'status'    => 'error',
				'error_msg' => 'Error! (?)'
			];
		}
	}

	/**
	 * @param $appId
	 *
	 * @return string
	 */
	public static function getLoginURL ( $appId )
	{
		$proxy = Request::get( 'proxy', '', 'string' );

		Session::set( 'app_id', $appId );
		Session::set( 'proxy', $proxy );

		$appInf = DB::fetch( 'apps', [ 'id' => $appId, 'driver' => 'twitter' ] );

		try
		{
			$connection = new TwitterOAuth( $appInf[ 'app_key' ], $appInf[ 'app_secret' ], NULL, NULL, $proxy );

			$request_token = $connection->oauth( 'oauth/request_token', [
				'oauth_callback' => self::callbackURL()
			] );
		}
		catch ( Exception $e )
		{
			self::error( $e->getMessage() );
		}

		Session::set( 'oauth_token', $request_token[ 'oauth_token' ] );
		Session::set( 'oauth_token_secret', $request_token[ 'oauth_token_secret' ] );

		return $connection->url( 'oauth/authorize', [
			'oauth_token' => $request_token[ 'oauth_token' ]
		] );
	}

	/**
	 * @return string
	 */
	public static function callbackURL ()
	{
		return site_url() . '/?twitter_callback=1';
	}

	public static function getAccessToken ()
	{
		$appId          = (int) Session::get( 'app_id' );
		$oauth_verifier = Request::get( 'oauth_verifier', '', 'str' );
		$oauth_token    = Request::get( 'oauth_token', '', 'str' );

		if ( empty( $appId ) || empty( $oauth_verifier ) || empty( $oauth_token ) )
		{
			return FALSE;
		}

		$appInf = DB::fetch( 'apps', [ 'id' => $appId, 'driver' => 'twitter' ] );

		$proxy              = Session::get( 'proxy' );
		$oauth_token        = Session::get( 'oauth_token' );
		$oauth_token_secret = Session::get( 'oauth_token_secret' );

		$connection = new TwitterOAuth( $appInf[ 'app_key' ], $appInf[ 'app_secret' ], $oauth_token, $oauth_token_secret, $proxy );

		$access_token = $connection->oauth( "oauth/access_token", [
			'oauth_verifier' => $oauth_verifier
		] );

		Session::remove( 'app_id' );
		Session::remove( 'proxy' );
		Session::remove( 'oauth_token' );
		Session::remove( 'oauth_token_secret' );

		if ( ! ( isset( $access_token[ 'oauth_token' ] ) && isset( $access_token[ 'oauth_token_secret' ] ) ) )
		{
			self::error();
		}

		self::authorize( $appInf, $access_token[ 'oauth_token' ], $access_token[ 'oauth_token_secret' ], $proxy );
	}

	/**
	 * @param array $appInf
	 * @param string $oauth_token
	 * @param string $oauth_token_secret
	 * @param string $proxy
	 */
	public static function authorize ( $appInf, $oauth_token, $oauth_token_secret, $proxy )
	{
		$connection = new TwitterOAuth( $appInf[ 'app_key' ], $appInf[ 'app_secret' ], $oauth_token, $oauth_token_secret, $proxy );
		$user       = $connection->get( "account/verify_credentials" );

		if ( ! ( ! empty( $user ) && isset( $user->id ) ) )
		{
			self::error();
		}

		$checkUserExist = DB::fetch( 'accounts', [
			'blog_id'    => Helper::getBlogId(),
			'user_id'    => get_current_user_id(),
			'driver'     => 'twitter',
			'profile_id' => $user->id_str
		] );

		if ( $checkUserExist )
		{
			$accId = $checkUserExist[ 'id' ];

			DB::DB()->delete( DB::table( 'accounts' ), [ 'id' => $accId ] );
			DB::DB()->delete( DB::table( 'account_access_tokens' ), [ 'account_id' => $accId ] );
		}

		DB::DB()->insert( DB::table( 'accounts' ), [
			'blog_id'    => Helper::getBlogId(),
			'user_id'    => get_current_user_id(),
			'driver'     => 'twitter',
			'name'       => $user->name,
			'profile_id' => $user->id_str,
			'email'      => '',
			'username'   => $user->screen_name,
			'proxy'      => $proxy
		] );

		DB::DB()->insert( DB::table( 'account_access_tokens' ), [
			'account_id'          => DB::DB()->insert_id,
			'app_id'              => $appInf[ 'id' ],
			'access_token'        => $oauth_token,
			'access_token_secret' => $oauth_token_secret
		] );

		self::closeWindow();
	}

	/**
	 * @param integer $post_id
	 * @param string $accessToken
	 * @param string $accessTokenSecret
	 * @param integer $appId
	 * @param string $proxy
	 *
	 * @return array
	 */
	public static function getStats ( $post_id, $accessToken, $accessTokenSecret, $appId, $proxy )
	{
		$appInfo = DB::fetch( 'apps', [ 'id' => $appId, 'driver' => 'twitter' ] );

		$connection = new TwitterOAuth( $appInfo[ 'app_key' ], $appInfo[ 'app_secret' ], $accessToken, $accessTokenSecret, $proxy );
		$stat       = (array) $connection->get( "statuses/show/" . $post_id );

		return [
			'comments' => 0,
			'like'     => isset( $stat[ 'favorite_count' ] ) ? (int) $stat[ 'favorite_count' ] : 0,
			'shares'   => isset( $stat[ 'retweet_count' ] ) ? (int) $stat[ 'retweet_count' ] : 0,
			'details'  => ''
		];
	}

	/**
	 * @param integer $appId
	 * @param string $accessToken
	 * @param string $accessTokenSecret
	 * @param string $proxy
	 *
	 * @return array
	 */
	public static function checkAccount ( $appId, $accessToken, $accessTokenSecret, $proxy )
	{
		$result  = [
			'error'     => TRUE,
			'error_msg' => NULL
		];
		$appInfo = DB::fetch( 'apps', [ 'id' => $appId, 'driver' => 'twitter' ] );

		if ( ! $appInfo )
		{
			$result[ 'error_msg' ] = 'Error! There isn\'t a Twitter App!';
		}
		else
		{
			$connection = new TwitterOAuth( $appInfo[ 'app_key' ], $appInfo[ 'app_secret' ], $accessToken, $accessTokenSecret, $proxy );
			$user       = $connection->get( "account/verify_credentials" );

			if ( ! empty( $user ) && isset( $user->id ) )
			{
				$result[ 'error' ] = FALSE;
			}
		}

		return $result;
	}
}