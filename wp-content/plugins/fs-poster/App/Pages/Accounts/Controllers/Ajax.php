<?php

namespace FSPoster\App\Pages\Accounts\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Date;
use FSPoster\App\Libraries\vk\Vk;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;
use FSPoster\App\Libraries\reddit\Reddit;
use FSPoster\App\Libraries\telegram\Telegram;
use FSPoster\App\Libraries\wordpress\Wordpress;
use FSPoster\App\Libraries\fb\FacebookCookieApi;
use FSPoster\App\Libraries\instagram\InstagramApi;
use FSPoster\App\Libraries\google\GoogleMyBusiness;
use FSPoster\App\Libraries\pinterest\PinterestCookieApi;
use FSPoster\App\Libraries\instagram\InstagramCookieMethod;
use FSPoster\App\Libraries\instagram\InstagramLoginPassMethod;

trait Ajax
{
	public function add_new_fb_account_with_cookie ()
	{
		$cookieCuser = Request::post( 'cookie_c_user', '', 'string' );
		$cookieXs    = Request::post( 'cookie_xs', '', 'string' );
		$proxy       = Request::post( 'proxy', '', 'string' );

		$fb   = new FacebookCookieApi( $cookieCuser, $cookieXs, $proxy );
		$data = $fb->authorizeFbUser();

		if ( $data === FALSE )
		{
			Helper::response( FALSE, esc_html__( 'The entered cookies are wrong!', 'fs-poster' ) );
		}

		Helper::response( TRUE );
	}

	public function delete_account ()
	{
		$id = Request::post( 'id', 0, 'num' );

		if ( ! ( $id > 0 ) )
		{
			exit();
		}

		$check_account = DB::fetch( 'accounts', $id );
		if ( ! $check_account )
		{
			Helper::response( FALSE, esc_html__( 'The account isn\'t found!', 'fs-poster' ) );
		}
		else
		{
			if ( $check_account[ 'user_id' ] != get_current_user_id() )
			{
				Helper::response( FALSE, esc_html__( 'You don\'t have permission to remove the account! Only the account owner can remove it.', 'fs-poster' ) );
			}
		}

		DB::DB()->delete( DB::table( 'accounts' ), [ 'id' => $id ] );
		DB::DB()->delete( DB::table( 'account_status' ), [ 'account_id' => $id ] );
		DB::DB()->delete( DB::table( 'account_access_tokens' ), [ 'account_id' => $id ] );

		DB::DB()->query( "DELETE FROM " . DB::table( 'account_node_status' ) . " WHERE node_id IN (SELECT id FROM " . DB::table( 'account_nodes' ) . " WHERE account_id='$id')" );
		DB::DB()->delete( DB::table( 'account_nodes' ), [ 'account_id' => $id ] );

		if ( $check_account[ 'driver' ] === 'instagram' )
		{
			$checkIfUsernameExist = DB::fetch( 'accounts', [
				'blog_id'  => Helper::getBlogId(),
				'username' => $check_account[ 'username' ],
				'driver'   => $check_account[ 'driver' ]
			] );

			if ( ! $checkIfUsernameExist )
			{
				DB::DB()->delete( DB::table( 'account_sessions' ), [
					'driver'   => $check_account[ 'driver' ],
					'username' => $check_account[ 'username' ]
				] );
			}
		}
		else
		{
			if ( $check_account[ 'driver' ] === 'pinterest' )
			{
				DB::DB()->delete( DB::table( 'account_sessions' ), [
					'driver'   => 'pinterest',
					'username' => $check_account[ 'username' ]
				] );
			}
		}

		Helper::response( TRUE );
	}

	public function get_accounts ()
	{
		$social_networks = [
			'fb',
			'twitter',
			'instagram',
			'linkedin',
			'vk',
			'pinterest',
			'reddit',
			'tumblr',
			'ok',
			'google_b',
			'telegram',
			'medium',
			'wordpress'
		];
		$name            = Request::post( 'name', '', 'string' );

		if ( empty( $name ) || ! in_array( $name, $social_networks ) )
		{
			Helper::response( FALSE );
		}

		$data = Pages::action( 'Accounts', 'get_' . $name . '_accounts' );

		if ( $name === 'telegram' )
		{
			$data[ 'button_text' ] = esc_html__( 'ADD A BOT', 'fs-poster' );
			$data[ 'err_text' ]    = esc_html__( 'bots', 'fs-poster' );
		}
		else if ( $name === 'wordpress' )
		{
			$data[ 'button_text' ] = esc_html__( 'ADD A SITE', 'fs-poster' );
			$data[ 'err_text' ]    = esc_html__( 'sites', 'fs-poster' );
		}
		else
		{
			$data[ 'button_text' ] = esc_html__( 'ADD AN ACCOUNT', 'fs-poster' );
			$data[ 'err_text' ]    = esc_html__( 'accounts', 'fs-poster' );
		}

		Pages::modal( 'Accounts', $name . '/index', $data );
	}

	public function account_activity_change ()
	{
		$id          = Request::post( 'id', '0', 'num' );
		$checked     = Request::post( 'checked', -1, 'num', [ '0', '1' ] );
		$filter_type = Request::post( 'filter_type', '', 'string', [ 'in', 'ex' ] );
		$categories  = Request::post( 'categories', [], 'array' );

		if ( ! ( $id > 0 && $checked > -1 ) )
		{
			Helper::response( FALSE );
		}

		$categories_arr = [];
		foreach ( $categories as $categId )
		{
			if ( is_numeric( $categId ) && $categId > 0 )
			{
				$categories_arr[] = (int) $categId;
			}
		}
		$categories_arr = implode( ',', $categories_arr );

		if ( ( ! empty( $categories_arr ) && empty( $filter_type ) ) || ( empty( $categories_arr ) && ! empty( $filter_type ) ) )
		{
			Helper::response( FALSE, esc_html__( 'Please select categories and filter type!', 'fs-poster' ) );
		}

		$categories_arr = empty( $categories_arr ) ? NULL : $categories_arr;
		$filter_type    = empty( $filter_type ) || empty( $categories_arr ) ? 'no' : $filter_type;

		$check_account = DB::DB()->get_row( "SELECT * FROM " . DB::table( 'accounts' ) . " WHERE id='" . $id . "'", ARRAY_A );

		if ( ! $check_account )
		{
			Helper::response( FALSE, esc_html__( 'The account isn\'t found!', 'fs-poster' ) );
		}

		if ( $check_account[ 'user_id' ] != get_current_user_id() && $check_account[ 'is_public' ] != 1 )
		{
			Helper::response( FALSE, esc_html__( 'Account not found or you do not have a permission for this account!', 'fs-poster' ) );
		}

		if ( $checked )
		{
			$checkIfIsActive = DB::fetch( 'account_status', [
				'account_id' => $id,
				'user_id'    => get_current_user_id(),
			] );

			if ( ! $checkIfIsActive )
			{
				DB::DB()->insert( DB::table( 'account_status' ), [
					'account_id'  => $id,
					'user_id'     => get_current_user_id(),
					'filter_type' => $filter_type,
					'categories'  => $categories_arr
				] );
			}
			else
			{
				DB::DB()->update( DB::table( 'account_status' ), [
					'filter_type' => $filter_type,
					'categories'  => $categories_arr
				], [ 'id' => $checkIfIsActive[ 'id' ] ] );
			}
		}
		else
		{
			DB::DB()->delete( DB::table( 'account_status' ), [
				'account_id' => $id,
				'user_id'    => get_current_user_id()
			] );
		}

		Helper::response( TRUE );
	}

	public function make_account_public ()
	{
		$id      = Request::post( 'id', 0, 'num' );
		$checked = Request::post( 'checked', '', 'string' );

		if ( ! ( ( $checked === '1' || $checked === '0' ) && $id > 0 ) )
		{
			Helper::response( FALSE );
		}

		$check_account = DB::DB()->get_row( "SELECT * FROM " . DB::table( 'accounts' ) . " WHERE id='" . $id . "'", ARRAY_A );

		if ( ! $check_account )
		{
			Helper::response( FALSE, esc_html__( 'The account isn\'t found!', 'fs-poster' ) );
		}

		if ( $check_account[ 'user_id' ] != get_current_user_id() )
		{
			Helper::response( FALSE, esc_html__( 'Only the account owner can make it public or private!', 'fs-poster' ) );
		}

		DB::DB()->update( DB::table( 'accounts' ), [
			'is_public' => $checked
		], [
			'id'      => $id,
			'user_id' => get_current_user_id()
		] );

		Helper::response( TRUE );
	}

	public function add_instagram_account ()
	{
		$username = Request::post( 'username', '', 'string' );
		$password = Request::post( 'password', '', 'string' );
		$proxy    = Request::post( 'proxy', '', 'string' );

		if ( empty( $username ) || empty( $password ) )
		{
			Helper::response( FALSE, [ 'error_msg' => esc_html__( 'Please enter the username and password!', 'fs-poster' ) ] );
		}

		// delete old session
		DB::DB()->delete( DB::table( 'account_sessions' ), [ 'driver' => 'instagram', 'username' => $username ] );

		$ig     = new InstagramLoginPassMethod( $username, $password, $proxy );
		$result = $ig->login();

		InstagramApi::handleResponse( $result, $username, $password, $proxy );
	}

	public function add_instagram_account_cookie_method ()
	{
		$cookie_sessionid = Request::post( 'cookie_sessionid', '', 'string' );
		$proxy            = Request::post( 'proxy', '', 'string' );

		$password = '*****';

		if ( empty( $cookie_sessionid ) )
		{
			Helper::response( FALSE, [ 'error_msg' => esc_html__( 'Please enter the Instagram account username and password!', 'fs-poster' ) ] );
		}

		$cookiesArr = [
			[
				"Name"     => "sessionid",
				"Value"    => $cookie_sessionid,
				"Domain"   => ".instagram.com",
				"Path"     => "/",
				"Max-Age"  => NULL,
				"Expires"  => NULL,
				"Secure"   => TRUE,
				"Discard"  => FALSE,
				"HttpOnly" => TRUE
			]
		];

		$ig   = new InstagramCookieMethod( $cookiesArr, $proxy );
		$info = $ig->profileInfo();

		if ( ! $info )
		{
			Helper::response( FALSE, esc_html__( 'The cookie value isn\'t valid! Please get the new one.', 'fs-poster' ) );
		}

		$cookiesArr[] = [
			"Name"     => "csrftoken",
			"Value"    => $info[ 'csrf' ],
			"Domain"   => ".instagram.com",
			"Path"     => "/",
			"Max-Age"  => NULL,
			"Expires"  => NULL,
			"Secure"   => TRUE,
			"Discard"  => FALSE,
			"HttpOnly" => FALSE
		];
		$cookiesArr[] = [
			"Name"     => "mcd",
			"Value"    => 3,
			"Domain"   => ".instagram.com",
			"Path"     => "/",
			"Max-Age"  => NULL,
			"Expires"  => NULL,
			"Secure"   => TRUE,
			"Discard"  => FALSE,
			"HttpOnly" => FALSE
		];

		$username = $info[ 'username' ];

		DB::DB()->delete( DB::table( 'account_sessions' ), [ 'driver' => 'instagram', 'username' => $username ] );
		DB::DB()->insert( DB::table( 'account_sessions' ), [
			'driver'   => 'instagram',
			'username' => $username,
			'settings' => NULL,
			'cookies'  => json_encode( $cookiesArr )
		] );

		$insertedId = DB::DB()->insert_id;
		$name       = json_decode( '"' . str_replace( '"', '\\"', $info[ 'full_name' ] ) . '"' );

		$sqlData = [
			'blog_id'     => Helper::getBlogId(),
			'user_id'     => get_current_user_id(),
			'profile_id'  => $info[ 'id' ],
			'username'    => $username,
			'password'    => $password,
			'proxy'       => $proxy,
			'driver'      => 'instagram',
			'name'        => $name,
			'profile_pic' => $info[ 'profile_pic_url' ]
		];

		$checkIfExists = DB::fetch( 'accounts', [
			'blog_id'    => Helper::getBlogId(),
			'user_id'    => get_current_user_id(),
			'profile_id' => $info[ 'id' ],
			'driver'     => 'instagram'
		] );

		if ( $checkIfExists )
		{
			DB::DB()->update( DB::table( 'accounts' ), $sqlData, [ 'id' => $checkIfExists[ 'id' ] ] );
		}
		else
		{
			DB::DB()->insert( DB::table( 'accounts' ), $sqlData );
		}

		Helper::response( TRUE );
	}

	public function instagram_confirm_challenge ()
	{
		$username   = Request::post( 'username', '', 'string' );
		$password   = Request::post( 'password', '', 'string' );
		$proxy      = Request::post( 'proxy', '', 'string' );
		$code       = Request::post( 'code', '', 'string' );
		$user_id    = Request::post( 'user_id', '', 'string' );
		$nonce_code = Request::post( 'nonce_code', '', 'string' );

		if ( empty( $username ) || empty( $password ) || empty( $code ) || empty( $user_id ) || empty( $nonce_code ) )
		{
			Helper::response( FALSE, [ 'error_msg' => esc_html__( 'Please enter the code!', 'fs-poster' ) ] );
		}

		$ig     = new InstagramLoginPassMethod( $username, $password, $proxy );
		$result = $ig->finishChallenge( $code, $nonce_code, $user_id );

		InstagramApi::handleResponse( $result, $username, $password, $proxy );
	}

	public function instagram_confirm_two_factor ()
	{
		$username              = Request::post( 'username', '', 'string' );
		$password              = Request::post( 'password', '', 'string' );
		$proxy                 = Request::post( 'proxy', '', 'string' );
		$code                  = Request::post( 'code', '', 'string' );
		$two_factor_identifier = Request::post( 'two_factor_identifier', '', 'string' );

		if ( empty( $username ) || empty( $password ) || empty( $code ) || empty( $two_factor_identifier ) )
		{
			Helper::response( FALSE, [ 'error_msg' => esc_html__( 'Please enter the code!', 'fs-poster' ) ] );
		}

		$ig     = new InstagramLoginPassMethod( $username, $password, $proxy );
		$result = $ig->finishTwoFactorLogin( $two_factor_identifier, $code );

		InstagramApi::handleResponse( $result, $username, $password, $proxy );
	}

	public function add_vk_account ()
	{
		$accessToken = Request::post( 'at', '', 'string' );
		$app         = Request::post( 'app', '0', 'int' );
		$proxy       = Request::post( 'proxy', '0', 'string' );

		if ( empty( $accessToken ) )
		{
			Helper::response( FALSE, [ 'error_msg' => esc_html__( 'The access token is empty!', 'fs-poster' ) ] );
		}

		preg_match( '/access_token=([^&]+)/', $accessToken, $accessToken2 );

		if ( isset( $accessToken2[ 1 ] ) )
		{
			$accessToken = $accessToken2[ 1 ];
		}

		$get_app = DB::fetch( 'apps', [ 'driver' => 'vk', 'app_id' => $app ] );

		$result = Vk::authorizeVkUser( (int) $get_app[ 'id' ], $accessToken, $proxy );

		if ( isset( $result[ 'error' ] ) )
		{
			Helper::response( FALSE, $result[ 'error' ] );
		}

		Helper::response( TRUE );
	}

	public function search_subreddits ()
	{
		$accountId = Request::post( 'account_id', '0', 'num' );
		$search    = Request::post( 'search', '', 'string' );

		$userId = get_current_user_id();

		$account_info = DB::DB()->get_row( "SELECT * FROM " . DB::table( 'accounts' ) . " tb1 WHERE id='{$accountId}' AND driver='reddit' AND (user_id='{$userId}' OR is_public=1) ", ARRAY_A );

		if ( ! $account_info )
		{
			Helper::response( FALSE, esc_html__( 'You have not a permission for adding subreddit in this account!', 'fs-poster' ) );
		}

		$accessTokenGet = DB::fetch( 'account_access_tokens', [ 'account_id' => $accountId ] );

		$accessToken = $accessTokenGet[ 'access_token' ];
		if ( ( Date::epoch() + 30 ) > Date::epoch( $accessTokenGet[ 'expires_on' ] ) )
		{
			$accessToken = Reddit::refreshToken( $accessTokenGet );
		}

		$searchSubreddits = Reddit::cmd( 'https://oauth.reddit.com/api/search_subreddits', 'POST', $accessToken, [
			'query'                  => $search,
			'include_over_18'        => TRUE,
			'exact'                  => FALSE,
			'include_unadvertisable' => TRUE
		], $account_info[ 'proxy' ] );

		$new_arr           = [];
		$preventDublicates = [];

		foreach ( $searchSubreddits[ 'subreddits' ] as $subreddit )
		{
			$preventDublicates[ $subreddit[ 'name' ] ] = TRUE;

			$new_arr[] = [
				'text' => htmlspecialchars( $subreddit[ 'name' ] . ' ( ' . $subreddit[ 'subscriber_count' ] . ' subscribers )' ),
				'id'   => htmlspecialchars( $subreddit[ 'name' ] )
			];
		}

		// for fixing Reddit API bug
		$searchSubreddits = Reddit::cmd( 'https://oauth.reddit.com/api/search_subreddits', 'POST', $accessToken, [
			'query' => $search,
			'exact' => TRUE
		] );

		foreach ( $searchSubreddits[ 'subreddits' ] as $subreddit )
		{
			if ( isset( $preventDublicates[ $subreddit[ 'name' ] ] ) )
			{
				continue;
			}

			$new_arr[] = [
				'text' => htmlspecialchars( $subreddit[ 'name' ] . ' ( ' . $subreddit[ 'subscriber_count' ] . ' subscribers )' ),
				'id'   => htmlspecialchars( $subreddit[ 'name' ] )
			];
		}

		Helper::response( TRUE, [ 'subreddits' => $new_arr ] );
	}

	public function reddit_get_subreddt_flairs ()
	{
		$accountId = Request::post( 'account_id', '0', 'num' );
		$subreddit = Request::post( 'subreddit', '', 'string' );

		$subreddit = basename( $subreddit );

		$userId = get_current_user_id();

		$account_info = DB::DB()->get_row( "SELECT * FROM " . DB::table( 'accounts' ) . " tb1 WHERE id='{$accountId}' AND driver='reddit' AND (user_id='{$userId}' OR is_public=1) ", ARRAY_A );

		if ( ! $account_info )
		{
			Helper::response( FALSE, esc_html__( 'You have not a permission for adding subreddit in this account!', 'fs-poster' ) );
		}

		$accessTokenGet = DB::fetch( 'account_access_tokens', [ 'account_id' => $accountId ] );

		$accessToken = Reddit::accessToken( $accessTokenGet );

		$flairs = Reddit::cmd( 'https://oauth.reddit.com/r/' . $subreddit . '/api/link_flair', 'GET', $accessToken );

		$new_arr = [];
		if ( ! isset( $flairs[ 'error' ] ) )
		{
			foreach ( $flairs as $flair )
			{
				$new_arr[] = [
					'text' => htmlspecialchars( $flair[ 'text' ] ),
					'id'   => htmlspecialchars( $flair[ 'id' ] )
				];
			}
		}

		Helper::response( TRUE, [ 'flairs' => $new_arr ] );
	}

	public function reddit_subreddit_save ()
	{
		$accountId = Request::post( 'account_id', '0', 'num' );
		$subreddit = Request::post( 'subreddit', '', 'string' );
		$flairId   = Request::post( 'flair', '', 'string' );
		$flairName = Request::post( 'flair_name', '', 'string' );

		if ( ! ( ! empty( $subreddit ) && $accountId > 0 ) )
		{
			Helper::response( FALSE );
		}

		$userId = (int) get_current_user_id();

		$account_info = DB::DB()->get_row( "SELECT * FROM " . DB::table( 'accounts' ) . " WHERE id='{$accountId}' AND driver='reddit' AND (user_id='{$userId}' OR is_public=1) ", ARRAY_A );

		if ( ! $account_info )
		{
			Helper::response( FALSE, 'You have not a permission for adding subreddit in this account!' );
		}

		DB::DB()->insert( DB::table( 'account_nodes' ), [
			'blog_id'      => Helper::getBlogId(),
			'user_id'      => $userId,
			'driver'       => 'reddit',
			'account_id'   => $accountId,
			'node_type'    => 'subreddit',
			'screen_name'  => $subreddit,
			'name'         => $subreddit,
			'access_token' => $flairId,
			'category'     => $flairName
		] );

		$nodeId = DB::DB()->insert_id;

		Helper::response( TRUE, [ 'id' => $nodeId ] );
	}

	public function add_google_b_account ()
	{
		$cookie_sid  = Request::post( 'cookie_sid', '', 'string' );
		$cookie_hsid = Request::post( 'cookie_hsid', '', 'string' );
		$cookie_ssid = Request::post( 'cookie_ssid', '', 'string' );
		$proxy       = Request::post( 'proxy', '', 'string' );

		if ( empty( $cookie_sid ) || empty( $cookie_hsid ) || empty( $cookie_ssid ) )
		{
			Helper::response( FALSE, 'Please type your Cookies!' );
		}

		$google = new GoogleMyBusiness( $cookie_sid, $cookie_hsid, $cookie_ssid, $proxy );
		$data   = $google->getUserInfo();

		if ( empty( $data[ 'id' ] ) )
		{
			Helper::response( FALSE, 'The entered cookies are wrong!' );
		}

		$options = json_encode( [
			'sid'  => $cookie_sid,
			'hsid' => $cookie_hsid,
			'ssid' => $cookie_ssid,
		] );

		$sqlData = [
			'blog_id'     => Helper::getBlogId(),
			'user_id'     => get_current_user_id(),
			'profile_id'  => $data[ 'id' ],
			'username'    => isset( $data[ 'email' ] ) ? $data[ 'email' ] : '',
			'password'    => '',
			'proxy'       => $proxy,
			'driver'      => 'google_b',
			'name'        => isset( $data[ 'name' ] ) ? $data[ 'name' ] : '',
			'profile_pic' => isset( $data[ 'profile_image' ] ) ? $data[ 'profile_image' ] : '',
			'options'     => $options
		];

		$checkIfExists = DB::fetch( 'accounts', [
			'blog_id'    => Helper::getBlogId(),
			'driver'     => 'google_b',
			'user_id'    => get_current_user_id(),
			'profile_id' => $data[ 'id' ]
		] );

		if ( $checkIfExists )
		{
			DB::DB()->update( DB::table( 'accounts' ), $sqlData, [ 'id' => $checkIfExists[ 'id' ] ] );
			$accountId = $checkIfExists[ 'id' ];

			DB::DB()->delete( DB::table( 'account_nodes' ), [ 'account_id' => $accountId ] );
		}
		else
		{
			DB::DB()->insert( DB::table( 'accounts' ), $sqlData );
			$accountId = DB::DB()->insert_id;
		}

		$locations = $google->getMyLocations();
		foreach ( $locations as $location )
		{
			DB::DB()->insert( DB::table( 'account_nodes' ), [
				'blog_id'    => Helper::getBlogId(),
				'user_id'    => get_current_user_id(),
				'account_id' => $accountId,
				'node_type'  => 'location',
				'node_id'    => $location[ 'id' ],
				'name'       => $location[ 'name' ],
				'category'   => $location[ 'category' ],
				'driver'     => 'google_b'
			] );
		}

		Helper::response( TRUE );
	}

	public function add_telegram_bot ()
	{
		$bot_token = Request::post( 'bot_token', '', 'string' );
		$proxy     = Request::post( 'proxy', '', 'string' );

		if ( empty( $bot_token ) )
		{
			Helper::response( FALSE, 'Please type your Bot Token!' );
		}

		$tg   = new Telegram( $bot_token, $proxy );
		$data = $tg->getBotInfo();

		if ( empty( $data[ 'id' ] ) )
		{
			Helper::response( FALSE, 'The entered Bot Token is invalid!' );
		}

		$sqlData = [
			'blog_id'    => Helper::getBlogId(),
			'user_id'    => get_current_user_id(),
			'profile_id' => $data[ 'id' ],
			'username'   => $data[ 'username' ],
			'proxy'      => $proxy,
			'driver'     => 'telegram',
			'name'       => $data[ 'name' ],
			'options'    => $bot_token
		];

		$checkIfExists = DB::fetch( 'accounts', [
			'blog_id'    => Helper::getBlogId(),
			'driver'     => 'telegram',
			'user_id'    => get_current_user_id(),
			'profile_id' => $data[ 'id' ]
		] );

		if ( $checkIfExists )
		{
			DB::DB()->update( DB::table( 'accounts' ), $sqlData, [ 'id' => $checkIfExists[ 'id' ] ] );
		}
		else
		{
			DB::DB()->insert( DB::table( 'accounts' ), $sqlData );
		}

		Helper::response( TRUE );
	}

	public function telegram_chat_save ()
	{
		$account_id = Request::post( 'account_id', '', 'int' );
		$chat_id    = Request::post( 'chat_id', '', 'string' );

		if ( empty( $account_id ) || empty( $chat_id ) )
		{
			Helper::response( FALSE );
		}

		$account_info = DB::fetch( 'accounts', [ 'id' => $account_id ] );
		if ( ! $account_info )
		{
			Helper::response( FALSE );
		}

		$tg   = new Telegram( $account_info[ 'options' ], $account_info[ 'proxy' ] );
		$data = $tg->getChatInfo( $chat_id );

		if ( empty( $data[ 'id' ] ) )
		{
			Helper::response( FALSE, 'Chat not found!' );
		}

		DB::DB()->insert( DB::table( 'account_nodes' ), [
			'blog_id'     => Helper::getBlogId(),
			'user_id'     => get_current_user_id(),
			'account_id'  => $account_id,
			'node_type'   => 'chat',
			'node_id'     => $data[ 'id' ],
			'name'        => $data[ 'name' ],
			'screen_name' => $data[ 'username' ],
			'category'    => $data[ 'type' ],
			'driver'      => 'telegram'
		] );

		Helper::response( TRUE, [
			'id'        => DB::DB()->insert_id,
			'chat_pic'  => Pages::asset( 'Base', 'img/telegram.svg' ),
			'chat_name' => htmlspecialchars( $data[ 'name' ] ),
			'chat_link' => Helper::profileLink( [ 'driver' => 'telegram', 'username' => $data[ 'username' ] ] )
		] );
	}

	public function telegram_last_active_chats ()
	{
		$account_id = Request::post( 'account', '', 'int' );

		if ( ! ( is_numeric( $account_id ) && $account_id > 0 ) )
		{
			Helper::response( FALSE );
		}

		$list = [];

		$account_info = DB::fetch( 'accounts', [ 'id' => $account_id ] );
		if ( ! $account_info )
		{
			Helper::response( FALSE );
		}

		$tg   = new Telegram( $account_info[ 'options' ], $account_info[ 'proxy' ] );
		$data = $tg->getActiveChats();

		Helper::response( TRUE, [ 'list' => $data ] );
	}

	public function add_pinterest_account_cookie_method ()
	{
		$cookie_sess = Request::post( 'cookie_sess', '', 'string' );
		$proxy       = Request::post( 'proxy', '', 'string' );

		if ( empty( $cookie_sess ) )
		{
			Helper::response( FALSE, esc_html__( 'Please enter the Pinterest cookie!', 'fs-poster' ) );
		}

		$pinterest = new PinterestCookieApi( $cookie_sess, $proxy );
		$details   = $pinterest->getAccountData();

		$sqlData = [
			'blog_id'     => Helper::getBlogId(),
			'user_id'     => get_current_user_id(),
			'profile_id'  => $details[ 'id' ],
			'proxy'       => $proxy,
			'driver'      => 'pinterest',
			'options'     => json_encode( [ 'auth_method' => 'cookie' ] ),
			'name'        => $details[ 'full_name' ],
			'profile_pic' => $details[ 'profile_pic' ],
			'username'    => $details[ 'username' ]
		];

		$checkIfExists = DB::fetch( 'accounts', [
			'blog_id'    => Helper::getBlogId(),
			'user_id'    => get_current_user_id(),
			'profile_id' => $details[ 'id' ],
			'driver'     => 'pinterest'
		] );

		if ( $checkIfExists )
		{
			DB::DB()->update( DB::table( 'accounts' ), $sqlData, [ 'id' => $checkIfExists[ 'id' ] ] );
			$accountId = $checkIfExists[ 'id' ];
			DB::DB()->delete( DB::table( 'account_nodes' ), [ 'account_id' => $accountId ] );
			DB::DB()->delete( DB::table( 'account_sessions' ), [
				'driver'   => 'pinterest',
				'username' => $details[ 'username' ]
			] );
		}
		else
		{
			DB::DB()->insert( DB::table( 'accounts' ), $sqlData );
			$accountId = DB::DB()->insert_id;
		}

		DB::DB()->insert( DB::table( 'account_sessions' ), [
			'driver'   => 'pinterest',
			'username' => $details[ 'username' ],
			'cookies'  => $cookie_sess
		] );

		foreach ( $pinterest->getBoards( $details[ 'username' ] ) as $board )
		{
			DB::DB()->insert( DB::table( 'account_nodes' ), [
				'blog_id'     => Helper::getBlogId(),
				'user_id'     => get_current_user_id(),
				'account_id'  => $accountId,
				'driver'      => 'pinterest',
				'node_type'   => 'board',
				'node_id'     => $board[ 'id' ],
				'name'        => $board[ 'name' ],
				'cover'       => $board[ 'cover' ],
				'screen_name' => $board[ 'url' ],
			] );
		}

		Helper::response( TRUE );
	}

	public function add_wordpress_site ()
	{
		$site_url = Request::post( 'site_url', '', 'string' );
		$username = Request::post( 'username', '', 'string' );
		$password = Request::post( 'password', '', 'string' );
		$proxy    = Request::post( 'proxy', '', 'string' );

		if ( empty( $site_url ) || empty( $username ) || empty( $password ) )
		{
			Helper::response( FALSE, 'Please fill all inputs correctly!' );
		}

		if ( ! preg_match( '/^http(s|):\/\//i', $site_url ) )
		{
			Helper::response( FALSE, 'The URL must start with http(s)!' );
		}

		$wordpress = new Wordpress( $site_url, $username, $password, $proxy );
		$check     = $wordpress->checkUser();

		if ( $check !== TRUE )
		{
			Helper::response( FALSE, $check );
		}

		$sqlData = [
			'blog_id'  => Helper::getBlogId(),
			'user_id'  => get_current_user_id(),
			'username' => $username,
			'password' => $password,
			'proxy'    => $proxy,
			'driver'   => 'wordpress',
			'name'     => $site_url,
			'options'  => $site_url
		];

		$checkIfExists = DB::fetch( 'accounts', [
			'driver'   => 'wordpress',
			'user_id'  => get_current_user_id(),
			'options'  => $site_url,
			'username' => $username
		] );

		if ( $checkIfExists )
		{
			DB::DB()->update( DB::table( 'accounts' ), $sqlData, [ 'id' => $checkIfExists[ 'id' ] ] );
			$accountId = $checkIfExists[ 'id' ];
		}
		else
		{
			DB::DB()->insert( DB::table( 'accounts' ), $sqlData );
			$accountId = DB::DB()->insert_id;
		}

		Helper::response( TRUE );
	}

	public function settings_node_activity_change ()
	{
		$id          = Request::post( 'id', '0', 'num' );
		$checked     = Request::post( 'checked', -1, 'num', [ '0', '1' ] );
		$filter_type = Request::post( 'filter_type', '', 'string', [ 'in', 'ex' ] );
		$categories  = Request::post( 'categories', [], 'array' );

		if ( ! ( $id > 0 && $checked > -1 ) )
		{
			Helper::response( FALSE );
		}

		$categories_arr = [];
		foreach ( $categories as $categId )
		{
			if ( is_numeric( $categId ) && $categId > 0 )
			{
				$categories_arr[] = (int) $categId;
			}
		}
		$categories_arr = implode( ',', $categories_arr );

		if ( ( ! empty( $categories_arr ) && empty( $filter_type ) ) || ( empty( $categories_arr ) && ! empty( $filter_type ) ) )
		{
			Helper::response( FALSE, 'Please select categories and filter type!' );
		}

		$categories_arr = empty( $categories_arr ) ? NULL : $categories_arr;
		$filter_type    = empty( $filter_type ) ? 'no' : $filter_type;

		$check_account = DB::DB()->get_row( "SELECT * FROM " . DB::table( 'account_nodes' ) . " WHERE id='" . $id . "'", ARRAY_A );

		if ( ! $check_account )
		{
			Helper::response( FALSE, 'Community not found!' );
		}

		if ( $check_account[ 'user_id' ] != get_current_user_id() && $check_account[ 'is_public' ] != 1 )
		{
			Helper::response( FALSE, 'Community not found or you do not have a permission for this community!' );
		}

		if ( $checked )
		{
			$checkIfIsActive = DB::fetch( 'account_node_status', [
				'node_id' => $id,
				'user_id' => get_current_user_id()
			] );

			if ( ! $checkIfIsActive )
			{
				DB::DB()->insert( DB::table( 'account_node_status' ), [
					'node_id'     => $id,
					'user_id'     => get_current_user_id(),
					'filter_type' => $filter_type,
					'categories'  => $categories_arr
				] );
			}
			else
			{
				DB::DB()->update( DB::table( 'account_node_status' ), [
					'filter_type' => $filter_type,
					'categories'  => $categories_arr
				], [ 'id' => $checkIfIsActive[ 'id' ] ] );
			}
		}
		else
		{
			DB::DB()->delete( DB::table( 'account_node_status' ), [
				'node_id' => $id,
				'user_id' => get_current_user_id()
			] );
		}

		Helper::response( TRUE );
	}

	public function settings_node_make_public ()
	{
		$id = Request::post( 'id', 0, 'num' );

		if ( ! $id > 0 )
		{
			Helper::response( FALSE );
		}

		$getNodeInf = DB::fetch( 'account_nodes', $id );

		if ( ! $getNodeInf )
		{
			Helper::response( FALSE, 'Community not found!' );
		}

		if ( $getNodeInf[ 'user_id' ] != get_current_user_id() )
		{
			Helper::response( FALSE, 'This is not one of you added comunity. Therefore you do not have a permission for make public/private this community.' );
		}

		$newStatus = (int) ( ! $getNodeInf[ 'is_public' ] );

		DB::DB()->update( DB::table( 'account_nodes' ), [ 'is_public' => $newStatus ], [ 'id' => $id ] );

		Helper::response( TRUE );
	}

	public function settings_node_delete ()
	{
		$id = Request::post( 'id', 0, 'num' );

		if ( ! $id > 0 )
		{
			Helper::response( FALSE );
		}

		$check_account = DB::DB()->get_row( "SELECT * FROM " . DB::table( 'account_nodes' ) . " WHERE id='" . $id . "'", ARRAY_A );

		if ( ! $check_account )
		{
			Helper::response( FALSE, 'Community not found!' );
		}

		if ( $check_account[ 'user_id' ] != get_current_user_id() )
		{
			Helper::response( FALSE, 'You do not have a permission for deleting this community!' );
		}

		DB::DB()->delete( DB::table( 'account_nodes' ), [ 'id' => $id ] );
		DB::DB()->delete( DB::table( 'account_node_status' ), [ 'node_id' => $id ] );

		Helper::response( TRUE );
	}
}