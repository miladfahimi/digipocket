<?php

namespace FSPoster\App\Providers;

use FSPoster\App\Libraries\fb\Facebook;
use FSPoster\App\Libraries\medium\Medium;
use FSPoster\App\Libraries\reddit\Reddit;
use FSPoster\App\Libraries\tumblr\Tumblr;
use FSPoster\App\Libraries\twitter\Twitter;
use FSPoster\App\Libraries\ok\OdnoKlassniki;
use FSPoster\App\Libraries\linkedin\Linkedin;
use FSPoster\App\Libraries\pinterest\Pinterest;

class FrontEnd
{
	public function __construct ()
	{
		if ( ! Helper::pluginDisabled() )
		{
			add_action( 'wp', [ $this, 'boot' ] );
		}
	}

	public function boot ()
	{
		$this->checkVisits();

		$this->fetchAccessToken();

		$this->fbRedirect();
		$this->twitterRedirect();
		$this->linkedinRedirect();
		$this->pinterestRedirect();
		$this->redditRedirect();
		$this->tumblrRedirect();
		$this->okRedirect();
		$this->mediumRedirect();

		$this->standartFSApp();
	}

	public function checkVisits ()
	{
		if ( is_single() || is_page() )
		{
			global $post;

			$feed_id = Request::get( 'feed_id', '0', 'int' );

			if ( isset( $post->ID ) && $feed_id > 0 )
			{
				$post_id = $post->ID;

				DB::DB()->query( DB::DB()->prepare( "UPDATE " . DB::table( 'feeds' ) . " SET visit_count=visit_count+1 WHERE id=%d AND post_id=%d", [
					$feed_id,
					$post_id
				] ) );
			}
		}
	}

	public function fetchAccessToken ()
	{
		if ( Request::get( 'fb_callback', '0', 'int' ) === 1 )
		{
			Facebook::getAccessToken();
		}
		else if ( Request::get( 'twitter_callback', '0', 'int' ) === 1 )
		{
			Twitter::getAccessToken();
		}
		else if ( Request::get( 'linkedin_callback', '0', 'int' ) === 1 )
		{
			Linkedin::getAccessToken();
		}
		else if ( Request::get( 'pinterest_callback', '0', 'int' ) === 1 )
		{
			Pinterest::getAccessToken();
		}
		else if ( Request::get( 'reddit_callback', '0', 'int' ) === 1 )
		{
			Reddit::getAccessToken();
		}
		else if ( Request::get( 'tumblr_callback', '0', 'int' ) === 1 )
		{
			Tumblr::getAccessToken();
		}
		else if ( Request::get( 'ok_callback', '0', 'int' ) === 1 )
		{
			Odnoklassniki::getAccessToken();
		}
		else if ( Request::get( 'medium_callback', '0', 'int' ) === 1 )
		{
			Medium::getAccessToken();
		}

	}

	public function fbRedirect ()
	{
		$appId = Request::get( 'fb_app_redirect', '0', 'int' );

		if ( $appId > 0 )
		{
			header( 'Location: ' . Facebook::getLoginURL( $appId ) );
			exit();
		}

	}

	public function twitterRedirect ()
	{
		$appId = Request::get( 'twitter_app_redirect', '0', 'int' );

		if ( $appId > 0 )
		{
			header( 'Location:' . Twitter::getLoginURL( $appId ) );
			exit();
		}
	}

	public function linkedinRedirect ()
	{
		$appId = Request::get( 'linkedin_app_redirect', '0', 'int' );

		if ( $appId > 0 )
		{
			header( 'Location: ' . Linkedin::getLoginURL( $appId ) );
			exit();
		}
	}

	public function pinterestRedirect ()
	{
		$appId = Request::get( 'pinterest_app_redirect', '0', 'int' );

		if ( $appId > 0 )
		{
			header( 'Location: ' . Pinterest::getLoginURL( $appId ) );
			exit();
		}
	}

	public function redditRedirect ()
	{
		$appId = Request::get( 'reddit_app_redirect', '0', 'int' );

		if ( $appId > 0 )
		{
			header( 'Location: ' . Reddit::getLoginURL( $appId ) );
			exit();
		}
	}

	public function tumblrRedirect ()
	{
		$appId = Request::get( 'tumblr_app_redirect', '0', 'int' );

		if ( $appId > 0 )
		{
			header( 'Location: ' . Tumblr::getLoginURL( $appId ) );
			exit();
		}
	}

	public function okRedirect ()
	{
		$appId = Request::get( 'ok_app_redirect', '0', 'int' );

		if ( $appId > 0 )
		{
			header( 'Location: ' . Odnoklassniki::getLoginURL( $appId ) );
			exit();
		}
	}

	public function mediumRedirect ()
	{
		$appId = Request::get( 'medium_app_redirect', '0', 'int' );

		if ( $appId > 0 )
		{
			header( 'Location: ' . Medium::getLoginURL( $appId ) );
			exit();
		}
	}

	public function standartFSApp ()
	{
		$supportedFSApps = [ 'fb', 'twitter', 'linkedin', 'pinterest', 'reddit', 'tumblr', 'ok', 'medium' ];

		$sn       = Request::get( 'sn', '', 'string', $supportedFSApps );
		$callback = Request::get( 'fs_app_redirect', '0', 'num', [ '1' ] );
		$proxy    = Request::get( 'proxy', '', 'string' );

		if ( ! empty( $proxy ) )
		{
			$proxy = strrev( $proxy ); // server: olleh => $proxy: hello
		}

		if ( ! $callback || empty( $sn ) )
		{
			return;
		}

		$appInf = DB::fetch( 'apps', [
			'driver'      => $sn,
			'is_standart' => 1
		] );

		if ( $sn === 'fb' )
		{
			$access_token = Request::get( 'access_token', '', 'string' );

			if ( empty( $access_token ) )
			{
				return;
			}

			Facebook::authorize( $appInf[ 'id' ], $access_token, $proxy );
		}
		else
		{
			if ( $sn === 'twitter' )
			{
				$oauth_token        = Request::get( 'oauth_token', '', 'string' );
				$oauth_token_secret = Request::get( 'oauth_token_secret', '', 'string' );

				if ( empty( $oauth_token ) || empty( $oauth_token_secret ) )
				{
					return;
				}

				Twitter::authorize( $appInf, $oauth_token, $oauth_token_secret, $proxy );
			}
			else
			{
				if ( $sn === 'linkedin' )
				{
					$access_token = Request::get( 'access_token', '', 'string' );
					$expire_in    = Request::get( 'expire_in', '', 'string' );

					if ( empty( $access_token ) || empty( $expire_in ) )
					{
						return;
					}

					Linkedin::authorize( $appInf[ 'id' ], $access_token, $expire_in, $proxy );
				}
				else
				{
					if ( $sn === 'pinterest' )
					{
						$access_token = Request::get( 'access_token', '', 'string' );

						if ( empty( $access_token ) )
						{
							return;
						}

						Pinterest::authorize( $appInf[ 'id' ], $access_token, $proxy );
					}
					else
					{
						if ( $sn === 'reddit' )
						{
							$access_token = Request::get( 'access_token', '', 'string' );
							$refreshToken = Request::get( 'refresh_token', '', 'string' );
							$expiresIn    = Request::get( 'expires_in', '', 'string' );

							if ( empty( $access_token ) || empty( $refreshToken ) || empty( $expiresIn ) )
							{
								return;
							}

							Reddit::authorize( $appInf[ 'id' ], $access_token, $refreshToken, $expiresIn, $proxy );
						}
						else
						{
							if ( $sn === 'tumblr' )
							{
								$access_token        = Request::get( 'access_token', '', 'string' );
								$access_token_secret = Request::get( 'access_token_secret', '', 'string' );

								if ( empty( $access_token ) || empty( $access_token_secret ) )
								{
									return;
								}

								date_default_timezone_set( 'Asia/Baku' );

								Tumblr::authorize( $appInf[ 'id' ], $appInf[ 'app_key' ], $appInf[ 'app_secret' ], $access_token, $access_token_secret, $proxy );
							}
							else
							{
								if ( $sn === 'ok' )
								{
									$access_token = Request::get( 'access_token', '', 'string' );
									$refreshToken = Request::get( 'refresh_token', '', 'string' );
									$expiresIn    = Request::get( 'expires_in', '', 'string' );

									if ( empty( $access_token ) || empty( $refreshToken ) || empty( $expiresIn ) )
									{
										return;
									}

									OdnoKlassniki::authorize( $appInf[ 'id' ], $appInf[ 'app_key' ], $appInf[ 'app_secret' ], $access_token, $refreshToken, $expiresIn, $proxy );
								}
								else
								{
									if ( $sn === 'medium' )
									{
										$access_token = Request::get( 'access_token', '', 'string' );
										$refreshToken = Request::get( 'refresh_token', '', 'string' );
										$expiresIn    = Request::get( 'expires_in', '', 'string' );

										if ( empty( $access_token ) || empty( $refreshToken ) || empty( $expiresIn ) )
										{
											return;
										}

										Medium::authorizeMediumUser( $appInf[ 'id' ], $access_token, $refreshToken, $expiresIn, $proxy );
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
