<?php

namespace FSPoster\App\Pages\Logs\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Date;
use FSPoster\App\Libraries\vk\Vk;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;
use FSPoster\App\Libraries\fb\Facebook;
use FSPoster\App\Libraries\reddit\Reddit;
use FSPoster\App\Libraries\twitter\Twitter;
use FSPoster\App\Libraries\ok\OdnoKlassniki;
use FSPoster\App\Libraries\linkedin\Linkedin;
use FSPoster\App\Libraries\pinterest\Pinterest;
use FSPoster\App\Libraries\fb\FacebookCookieApi;
use FSPoster\App\Libraries\instagram\InstagramApi;

trait Ajax
{
	public function report1_data ()
	{
		$type    = Request::post( 'type', '', 'string' );
		$user_id = ( int ) get_current_user_id();

		if ( ! in_array( $type, [
			'dayly',
			'monthly',
			'yearly'
		] ) )
		{
			exit();
		}

		$query = [
			'dayly'   => "SELECT CAST(send_time AS DATE) AS date , COUNT(0) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE tb1.blog_id='" . Helper::getBlogId() . "' AND ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.blog_id='" . Helper::getBlogId() . "' AND tb2.id=tb1.node_id AND (tb2.user_id='$user_id' OR tb2.is_public=1))>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id AND (tb2.user_id='$user_id')>0 OR tb2.is_public=1)) ) AND is_sended=1 GROUP BY CAST(send_time AS DATE)",
			'monthly' => "SELECT CONCAT(YEAR(send_time), '-', MONTH(send_time) , '-01') AS date , COUNT(0) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE tb1.blog_id='" . Helper::getBlogId() . "' AND ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.blog_id='" . Helper::getBlogId() . "' AND tb2.id=tb1.node_id AND (tb2.user_id='$user_id' OR tb2.is_public=1))>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id AND (tb2.user_id='$user_id')>0 OR tb2.is_public=1)) ) AND is_sended=1 AND send_time > ADDDATE(now(),INTERVAL -1 YEAR) GROUP BY YEAR(send_time), MONTH(send_time)",
			'yearly'  => "SELECT CONCAT(YEAR(send_time), '-01-01') AS date , COUNT(0) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE tb1.blog_id='" . Helper::getBlogId() . "' AND ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.blog_id='" . Helper::getBlogId() . "' AND tb2.id=tb1.node_id AND (tb2.user_id='$user_id' OR tb2.is_public=1))>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id AND (tb2.user_id='$user_id')>0 OR tb2.is_public=1)) ) AND is_sended=1 GROUP BY YEAR(send_time)"
		];

		$dateFormat = [
			'dayly'   => 'Y-m-d',
			'monthly' => 'Y M',
			'yearly'  => 'Y',
		];

		$dataSQL = DB::DB()->get_results( $query[ $type ], ARRAY_A );

		$labels = [];
		$datas  = [];
		foreach ( $dataSQL as $dInf )
		{
			$datas[]  = $dInf[ 'c' ];
			$labels[] = Date::format( $dateFormat[ $type ], $dInf[ 'date' ] );
		}

		Helper::response( TRUE, [
			'data'   => $datas,
			'labels' => $labels
		] );
	}

	public function report2_data ()
	{
		$type    = Request::post( 'type', '', 'string' );
		$user_id = ( int ) get_current_user_id();

		if ( ! in_array( $type, [
			'dayly',
			'monthly',
			'yearly'
		] ) )
		{
			exit();
		}

		$query = [
			'dayly'   => "SELECT CAST(send_time AS DATE) AS date , SUM(visit_count) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE tb1.blog_id='" . Helper::getBlogId() . "' AND ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.blog_id='" . Helper::getBlogId() . "' AND tb2.id=tb1.node_id AND (tb2.user_id='$user_id' OR tb2.is_public=1))>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id AND (tb2.user_id='$user_id')>0 OR tb2.is_public=1)) ) AND is_sended=1 GROUP BY CAST(send_time AS DATE)",
			'monthly' => "SELECT CONCAT(YEAR(send_time), '-', MONTH(send_time) , '-01') AS date , SUM(visit_count) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE tb1.blog_id='" . Helper::getBlogId() . "' AND ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.blog_id='" . Helper::getBlogId() . "' AND tb2.id=tb1.node_id AND (tb2.user_id='$user_id' OR tb2.is_public=1))>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id AND (tb2.user_id='$user_id')>0 OR tb2.is_public=1)) ) AND send_time > ADDDATE(now(),INTERVAL -1 YEAR) AND is_sended=1 GROUP BY YEAR(send_time), MONTH(send_time)",
			'yearly'  => "SELECT CONCAT(YEAR(send_time), '-01-01') AS date , SUM(visit_count) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE tb1.blog_id='" . Helper::getBlogId() . "' AND ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.blog_id='" . Helper::getBlogId() . "' AND tb2.id=tb1.node_id AND (tb2.user_id='$user_id' OR tb2.is_public=1))>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id AND (tb2.user_id='$user_id')>0 OR tb2.is_public=1)) ) AND is_sended=1 GROUP BY YEAR(send_time)"
		];

		$dateFormat = [
			'dayly'   => 'Y-m-d',
			'monthly' => 'Y M',
			'yearly'  => 'Y',
		];

		$dataSQL = DB::DB()->get_results( $query[ $type ], ARRAY_A );

		$labels = [];
		$datas  = [];
		foreach ( $dataSQL as $dInf )
		{
			$datas[]  = $dInf[ 'c' ];
			$labels[] = Date::format( $dateFormat[ $type ], $dInf[ 'date' ] );
		}

		Helper::response( TRUE, [
			'data'   => $datas,
			'labels' => $labels
		] );
	}

	public function report3_data ()
	{
		$page           = Request::post( 'page', '1', 'num' );
		$schedule_id    = Request::post( 'schedule_id', '0', 'num' );
		$rows_count     = Request::post( 'rows_count', '4', 'int', [ '4', '8', '15' ] );
		$filter_results = Request::post( 'filter_results', 'all', 'string', [ 'all', 'error', 'ok' ] );

		if ( ! ( $page > 0 ) )
		{
			Helper::response( FALSE );
		}

		$query_add = '';

		if ( $schedule_id > 0 )
		{
			$query_add = ' AND schedule_id="' . (int) $schedule_id . '"';
		}

		if ( $filter_results === 'error' || $filter_results === 'ok' )
		{
			$query_add .= ' AND status = "' . $filter_results . '"';
		}

		$userId   = (int) get_current_user_id();
		$allCount = DB::DB()->get_row( "SELECT COUNT(0) AS c FROM " . DB::table( 'feeds' ) . ' tb1 WHERE blog_id=\'' . Helper::getBlogId() . '\' AND is_sended=1 AND ( (node_type=\'account\' AND (SELECT COUNT(0) FROM ' . DB::table( 'accounts' ) . ' tb2 WHERE tb2.id=tb1.node_id AND (tb2.user_id=\'' . $userId . '\' OR tb2.is_public=1))>0) OR (node_type<>\'account\' AND (SELECT COUNT(0) FROM ' . DB::table( 'account_nodes' ) . ' tb2 WHERE tb2.id=tb1.node_id AND (tb2.user_id=\'' . $userId . '\')>0 OR tb2.is_public=1)) ) ' . $query_add, ARRAY_A );
		$pages    = ceil( $allCount[ 'c' ] / $rows_count );

		Helper::setOption( 'logs_rows_count', $rows_count );

		$offset     = ( $page - 1 ) * $rows_count;
		$getData    = DB::DB()->get_results( "SELECT * FROM " . DB::table( 'feeds' ) . ' tb1 WHERE blog_id=\'' . Helper::getBlogId() . '\' AND is_sended=1 AND ( (node_type=\'account\' AND (SELECT COUNT(0) FROM ' . DB::table( 'accounts' ) . ' tb2 WHERE tb2.id=tb1.node_id AND (tb2.user_id=\'' . $userId . '\' OR tb2.is_public=1))>0) OR (node_type<>\'account\' AND (SELECT COUNT(0) FROM ' . DB::table( 'account_nodes' ) . ' tb2 WHERE tb2.id=tb1.node_id AND (tb2.user_id=\'' . $userId . '\')>0 OR tb2.is_public=1)) ) ' . $query_add . " ORDER BY send_time DESC LIMIT $offset , $rows_count", ARRAY_A );
		$resultData = [];

		foreach ( $getData as $feedInf )
		{
			$postInf        = get_post( $feedInf[ 'post_id' ] );
			$node_infoTable = $feedInf[ 'node_type' ] === 'account' ? 'accounts' : 'account_nodes';
			$node_info      = DB::fetch( $node_infoTable, $feedInf[ 'node_id' ] );

			if ( $node_info && $feedInf[ 'node_type' ] === 'account' )
			{
				$node_info[ 'node_type' ] = 'account';
			}

			$insights = [
				'like'     => 0,
				'details'  => '',
				'comments' => 0,
				'shares'   => 0
			];

			if ( ! empty( $feedInf[ 'driver_post_id' ] ) )
			{
				$nInf = Helper::getAccessToken( $feedInf[ 'node_type' ], $feedInf[ 'node_id' ] );

				$proxy       = $nInf[ 'info' ][ 'proxy' ];
				$accessToken = $nInf[ 'access_token' ];
				$options     = $nInf[ 'options' ];
				$accountId   = $nInf[ 'account_id' ];
				$appId       = $nInf[ 'app_id' ];

				$appInf = DB::fetch( 'apps', $appId );

				if ( $feedInf[ 'driver' ] === 'fb' )
				{
					if ( empty( $options ) )
					{
						$insights = Facebook::getStats( $feedInf[ 'driver_post_id' ], $accessToken, $proxy );
					}
					else
					{
						$fbDriver = new FacebookCookieApi( $accountId, $options, $proxy );
						$insights = $fbDriver->getStats( $feedInf[ 'driver_post_id' ] );
					}
				}
				else if ( $feedInf[ 'driver' ] === 'vk' )
				{
					$insights = Vk::getStats( $feedInf[ 'driver_post_id' ], $accessToken, $proxy );
				}
				else if ( $feedInf[ 'driver' ] === 'twitter' )
				{
					$insights = Twitter::getStats( $feedInf[ 'driver_post_id' ], $accessToken, $nInf[ 'access_token_secret' ], $appId, $proxy );
				}
				else if ( $feedInf[ 'driver' ] === 'instagram' )
				{
					$insights = InstagramApi::getStats( $feedInf[ 'driver_post_id2' ], $feedInf[ 'driver_post_id' ], $nInf[ 'info' ] );
				}
				else if ( $feedInf[ 'driver' ] === 'linkedin' )
				{
					$insights = Linkedin::getStats( NULL, $proxy );
				}
				else if ( $feedInf[ 'driver' ] === 'pinterest' )
				{
					$insights = Pinterest::getStats( $feedInf[ 'driver_post_id' ], $accessToken, $proxy );
				}
				else if ( $feedInf[ 'driver' ] === 'reddit' )
				{
					$insights = Reddit::getStats( $feedInf[ 'driver_post_id' ], $accessToken, $proxy );
				}
				else if ( $feedInf[ 'driver' ] === 'ok' )
				{
					$post_id2 = explode( '/', $feedInf[ 'driver_post_id' ] );
					$post_id2 = end( $post_id2 );
					$insights = OdnoKlassniki::getStats( $post_id2, $accessToken, $appInf[ 'app_key' ], $appInf[ 'app_secret' ], $proxy );
				}
			}

			if ( $feedInf[ 'driver' ] === 'fb' )
			{
				$icon = 'facebook';
			}
			else if ( $feedInf[ 'driver' ] === 'vk' )
			{
				$icon = 'vk';
			}
			else if ( $feedInf[ 'driver' ] === 'twitter' )
			{
				$icon = 'twitter';
			}
			else if ( $feedInf[ 'driver' ] === 'instagram' )
			{
				$icon = 'instagram';
			}
			else if ( $feedInf[ 'driver' ] === 'linkedin' )
			{
				$icon = 'linkedin';
			}
			else if ( $feedInf[ 'driver' ] === 'pinterest' )
			{
				$icon = 'pinterest';
			}
			else if ( $feedInf[ 'driver' ] === 'reddit' )
			{
				$icon = 'reddit';
			}
			else if ( $feedInf[ 'driver' ] === 'ok' )
			{
				$icon = 'odnoklassniki';
			}
			else if ( $feedInf[ 'driver' ] === 'tumblr' )
			{
				$icon = 'tumblr';
			}
			else if ( $feedInf[ 'driver' ] === 'wordpress' )
			{
				$icon = 'wordpress';
			}
			else if ( $feedInf[ 'driver' ] === 'google_b' )
			{
				$icon = 'google';
			}
			else if ( $feedInf[ 'driver' ] === 'telegram' )
			{
				$icon = 'telegram';
			}
			else if ( $feedInf[ 'driver' ] === 'medium' )
			{
				$icon = 'medium';
			}

			if ( $feedInf[ 'driver' ] === 'google_b' )
			{
				$username = $node_info[ 'node_id' ];
			}
			else if ( $feedInf[ 'driver' ] === 'wordpress' )
			{
				$username = isset( $nInf[ 'options' ] ) ? $nInf[ 'options' ] : '';
			}
			else
			{
				$username = isset( $node_info[ 'screen_name' ] ) ? $node_info[ 'screen_name' ] : ( isset( $node_info[ 'username' ] ) ? $node_info[ 'username' ] : '-' );
			}

			$resultData[] = [
				'id'           => $feedInf[ 'id' ],
				'name'         => $node_info ? htmlspecialchars( $node_info[ 'name' ] ) : ' - deleted',
				'post_id'      => htmlspecialchars( $feedInf[ 'driver_post_id' ] ),
				'post_title'   => htmlspecialchars( isset( $postInf->post_title ) ? $postInf->post_title : 'Deleted' ),
				'cover'        => Helper::profilePic( $node_info ),
				'profile_link' => Helper::profileLink( $node_info ),
				'is_sended'    => $feedInf[ 'is_sended' ],
				'post_link'    => Helper::postLink( $feedInf[ 'driver_post_id' ], $feedInf[ 'driver' ] . ( $feedInf[ 'driver' ] === 'instagram' ? $feedInf[ 'feed_type' ] : '' ), $username ),
				'status'       => $feedInf[ 'status' ],
				'error_msg'    => $feedInf[ 'error_msg' ],
				'hits'         => $feedInf[ 'visit_count' ],
				'driver'       => $feedInf[ 'driver' ],
				'icon'         => $icon,
				'insights'     => $insights,
				'node_type'    => ucfirst( $feedInf[ 'node_type' ] ),
				'feed_type'    => ucfirst( (string) $feedInf[ 'feed_type' ] ),
				'date'         => Date::dateTimeSQL( $feedInf[ 'send_time' ] ),
				'wp_post_id'   => $feedInf[ 'post_id' ]
			];
		}

		$show_pages = [ 1, $page, $pages ];

		if ( ( $page - 3 ) >= 1 )
		{
			for ( $i = $page; $i >= $page - 3; $i-- )
			{
				$show_pages[] = $i;
			}
		}
		else if ( ( $page - 2 ) >= 1 )
		{
			for ( $i = $page; $i >= $page - 2; $i-- )
			{
				$show_pages[] = $i;
			}
		}
		else if ( ( $page - 1 ) >= 1 )
		{
			for ( $i = $page; $i >= $page - 1; $i-- )
			{
				$show_pages[] = $i;
			}
		}

		if ( ( $page + 3 ) <= $pages )
		{
			for ( $i = $page; $i <= $page + 3; $i++ )
			{
				$show_pages[] = $i;
			}
		}
		else if ( ( $page + 2 ) <= $pages )
		{
			for ( $i = $page; $i <= $page + 2; $i++ )
			{
				$show_pages[] = $i;
			}
		}
		else if ( ( $page + 1 ) <= $pages )
		{
			for ( $i = $page; $i <= $page + 1; $i++ )
			{
				$show_pages[] = $i;
			}
		}

		$show_pages = array_unique( $show_pages );
		sort( $show_pages );

		Helper::response( TRUE, [
			'data'  => $resultData,
			'pages' => [
				'page_number'  => $show_pages,
				'current_page' => $page,
				'count'        => $pages
			],
			'total' => $allCount[ 'c' ]
		] );
	}

	public function fs_clear_logs ()
	{
		$userId = (int) get_current_user_id();

		DB::DB()->query( "DELETE FROM " . DB::table( 'feeds' ) . ' WHERE blog_id=\'' . Helper::getBlogId() . '\' AND (is_sended=1 OR (send_time+INTERVAL 1 DAY)<NOW()) AND ( (node_type=\'account\' AND (SELECT COUNT(0) FROM ' . DB::table( 'accounts' ) . ' tb2 WHERE tb2.blog_id=\'' . Helper::getBlogId() . '\' AND tb2.id=' . DB::table( 'feeds' ) . '.node_id AND (tb2.user_id=\'' . $userId . '\' OR tb2.is_public=1))>0) OR (node_type<>\'account\' AND (SELECT COUNT(0) FROM ' . DB::table( 'account_nodes' ) . ' tb2 WHERE tb2.id=' . DB::table( 'feeds' ) . '.node_id AND (tb2.user_id=\'' . $userId . '\')>0 OR tb2.is_public=1)) )' );

		Helper::response( TRUE );
	}
}