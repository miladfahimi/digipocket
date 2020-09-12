<?php

namespace FSPoster\App\Pages\Apps\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Libraries\vk\Vk;
use FSPoster\App\Providers\Request;
use FSPoster\App\Libraries\fb\Facebook;
use FSPoster\App\Libraries\reddit\Reddit;
use FSPoster\App\Libraries\tumblr\Tumblr;
use FSPoster\App\Libraries\medium\Medium;
use FSPoster\App\Libraries\ok\OdnoKlassniki;
use FSPoster\App\Libraries\linkedin\Linkedin;
use FSPoster\App\Libraries\pinterest\Pinterest;

class Action
{
	public function get_apps ()
	{
		$appsCount = DB::DB()->get_results( "SELECT driver, COUNT(0) AS _count FROM " . DB::table( 'apps' ) . " GROUP BY driver", ARRAY_A );
		$appCounts = [
			'total'     => 0,
			'fb'        => [ 0, [ 'app_id', 'app_key' ] ],
			'twitter'   => [ 0, [ 'app_key', 'app_secret' ] ],
			'linkedin'  => [ 0, [ 'app_id', 'app_secret' ] ],
			'vk'        => [ 0, [ 'app_id', 'app_secret' ] ],
			'pinterest' => [ 0, [ 'app_id', 'app_secret' ] ],
			'reddit'    => [ 0, [ 'app_id', 'app_secret' ] ],
			'tumblr'    => [ 0, [ 'app_key', 'app_secret' ] ],
			'ok'        => [ 0, [ 'app_id', 'app_key', 'app_secret' ] ],
			'medium'    => [ 0, [ 'app_id', 'app_secret' ] ]
		];

		foreach ( $appsCount as $aInf )
		{
			if ( isset( $appCounts[ $aInf[ 'driver' ] ] ) )
			{
				$appCounts[ $aInf[ 'driver' ] ][ 0 ] = $aInf[ '_count' ];
				$appCounts[ 'total' ]                += $aInf[ '_count' ];
			}
		}

		$active_tab = Request::get( 'tab', NULL, 'string' );

		if ( ! is_null( $active_tab ) && ! array_key_exists( $active_tab, $appCounts ) )
		{
			$active_tab = NULL;
		}

		if ( ! is_null( $active_tab ) )
		{
			$appList = DB::fetchAll( 'apps', [ 'driver' => $active_tab ] );

			$callbackUrl = '-';

			if ( $active_tab === 'fb' )
			{
				$callbackUrl = Facebook::callbackURL();
			}
			else if ( $active_tab === 'twitter' )
			{
				$callbackUrl = site_url() . '/';
			}
			else if ( $active_tab === 'linkedin' )
			{
				$callbackUrl = Linkedin::callbackURL();
			}
			else if ( $active_tab === 'vk' )
			{
				$callbackUrl = Vk::callbackURL();
			}
			else if ( $active_tab === 'pinterest' )
			{
				$callbackUrl = Pinterest::callbackURL();
			}
			else if ( $active_tab === 'reddit' )
			{
				$callbackUrl = Reddit::callbackURL();
			}
			else if ( $active_tab === 'tumblr' )
			{
				$callbackUrl = Tumblr::callbackURL();
			}
			else if ( $active_tab === 'ok' )
			{
				$callbackUrl = OdnoKlassniki::callbackURL();
			}
			else if ( $active_tab === 'medium' )
			{
				$callbackUrl = Medium::callbackURL();
			}
		}

		return [
			'appCounts'   => $appCounts,
			'callbackUrl' => isset( $callbackUrl ) ? $callbackUrl : NULL,
			'appList'     => isset( $appList ) ? $appList : NULL,
			'active_tab'  => $active_tab
		];
	}
}