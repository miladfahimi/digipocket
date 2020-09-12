<?php

namespace FSPoster\App\Providers;

use FSPoster\App\Libraries\vk\Vk;
use FSPoster\App\Libraries\fb\Facebook;
use FSPoster\App\Libraries\medium\Medium;
use FSPoster\App\Libraries\reddit\Reddit;
use FSPoster\App\Libraries\tumblr\Tumblr;
use FSPoster\App\Libraries\twitter\Twitter;
use FSPoster\App\Libraries\ok\OdnoKlassniki;
use FSPoster\App\Libraries\linkedin\Linkedin;
use FSPoster\App\Libraries\telegram\Telegram;
use FSPoster\App\Libraries\pinterest\Pinterest;
use FSPoster\App\Libraries\wordpress\Wordpress;
use FSPoster\App\Libraries\fb\FacebookCookieApi;
use FSPoster\App\Libraries\instagram\InstagramApi;
use FSPoster\App\Libraries\google\GoogleMyBusiness;
use FSPoster\App\Libraries\pinterest\PinterestCookieApi;

class AccountService
{
	public static function checkAccounts ()
	{
		$all_accountsSQL = DB::DB()->prepare( 'SELECT * FROM ' . DB::table( 'accounts' ) . ' WHERE ((id IN (SELECT account_id FROM ' . DB::table( 'account_status' ) . ')) OR (id IN (SELECT account_id FROM ' . DB::table( 'account_nodes' ) . ' WHERE id IN (SELECT node_id FROM ' . DB::table( 'account_node_status' ) . ')))) AND (`last_check_time` is NULL OR `last_check_time` < %s)', [ Date::dateTimeSQL( '-1 day' ) ] );
		$all_accounts    = DB::DB()->get_results( $all_accountsSQL, ARRAY_A );

		foreach ( $all_accounts as $account )
		{
			$nInf = Helper::getAccessToken( 'account', $account[ 'id' ] );

			$appId             = $nInf[ 'app_id' ];
			$driver            = $nInf[ 'driver' ];
			$accessToken       = $nInf[ 'access_token' ];
			$accessTokenSecret = $nInf[ 'access_token_secret' ];
			$proxy             = $nInf[ 'info' ][ 'proxy' ];
			$options           = $nInf[ 'options' ];
			$accountId         = $nInf[ 'account_id' ];

			if ( $driver === 'fb' )
			{
				if ( empty( $options ) ) // app method
				{
					$result = Facebook::checkAccount( $accessToken, $proxy );
				}
				else // cookie method
				{
					$fbDriver = new FacebookCookieApi( $accountId, $options, $proxy );
					$result   = $fbDriver->checkAccount();
				}
			}
			else if ( $driver === 'twitter' )
			{
				$result = Twitter::checkAccount( $appId, $accessToken, $accessTokenSecret, $proxy );
			}
			else if ( $driver === 'instagram' )
			{
				$result = InstagramApi::checkAccount( $nInf[ 'info' ] );
			}
			else if ( $driver === 'linkedin' )
			{
				$result = Linkedin::checkAccount( $accessToken, $proxy );
			}
			else if ( $driver === 'vk' )
			{
				$result = Vk::checkAccount( $accessToken, $proxy );
			}
			else if ( $driver === 'pinterest' )
			{
				if ( empty( $options ) ) // app method
				{
					$result = Pinterest::checkAccount( $accessToken, $proxy );
				}
				else // cookie method
				{
					$getCookie = DB::fetch( 'account_sessions', [
						'driver'   => 'pinterest',
						'username' => $nInf[ 'username' ]
					] );

					$pinterest = new PinterestCookieApi( $getCookie[ 'cookies' ], $proxy );
					$result    = $pinterest->checkAccount();
				}
			}
			else if ( $driver === 'reddit' )
			{
				$result = Reddit::checkAccount( $accessToken, $proxy );
			}
			else if ( $driver === 'tumblr' )
			{
				$result = Tumblr::checkAccount( $accessToken, $accessTokenSecret, $appId, $proxy );
			}
			else if ( $driver === 'ok' )
			{
				$appInf = DB::fetch( 'apps', [ 'id' => $appId ] );

				$result = OdnoKlassniki::checkAccount( $accessToken, $appInf[ 'app_key' ], $appInf[ 'app_secret' ], $proxy );
			}
			else if ( $driver === 'google_b' )
			{
				$options     = json_decode( $options, TRUE );
				$cookie_sid  = isset( $options[ 'sid' ] ) ? $options[ 'sid' ] : '';
				$cookie_hsid = isset( $options[ 'hsid' ] ) ? $options[ 'hsid' ] : '';
				$cookie_ssid = isset( $options[ 'ssid' ] ) ? $options[ 'ssid' ] : '';

				$google_b = new GoogleMyBusiness( $cookie_sid, $cookie_hsid, $cookie_ssid, $proxy );
				$result   = $google_b->checkAccount();
			}
			else if ( $driver === 'telegram' )
			{
				$telegram = new Telegram( $options, $proxy );
				$result   = $telegram->checkAccount();
			}
			else if ( $driver === 'medium' )
			{
				$result = Medium::checkAccount( $accessToken, $proxy );
			}
			else if ( $driver === 'wordpress' )
			{
				$wordpress = new Wordpress( $options, $nInf[ 'username' ], $nInf[ 'password' ], $proxy );
				$result    = $wordpress->checkAccount();
			}

			if ( $result[ 'error' ] )
			{
				$error_msg = isset( $result[ 'error_msg' ] ) ? substr( $result[ 'error_msg' ], 0, 300 ) : esc_html__( 'In order to solve the failed account issue, please remove and re-add the account.', 'fs-poster' );

				DB::DB()->update( DB::table( 'accounts' ), [
					'status'          => 'error',
					'error_msg'       => $error_msg,
					'last_check_time' => Date::dateTimeSQL()
				], [ 'id' => $account[ 'id' ] ] );

				if ( Helper::getOption( 'check_accounts_disable', 0 ) )
				{
					DB::DB()->delete( DB::table( 'account_status' ), [
						'account_id' => $account[ 'id' ]
					] );

					DB::DB()->query( DB::DB()->prepare( 'DELETE FROM ' . DB::table( 'account_node_status' ) . ' WHERE node_id IN (SELECT id FROM ' . DB::table( 'account_nodes' ) . ' WHERE account_id = %d)', [ $account[ 'id' ] ] ) );
				}
			}
			else
			{
				DB::DB()->update( DB::table( 'accounts' ), [
					'status'          => NULL,
					'error_msg'       => NULL,
					'last_check_time' => Date::dateTimeSQL()
				], [
					'id' => $account[ 'id' ]
				] );
			}
		}
	}
}