<?php

namespace FSPoster\App\Providers;

use Exception;
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

class ShareService
{
	public static function insertFeeds ( $wpPostId, $nodes_list, $custom_messages, $categoryFilter = TRUE, $schedule_date = NULL, $shareOnBackground = NULL, $scheduleId = NULL, $disableStartInterval = FALSE )
	{
		/**
		 * Accounts, communications list array
		 */
		$nodes_list = is_array( $nodes_list ) ? $nodes_list : [];

		/**
		 * Instagram, share on:
		 *  - 1: Profile only
		 *  - 2: Story only
		 *  - 3: Profile and Story
		 */
		$igPostType = Helper::getOption( 'instagram_post_in_type', '1' );

		/**
		 * Interval for each publication (sec.)
		 */
		$postInterval        = (int) Helper::getOption( 'post_interval', '0' );
		$postIntervalType    = (int) Helper::getOption( 'post_interval_type', '1' );
		$sendDateTime        = Date::dateTimeSQL( is_null( $schedule_date ) ? 'now' : $schedule_date );
		$intervalForNetworks = [];

		/**
		 * Time interval before start
		 */
		if ( ! $disableStartInterval )
		{
			$timer = (int) Helper::getOption( 'share_timer', '0' );

			if ( $timer > 0 )
			{
				$sendDateTime = Date::dateTimeSQL( $sendDateTime, '+' . $timer . ' minutes' );
			}
		}

		$feedsCount = 0;

		if ( is_null( $shareOnBackground ) )
		{
			$shareOnBackground = (int) Helper::getOption( 'share_on_background', '1' );
		}

		foreach ( $nodes_list as $nodeId )
		{
			if ( is_string( $nodeId ) && strpos( $nodeId, ':' ) !== FALSE )
			{
				$parse         = explode( ':', $nodeId );
				$driver        = $parse[ 0 ];
				$nodeType      = $parse[ 1 ];
				$nodeId        = $parse[ 2 ];
				$filterType    = isset( $parse[ 3 ] ) ? $parse[ 3 ] : 'no';
				$categoriesStr = isset( $parse[ 4 ] ) ? $parse[ 4 ] : '';

				if ( $categoryFilter && ! empty( $categoriesStr ) && $filterType != 'no' )
				{
					$categoriesFilter = [];

					foreach ( explode( ',', $categoriesStr ) as $termId )
					{
						if ( is_numeric( $termId ) && $termId > 0 )
						{
							$categoriesFilter[] = (int) $termId;
						}
					}

					$queryType = $filterType == 'in' ? 'IN' : 'NOT IN';

					$result = DB::DB()->get_row( "SELECT count(0) AS r_count FROM `" . DB::WPtable( 'term_relationships', TRUE ) . "` WHERE object_id='" . (int) $wpPostId . "' AND `term_taxonomy_id` IN (SELECT `term_taxonomy_id` FROM `" . DB::WPtable( 'term_taxonomy', TRUE ) . "` WHERE `term_id` IN ('" . implode( "' , '", $categoriesFilter ) . "'))", ARRAY_A );

					if ( ( $filterType == 'in' && $result[ 'r_count' ] == 0 ) || ( $filterType == 'ex' && $result[ 'r_count' ] > 0 ) )
					{
						continue;
					}
				}

				if ( $nodeType == 'account' && in_array( $driver, [ 'tumblr', 'google_b', 'telegram' ] ) )
				{
					continue;
				}

				if ( ! ( in_array( $nodeType, [
						'account',
						'ownpage',
						'page',
						'group',
						'event',
						'blog',
						'company',
						'community',
						'subreddit',
						'location',
						'chat',
						'board',
						'publication'
					] ) && is_numeric( $nodeId ) && $nodeId > 0 ) )
				{
					continue;
				}

				if ( $postInterval > 0 )
				{
					$driver2ForArr = $postIntervalType == 1 ? $driver : 'all';
					$dataSendTime  = isset( $intervalForNetworks[ $driver2ForArr ] ) ? $intervalForNetworks[ $driver2ForArr ] : $sendDateTime;
				}
				else
				{
					$dataSendTime = $sendDateTime;
				}

				if ( ! ( $driver == 'instagram' && $igPostType == '2' ) )
				{
					$customMessage = isset( $custom_messages[ $driver ] ) ? $custom_messages[ $driver ] : NULL;

					if ( $customMessage == Helper::getOption( 'post_text_message_' . $driver, "{title}" ) )
					{
						$customMessage = NULL;
					}

					DB::DB()->insert( DB::table( 'feeds' ), [
						'blog_id'             => Helper::getBlogId(),
						'driver'              => $driver,
						'post_id'             => $wpPostId,
						'node_type'           => $nodeType,
						'node_id'             => (int) $nodeId,
						'interval'            => $postInterval,
						'custom_post_message' => $customMessage,
						'send_time'           => $dataSendTime,
						'share_on_background' => $shareOnBackground ? 1 : 0,
						'schedule_id'         => $scheduleId,
						'is_seen'             => 0
					] );

					$feedsCount++;
				}

				if ( $driver == 'instagram' && ( $igPostType == '2' || $igPostType == '3' ) )
				{
					$customMessage = isset( $custom_messages[ $driver . '_h' ] ) ? $custom_messages[ $driver . '_h' ] : NULL;

					if ( $customMessage == Helper::getOption( 'post_text_message_' . $driver . '_h', "{title}" ) )
					{
						$customMessage = NULL;
					}

					DB::DB()->insert( DB::table( 'feeds' ), [
						'blog_id'             => Helper::getBlogId(),
						'driver'              => $driver,
						'post_id'             => $wpPostId,
						'node_type'           => $nodeType,
						'node_id'             => (int) $nodeId,
						'interval'            => $postInterval,
						'feed_type'           => 'story',
						'custom_post_message' => $customMessage,
						'send_time'           => $dataSendTime,
						'share_on_background' => $shareOnBackground ? 1 : 0,
						'schedule_id'         => $scheduleId,
						'is_seen'             => 0
					] );

					$feedsCount++;
				}

				if ( $postInterval > 0 )
				{
					$intervalForNetworks[ $driver2ForArr ] = Date::dateTimeSQL( $dataSendTime, '+' . $postInterval . ' second' );
				}
			}
		}

		return $feedsCount;
	}

	public static function shareQueuedFeeds ()
	{
		$allBlogs = Helper::getBlogs();

		foreach ( $allBlogs as $blogId )
		{
			Helper::setBlogId( $blogId );

			$nowDateTime = Date::dateTimeSQL();

			$getFeeds = DB::DB()->prepare( 'SELECT id FROM `' . DB::table( 'feeds' ) . '` tb1 WHERE `blog_id`=%d AND `share_on_background`=1 and `is_sended`=0 and `send_time`<=%s AND (SELECT count(0) FROM `' . DB::WPtable( 'posts', TRUE ) . '` WHERE `id`=tb1.`post_id` AND (`post_status`=\'publish\' OR `post_type`=\'attachment\'))>0', [
				$blogId,
				$nowDateTime
			] );
			$getFeeds = DB::DB()->get_results( $getFeeds, ARRAY_A );
			$feedIDs  = [];
			foreach ( $getFeeds as $feedInf )
			{
				$feedIDs[] = (int) $feedInf[ 'id' ];
			}

			// for preventing dublicat shares...
			if ( ! empty( $feedIDs ) )
			{
				DB::DB()->query( 'UPDATE `' . DB::table( 'feeds' ) . '` SET `is_sended`=2 WHERE id IN (\'' . implode( "','", $feedIDs ) . '\')' );
			}

			foreach ( $feedIDs as $feedId )
			{
				ShareService::post( $feedId, TRUE );
			}

			Helper::resetBlogId();
		}
	}

	public static function postSaveEvent ( $new_status, $old_status, $post )
	{
		global $wp_version;
		$post_id = $post->ID;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return;
		}

		if ( ! in_array( $new_status, [ 'publish', 'future', 'draft' ] ) )
		{
			return;
		}

		/**
		 * Gutenberg bug...
		 * https://github.com/WordPress/gutenberg/issues/15094
		 */
		$_locale = Request::get( '_locale', '', 'string' );

		if ( version_compare( $wp_version, '5.0', '>=' ) && $_locale === 'user' && empty( $_POST ) )
		{
			delete_post_meta( $post_id, '_fs_poster_post_old_status_saved' );
			add_post_meta( $post_id, '_fs_poster_post_old_status_saved', $old_status, TRUE );

			return;
		}

		// if not allowed post type...
		if ( ! in_array( $post->post_type, explode( '|', Helper::getOption( 'allowed_post_types', 'post|page|attachment|product' ) ) ) )
		{
			return;
		}

		$metaBoxLoader            = (int) Request::get( 'meta-box-loader', 0, 'num', [ '1' ] );
		$original_post_old_status = Request::post( 'original_post_status', '', 'string' );

		if ( $metaBoxLoader === 1 && ! empty( $original_post_old_status ) )
		{
			// Gutenberg bug!
			$old_status = get_post_meta( $post_id, '_fs_poster_post_old_status_saved', TRUE );
			delete_post_meta( $post_id, '_fs_poster_post_old_status_saved' );
		}

		if ( $old_status == 'publish' )
		{
			return;
		}

		if ( $old_status == 'future' && ( $new_status == 'future' || $new_status == 'publish' ) )
		{
			$oldScheduleDate = Date::epoch( get_post_meta( $post_id, '_fs_poster_schedule_datetime', TRUE ) );
			$newDateTime     = $new_status == 'publish' ? Date::epoch() : Date::epoch( $post->post_date );
			$diff            = (int) ( ( $newDateTime - $oldScheduleDate ) / 60 );

			if ( $diff != 0 && abs( $diff ) < 60 * 24 * 90 )
			{
				DB::DB()->query( 'UPDATE `' . DB::table( 'feeds' ) . '` SET `send_time`=ADDDATE(`send_time`,INTERVAL ' . $diff . ' MINUTE) WHERE blog_id=\'' . Helper::getBlogId() . '\' AND is_sended=0 and post_id=\'' . (int) $post_id . '\'' );
			}

			delete_post_meta( $post_id, '_fs_poster_schedule_datetime' );

			if ( $new_status == 'future' )
			{
				add_post_meta( $post_id, '_fs_poster_schedule_datetime', $post->post_date, TRUE );
			}

			return;
		}

		$userId = $post->post_author;

		if ( $old_status == 'future' && $new_status == 'draft' )
		{
			$nodes_list        = [];
			$post_text_message = [
				'fb'          => Helper::getOption( 'post_text_message_fb', "{title}" ),
				'instagram'   => Helper::getOption( 'post_text_message_instagram', "{title}" ),
				'instagram_h' => Helper::getOption( 'post_text_message_instagram_h', "{title}" ),
				'twitter'     => Helper::getOption( 'post_text_message_twitter', "{title}" ),
				'linkedin'    => Helper::getOption( 'post_text_message_linkedin', "{title}" ),
				'tumblr'      => Helper::getOption( 'post_text_message_tumblr', "{title}" ),
				'reddit'      => Helper::getOption( 'post_text_message_reddit', "{title}" ),
				'vk'          => Helper::getOption( 'post_text_message_vk', "{title}" ),
				'ok'          => Helper::getOption( 'post_text_message_ok', "{title}" ),
				'pinterest'   => Helper::getOption( 'post_text_message_pinterest', "{title}" ),
				'google_b'    => Helper::getOption( 'post_text_message_google_b', "{title}" ),
				'telegram'    => Helper::getOption( 'post_text_message_telegram', "{title}" ),
				'medium'      => Helper::getOption( 'post_text_message_medium', "{title}" ),
				'wordpress'   => Helper::getOption( 'post_text_message_wordpress', "{content_full}" )
			];

			$getScheduledFeeds = DB::DB()->get_results( DB::DB()->prepare( "
					SELECT tb1.node_id AS id, tb1.driver, tb1.node_type, tb2.filter_type, tb2.categories, tb1.custom_post_message FROM `" . DB::table( 'feeds' ) . "` tb1 LEFT JOIN `" . DB::table( 'account_status' ) . "` tb2 ON tb2.account_id=tb1.node_id AND tb2.user_id=%d WHERE tb1.post_id=%d AND node_type='account'
					UNION 
					SELECT tb1.node_id AS id, tb1.driver, tb1.node_type, tb2.filter_type, tb2.categories, tb1.custom_post_message FROM `" . DB::table( 'feeds' ) . "` tb1 LEFT JOIN `" . DB::table( 'account_node_status' ) . "` tb2 ON tb2.node_id=tb1.node_id AND tb2.user_id=%d WHERE tb1.post_id=%d AND node_type<>'account'
					", [ $userId, $post_id, $userId, $post_id ] ), ARRAY_A );

			foreach ( $getScheduledFeeds as $nodeInf )
			{
				$nodes_list[] = $nodeInf[ 'driver' ] . ':' . $nodeInf[ 'node_type' ] . ':' . $nodeInf[ 'id' ] . ':' . htmlspecialchars( $nodeInf[ 'filter_type' ] ) . ':' . htmlspecialchars( $nodeInf[ 'categories' ] );

				$post_text_message[ $nodeInf[ 'driver' ] ] = $nodeInf[ 'custom_post_message' ];
			}

			add_post_meta( $post_id, '_fs_poster_share', ( empty( $nodes_list ) ? Helper::getOption( 'auto_share_new_posts', '1' ) : 1 ), TRUE );
			add_post_meta( $post_id, '_fs_poster_node_list', $nodes_list, TRUE );

			foreach ( $post_text_message as $dr => $cmtxt )
			{
				add_post_meta( $post_id, '_fs_poster_cm_' . $dr, $cmtxt, TRUE );
			}

			DB::DB()->delete( DB::table( 'feeds' ), [
				'blog_id'   => Helper::getBlogId(),
				'post_id'   => $post_id,
				'is_sended' => '0'
			] );

			return;
		}

		$post_text_message = [];

		$post_text_message[ 'fb' ]          = Request::post( 'fs_post_text_message_fb', '', 'string' );
		$post_text_message[ 'twitter' ]     = Request::post( 'fs_post_text_message_twitter', '', 'string' );
		$post_text_message[ 'instagram' ]   = Request::post( 'fs_post_text_message_instagram', '', 'string' );
		$post_text_message[ 'instagram_h' ] = Request::post( 'fs_post_text_message_instagram_h', '', 'string' );
		$post_text_message[ 'linkedin' ]    = Request::post( 'fs_post_text_message_linkedin', '', 'string' );
		$post_text_message[ 'vk' ]          = Request::post( 'fs_post_text_message_vk', '', 'string' );
		$post_text_message[ 'pinterest' ]   = Request::post( 'fs_post_text_message_pinterest', '', 'string' );
		$post_text_message[ 'reddit' ]      = Request::post( 'fs_post_text_message_reddit', '', 'string' );
		$post_text_message[ 'tumblr' ]      = Request::post( 'fs_post_text_message_tumblr', '', 'string' );
		$post_text_message[ 'ok' ]          = Request::post( 'fs_post_text_message_ok', '', 'string' );
		$post_text_message[ 'google_b' ]    = Request::post( 'fs_post_text_message_google_b', '', 'string' );
		$post_text_message[ 'telegram' ]    = Request::post( 'fs_post_text_message_telegram', '', 'string' );
		$post_text_message[ 'medium' ]      = Request::post( 'fs_post_text_message_medium', '', 'string' );
		$post_text_message[ 'wordpress' ]   = Request::post( 'fs_post_text_message_wordpress', '', 'string' );

		if ( $old_status == 'draft' )
		{
			delete_post_meta( $post_id, '_fs_poster_share' );
			delete_post_meta( $post_id, '_fs_poster_node_list' );

			foreach ( $post_text_message as $dr => $cmtxt )
			{
				delete_post_meta( $post_id, '_fs_poster_cm_' . $dr );
			}
		}

		// Exit the function if the 'Share' checkbox is not checked
		$share_checked_inpt = Request::post( 'share_checked', NULL, 'string', [ 'on', 'off' ] );

		if ( is_null( $share_checked_inpt ) )
		{
			$share_checked_inpt = Helper::getOption( 'auto_share_new_posts', '1' ) ? 'on' : 'off';
		}

		if ( $share_checked_inpt !== 'on' )
		{
			if ( $new_status == 'draft' )
			{
				add_post_meta( $post_id, '_fs_poster_share', 0, TRUE );
			}

			DB::DB()->delete( DB::table( 'feeds' ), [
				'blog_id'   => Helper::getBlogId(),
				'post_id'   => $post_id,
				'is_sended' => '0'
			] );

			return;
		}

		// run share process on background
		if ( $new_status == 'future' )
		{
			$backgroundShare = 1;

			add_post_meta( $post_id, '_fs_poster_schedule_datetime', $post->post_date, TRUE );
		}
		else
		{
			$backgroundShare = (int) Helper::getOption( 'share_on_background', '1' );
		}

		// social networks lists
		$nodes_list = Request::post( 'share_on_nodes', FALSE, 'array' );

		// If from XMLRPC: load all active nodes
		if ( $nodes_list === FALSE && ( $new_status !== 'draft' or $old_status === 'new' ) && ! isset( $_POST[ 'share_checked' ] ) )
		{

			$nodes_list = [];

			$accounts = DB::DB()->get_results( DB::DB()->prepare( "
					SELECT tb2.id, tb2.driver, tb1.filter_type, tb1.categories, 'account' AS node_type 
					FROM " . DB::table( 'account_status' ) . " tb1
					INNER JOIN " . DB::table( 'accounts' ) . " tb2 ON tb2.id=tb1.account_id
					WHERE tb1.user_id=%d AND tb2.blog_id=%d", [ $userId, Helper::getBlogId() ] ), ARRAY_A );

			$active_nodes = DB::DB()->get_results( DB::DB()->prepare( "
					SELECT tb2.id, tb2.driver, tb2.node_type, tb1.filter_type, tb1.categories FROM " . DB::table( 'account_node_status' ) . " tb1
					LEFT JOIN " . DB::table( 'account_nodes' ) . " tb2 ON tb2.id=tb1.node_id
					WHERE tb1.user_id=%d AND tb2.blog_id=%d", [ $userId, Helper::getBlogId() ] ), ARRAY_A );

			$active_nodes = array_merge( $accounts, $active_nodes );

			foreach ( $active_nodes as $nodeInf )
			{
				$nodes_list[] = $nodeInf[ 'driver' ] . ':' . $nodeInf[ 'node_type' ] . ':' . $nodeInf[ 'id' ] . ':' . htmlspecialchars( $nodeInf[ 'filter_type' ] ) . ':' . htmlspecialchars( $nodeInf[ 'categories' ] );
			}
		}

		if ( $new_status == 'draft' )
		{
			add_post_meta( $post_id, '_fs_poster_share', 1, TRUE );
			add_post_meta( $post_id, '_fs_poster_node_list', $nodes_list, TRUE );

			foreach ( $post_text_message as $dr => $cmtxt )
			{
				add_post_meta( $post_id, '_fs_poster_cm_' . $dr, $cmtxt, TRUE );
			}

			return;
		}

		// Insert queued posts in feeds table
		$schedule_date = NULL;
		if ( $new_status == 'future' )
		{
			$schedule_date = Date::dateTimeSQL( $post->post_date, '+1 minute' );
		}
		self::insertFeeds( $post_id, $nodes_list, $post_text_message, TRUE, $schedule_date, $backgroundShare );

		// if not scheduled post then add arguments end of url
		if ( $new_status == 'publish' )
		{
			add_filter( 'redirect_post_location', function ( $location ) use ( $backgroundShare ) {
				return $location . '&share=1&background=' . $backgroundShare;
			} );
		}
	}

	public static function deletePostFeeds ( $post_id )
	{
		DB::DB()->delete( DB::table( 'feeds' ), [
			'blog_id'   => Helper::getBlogId(),
			'post_id'   => $post_id,
			'is_sended' => 0
		] );
	}

	public static function shareSchedules ()
	{
		$nowDateTime = Date::dateTimeSQL();

		$getSchdules = DB::DB()->prepare( 'SELECT * FROM `' . DB::table( 'schedules' ) . '` WHERE `status`=\'active\' and `next_execute_time`<=%s', [ $nowDateTime ] );
		$getSchdules = DB::DB()->get_results( $getSchdules, ARRAY_A );

		$preventDublicates = DB::DB()->prepare( 'UPDATE `' . DB::table( 'schedules' ) . '` SET `next_execute_time`=DATE_ADD(\'%s\', INTERVAL `interval` MINUTE) WHERE `status`=\'active\' and `next_execute_time`<=%s', [
			$nowDateTime,
			$nowDateTime
		] );
		DB::DB()->query( $preventDublicates );

		$result = FALSE;
		foreach ( $getSchdules as $schedule_info )
		{
			if ( self::scheduledPost( $schedule_info ) )
			{
				$result = TRUE;
			}
		}

		if ( $result )
		{
			self::shareQueuedFeeds();
		}
	}

	public static function scheduledPost ( $schedule_info )
	{
		$scheduleId = $schedule_info[ 'id' ];
		$userId     = $schedule_info[ 'user_id' ];
		$blogId     = $schedule_info[ 'blog_id' ];
		$interval   = (int) $schedule_info[ 'interval' ];

		Helper::setBlogId( $blogId );

		// check if is sleep time...
		if ( ! empty( $schedule_info[ 'sleep_time_start' ] ) && ! empty( $schedule_info[ 'sleep_time_end' ] ) )
		{
			$currentTimestamp = Date::epoch( Date::dateTimeSQL() );

			$sleepTimeStart = Date::epoch( Date::dateSQL() . ' ' . $schedule_info[ 'sleep_time_start' ] );
			$sleepTimeEnd   = Date::epoch( Date::dateSQL() . ' ' . $schedule_info[ 'sleep_time_end' ] );

			if ( $sleepTimeStart > $sleepTimeEnd )
			{
				$sleepTimeEnd += 24 * 60 * 60;
			}

			if ( $currentTimestamp >= $sleepTimeStart && $currentTimestamp <= $sleepTimeEnd )
			{
				Helper::resetBlogId();

				return;
			}
		}

		$filterQuery = Helper::scheduleFilters( $schedule_info );

		/* End post_sort */
		$getRandomPost = DB::DB()->get_row( "SELECT * FROM `" . DB::WPtable( 'posts', TRUE ) . "` tb1 WHERE (post_status='publish' OR post_type='attachment') {$filterQuery} LIMIT 1", ARRAY_A );
		$post_id       = $getRandomPost[ 'ID' ];
		$postType      = $getRandomPost[ 'post_type' ];

		if ( ! ( $post_id > 0 ) )
		{
			DB::DB()->update( DB::table( 'schedules' ), [ 'status' => 'finished' ], [ 'id' => $scheduleId ] );
			Helper::resetBlogId();

			return;
		}

		if ( $schedule_info[ 'post_freq' ] === 'once' && ! empty( $schedule_info[ 'post_ids' ] ) )
		{
			DB::DB()->query( DB::DB()->prepare( "UPDATE `" . DB::table( 'schedules' ) . "` SET `post_ids`=TRIM(BOTH ',' FROM replace(concat(',',`post_ids`,','), ',%d,',',')), status=IF( `post_ids`='' , 'finished', `status`) WHERE `id`=%d", [
				$post_id,
				$scheduleId
			] ) );
		}

		$accountsList = explode( ',', $schedule_info[ 'share_on_accounts' ] );
		if ( ! empty( $schedule_info[ 'share_on_accounts' ] ) && is_array( $accountsList ) && ! empty( $accountsList ) && count( $accountsList ) > 0 )
		{
			$_accountsList = [];
			$_node_list    = [];

			foreach ( $accountsList as $accountN )
			{
				$accountN = explode( ':', $accountN );

				if ( ! isset( $accountN[ 1 ] ) )
				{
					continue;
				}

				if ( $accountN[ 0 ] == 'account' )
				{
					$_accountsList[] = (int) $accountN[ 1 ];
				}
				else
				{
					$_node_list[] = (int) $accountN[ 1 ];
				}
			}

			$get_activeAccounts = [];
			$get_activeNodes    = [];

			if ( ! empty( $_accountsList ) )
			{
				$get_activeAccounts = DB::DB()->get_results( DB::DB()->prepare( "
						SELECT tb1.*, IFNULL(filter_type,'no') AS filter_type, categories
						FROM " . DB::table( 'accounts' ) . " tb1
						LEFT JOIN " . DB::table( 'account_status' ) . " tb2 ON tb1.id=tb2.account_id AND tb2.user_id=%d
						WHERE (tb1.is_public=1 OR tb1.user_id=%d) AND tb1.blog_id=%d AND tb1.id in (" . implode( ',', $_accountsList ) . ")", [
					$userId,
					$userId,
					Helper::getBlogId()
				] ), ARRAY_A );
			}

			if ( ! empty( $_node_list ) )
			{
				$get_activeNodes = DB::DB()->get_results( DB::DB()->prepare( "
						SELECT tb1.*, IFNULL(filter_type,'no') AS filter_type, categories
						FROM " . DB::table( 'account_nodes' ) . " tb1
						LEFT JOIN " . DB::table( 'account_node_status' ) . " tb2 ON tb1.id=tb2.node_id AND tb2.user_id=%d
						WHERE (tb1.is_public=1 OR tb1.user_id=%d) AND tb1.blog_id=%d AND tb1.id in (" . implode( ',', $_node_list ) . ")", [
					$userId,
					$userId,
					Helper::getBlogId()
				] ), ARRAY_A );
			}
		}

		$customPostMessages = json_decode( $schedule_info[ 'custom_post_message' ], TRUE );
		$customPostMessages = is_array( $customPostMessages ) ? $customPostMessages : [];
		$nodes_list         = [];

		foreach ( $get_activeAccounts as $accountInf )
		{
			$nodes_list[] = $accountInf[ 'driver' ] . ':account:' . (int) $accountInf[ 'id' ] . ':' . $accountInf[ 'filter_type' ] . ':' . $accountInf[ 'categories' ];
		}

		foreach ( $get_activeNodes as $nodeInf )
		{
			$nodes_list[] = $nodeInf[ 'driver' ] . ':' . $nodeInf[ 'node_type' ] . ':' . (int) $nodeInf[ 'id' ] . ':' . $nodeInf[ 'filter_type' ] . ':' . $nodeInf[ 'categories' ];
		}

		if ( ! empty( $nodes_list ) )
		{
			$categoryFilter = $postType == 'fs_post' || $postType == 'fs_post_tmp' ? FALSE : TRUE;

			self::insertFeeds( $post_id, $nodes_list, $customPostMessages, $categoryFilter, NULL, 1, $scheduleId, TRUE );
			Helper::resetBlogId();

			return TRUE;
		}

		Helper::resetBlogId();

		return FALSE;
	}

	public static function post ( $feedId, $secureShare = FALSE )
	{
		$feedInf = DB::fetch( 'feeds', $feedId );

		if ( ! $feedInf || ( $secureShare && $feedInf[ 'is_sended' ] != 2 ) )
		{
			return;
		}

		$post_id             = $feedInf[ 'post_id' ];
		$custom_post_message = $feedInf[ 'custom_post_message' ];

		$nInf = Helper::getAccessToken( $feedInf[ 'node_type' ], $feedInf[ 'node_id' ] );

		$nodeProfileId     = $nInf[ 'node_id' ];
		$appId             = $nInf[ 'app_id' ];
		$driver            = $nInf[ 'driver' ];
		$accessToken       = $nInf[ 'access_token' ];
		$accessTokenSecret = $nInf[ 'access_token_secret' ];
		$proxy             = $nInf[ 'info' ][ 'proxy' ];
		$options           = $nInf[ 'options' ];
		$accoundId         = $nInf[ 'account_id' ];

		$link           = '';
		$message        = '';
		$sendType       = 'status';
		$images         = NULL;
		$imagesLocale   = NULL;
		$videoURL       = NULL;
		$videoURLLocale = NULL;

		$postInf   = get_post( $post_id, ARRAY_A );
		$postType  = $postInf[ 'post_type' ];
		$postTitle = $postInf[ 'post_title' ];

		if ( $postType == 'attachment' && strpos( $postInf[ 'post_mime_type' ], 'image' ) !== FALSE )
		{
			$sendType       = 'image';
			$images[]       = $postInf[ 'guid' ];
			$imagesLocale[] = get_attached_file( $post_id );
		}
		else if ( $postType == 'attachment' && strpos( $postInf[ 'post_mime_type' ], 'video' ) !== FALSE )
		{
			$sendType       = 'video';
			$videoURL       = $postInf[ 'guid' ];
			$videoURLLocale = get_attached_file( $post_id );
		}
		else
		{
			$sendType = 'link';
		}

		$shortLink = '';
		$longLink  = '';

		if ( $postType == 'fs_post' || $postType == 'fs_post_tmp' )
		{
			$message = Helper::spintax( $postInf[ 'post_content' ] );

			$link1    = get_post_meta( $post_id, '_fs_link', TRUE );
			$link1    = Helper::spintax( $link1 );
			$longLink = $link1;

			$mediaId = get_post_thumbnail_id( $post_id );
			if ( $mediaId > 0 )
			{
				$sendType = 'image';
				$url1     = wp_get_attachment_url( $mediaId );
				$url2     = get_attached_file( $mediaId );

				$images       = [ $url1 ];
				$imagesLocale = [ $url2 ];
			}

			if ( ! empty( $link1 ) )
			{
				if ( Helper::getOption( 'unique_link', '1' ) == 1 )
				{
					$link1 .= ( strpos( $link1, '?' ) === FALSE ? '?' : '&' ) . '_unique_id=' . uniqid();
				}

				$link      = $link1;
				$shortLink = Helper::shortenerURL( $link1 );
			}
		}
		else
		{
			$link = Helper::getPostLink( $postInf, $feedId, $nInf[ 'info' ] );

			if ( empty( $custom_post_message ) )
			{
				$default_value       = $driver == 'wordpress' ? '{content_full}' : '{title}';
				$custom_post_message = Helper::getOption( 'post_text_message_' . $driver . ( $driver == 'instagram' && $feedInf[ 'feed_type' ] == 'story' ? '_h' : '' ), $default_value );
			}

			$longLink  = $link;
			$shortLink = Helper::shortenerURL( $link );

			$message = Helper::replaceTags( $custom_post_message, $postInf, $longLink, $shortLink );
			$message = Helper::spintax( $message );

			if ( Helper::getOption( 'replace_wp_shortcodes', 'off' ) === 'on' )
			{
				$message = do_shortcode( $message );
			}
			else if ( Helper::getOption( 'replace_wp_shortcodes', 'off' ) === 'del' )
			{
				$message = strip_shortcodes( $message );
			}

			$message = htmlspecialchars_decode( $message );

			$link = $shortLink;
		}

		if ( $driver != 'medium' && $driver != 'wordpress' && $driver != 'tumblr' )
		{
			if ( $driver === 'telegram' )
			{
				$message = strip_tags( $message, '<b><u><i>' );
			}
			else
			{
				$message = strip_tags( $message );
			}

			$message = str_replace( [ '&nbsp;', "\r\n" ], [ '', "\n" ], $message );
			//$message = preg_replace("/(\n\s*)+/", "\n", $message);
		}

		if ( $driver == 'fb' )
		{
			if ( $sendType != 'image' && $sendType != 'video' )
			{
				$pMethod = Helper::getOption( 'facebook_posting_type', '1' );

				if ( $pMethod == '2' )
				{
					$thumbnail = WPPostThumbnail::getPostThumbnailURL( $post_id );

					if ( ! empty( $thumbnail ) )
					{
						$sendType = 'image';
						$images   = [ $thumbnail ];
					}
				}
				else if ( $pMethod == '3' )
				{
					$images = WPPostThumbnail::getPostGalleryURL( $post_id, $postType );

					if ( ! empty( $images ) )
					{
						$sendType = 'image';
					}
				}
			}

			if ( empty( $options ) ) // App method
			{
				$res = Facebook::sendPost( $nodeProfileId, $sendType, $message, 0, $link, $images, $videoURL, $accessToken, $proxy );
			}
			else // Cookie method
			{
				$fbDriver = new FacebookCookieApi( $accoundId, $options, $proxy );
				$res      = $fbDriver->sendPost( $nodeProfileId, $feedInf[ 'node_type' ], $sendType, $message, 0, $link, $images, $videoURL );
			}
		}
		else if ( $driver == 'instagram' )
		{
			if ( $sendType != 'image' && $sendType != 'video' )
			{
				$thumbnailPath = WPPostThumbnail::getPostThumbnail( $post_id );

				if ( ! empty( $thumbnailPath ) )
				{
					$sendType     = 'image';
					$imagesLocale = [ $thumbnailPath ];
				}
			}
			if ( ! empty( $imagesLocale ) || ! empty( $videoURLLocale ) )
			{
				if ( $feedInf[ 'feed_type' ] == 'story' )
				{
					try
					{
						$res = InstagramApi::sendStory( $nInf[ 'info' ], $sendType, $message, $link, $imagesLocale, $videoURLLocale );
					}
					catch ( Exception $e )
					{
						$res = [
							'status'    => 'error',
							'error_msg' => esc_html__( 'Error! ' . $e->getMessage(), 'fs-poster' )
						];
					}
				}
				else
				{
					$res = InstagramApi::sendPost( $nInf[ 'info' ], $sendType, $message, $link, $imagesLocale, $videoURLLocale );
				}
			}
			else
			{
				$res = [
					'status'    => 'error',
					'error_msg' => esc_html__( 'Error! An image or video is required to share a post on Instagram.', 'fs-poster' )
				];
			}
		}
		else if ( $driver == 'linkedin' )
		{
			if ( $sendType != 'image' && $sendType != 'video' )
			{
				$pMethod = Helper::getOption( 'linkedin_posting_type', '1' );

				if ( $pMethod === '2' )
				{
					$thumbnailPath = WPPostThumbnail::getPostThumbnail( $post_id );

					if ( ! empty( $thumbnailPath ) )
					{
						$sendType     = 'image';
						$imagesLocale = [ $thumbnailPath ];
					}
				}
				else if ( $pMethod === '3' )
				{
					$imagesLocale = WPPostThumbnail::getPostGallery( $post_id, $postType );

					if ( ! empty( $imagesLocale ) )
					{
						$sendType = 'image';
					}
				}
			}

			$res = Linkedin::sendPost( $accoundId, $nInf[ 'info' ], $sendType, $message, $postInf[ 'post_title' ], $link, $imagesLocale, $videoURL, $accessToken, $proxy );
		}
		else if ( $driver == 'vk' )
		{
			if ( Helper::getOption( 'vk_upload_image', '1' ) == 1 && $sendType != 'image' && $sendType != 'video' )
			{
				$thumbnailPath = WPPostThumbnail::getPostThumbnail( $post_id );

				if ( ! empty( $thumbnailPath ) )
				{
					$sendType     = 'image_link';
					$imagesLocale = [ $thumbnailPath ];
				}
			}

			$res = Vk::sendPost( $nodeProfileId, $sendType, $message, $link, $imagesLocale, $videoURLLocale, $accessToken, $proxy );
		}
		else if ( $driver == 'pinterest' )
		{
			if ( empty( $options ) ) // App method
			{
				if ( $sendType != 'image' )
				{
					$thumbURL = WPPostThumbnail::getPostThumbnailURL( $post_id );

					if ( ! empty( $thumbURL ) )
					{
						$sendType = 'image';
						$images   = [ $thumbURL ];
					}
				}

				$res = Pinterest::sendPost( $nodeProfileId, $sendType, $message, $longLink, $images, $accessToken, $proxy );
			}
			else // Cookie method
			{
				if ( $sendType != 'image' )
				{
					$thumbPath = WPPostThumbnail::getPostThumbnail( $post_id );

					if ( ! empty( $thumbPath ) )
					{
						$sendType     = 'image';
						$imagesLocale = [ $thumbPath ];
					}
				}

				if ( Helper::getOption( 'pinterest_autocut_title', '1' ) == 1 && mb_strlen( $postTitle ) > 100 )
				{
					$postTitle = mb_substr( $postTitle, 0, 97 ) . '...';
				}

				$getCookie = DB::fetch( 'account_sessions', [
					'driver'   => 'pinterest',
					'username' => $nInf[ 'username' ]
				] );

				$pinterest = new PinterestCookieApi( $getCookie[ 'cookies' ], $proxy );
				$res       = $pinterest->sendPost( $nodeProfileId, $postTitle, $message, $longLink, $imagesLocale );
			}
		}
		else if ( $driver == 'reddit' )
		{
			$res = Reddit::sendPost( $nInf[ 'info' ], $sendType, $postTitle, $message, $longLink, $images, $videoURL, $accessToken, $proxy );
		}
		else if ( $driver == 'tumblr' )
		{
			if ( $sendType != 'image' && $sendType != 'video' )
			{
				$thumbnailPath = WPPostThumbnail::getPostThumbnail( $post_id );

				if ( ! empty( $thumbnailPath ) )
				{
					$sendType     = 'image';
					$imagesLocale = [ $thumbnailPath ];
				}
			}

			$res = Tumblr::sendPost( $nInf[ 'info' ], $sendType, $postTitle, $message, $link, $imagesLocale, $videoURLLocale, $accessToken, $accessTokenSecret, $appId, $proxy );
		}
		else if ( $driver == 'twitter' )
		{
			if ( $sendType != 'image' && $sendType != 'video' )
			{
				$pMethod = Helper::getOption( 'twitter_posting_type', '1' );

				if ( $pMethod == '2' )
				{
					$thumbnailPath = WPPostThumbnail::getPostThumbnail( $post_id );

					if ( ! empty( $thumbnailPath ) )
					{
						$sendType     = 'image';
						$imagesLocale = [ $thumbnailPath ];
					}
				}
				else if ( $pMethod == '3' )
				{
					$imagesLocale = WPPostThumbnail::getPostGallery( $post_id, $postType );

					if ( ! empty( $imagesLocale ) )
					{
						$sendType = 'image';
					}
				}
			}

			if ( Helper::getOption( 'twitter_auto_cut_tweets', '1' ) == 1 )
			{
				$message = preg_replace( '/\n+/', "\n", $message );
				$message = preg_replace( '/[\t ]+/', ' ', $message );

				if ( $sendType === 'link' )
				{
					if ( mb_strlen( "\n" . $link, 'UTF-8' ) <= 280 )
					{
						$limit   = 280 - mb_strlen( "\n" . $link, 'UTF-8' );
						$message = mb_substr( $message, 0, $limit - 3, 'UTF-8' ) . '...';
					}
					else
					{
						$link = '';

						if ( mb_strlen( $message, 'UTF-8' ) > 280 )
						{
							$message = mb_substr( $message, 0, 277, 'UTF-8' ) . '...';
						}
					}
				}
				else if ( mb_strlen( $message, 'UTF-8' ) > 280 )
				{
					$message = mb_substr( $message, 0, 277, 'UTF-8' ) . '...';
				}
			}

			$res = Twitter::sendPost( $appId, $sendType, $message, $link, $imagesLocale, $videoURLLocale, $accessToken, $accessTokenSecret, $proxy );
		}
		else if ( $driver == 'ok' )
		{
			if ( $sendType != 'image' && $sendType != 'video' )
			{
				$pMethod = Helper::getOption( 'ok_posting_type', '1' );

				if ( $pMethod == '2' )
				{
					$thumbnailPath = WPPostThumbnail::getPostThumbnail( $post_id );

					if ( ! empty( $thumbnailPath ) )
					{
						$sendType     = 'image';
						$imagesLocale = [ $thumbnailPath ];
					}
				}
				else if ( $pMethod == '3' )
				{
					$imagesLocale = WPPostThumbnail::getPostGallery( $post_id, $postType );

					if ( ! empty( $imagesLocale ) )
					{
						$sendType = 'image';
					}
				}
			}

			$appInf = DB::fetch( 'apps', [ 'id' => $appId ] );

			$res = OdnoKlassniki::sendPost( $nInf[ 'info' ], $sendType, $message, $link, $imagesLocale, $videoURLLocale, $accessToken, $appInf[ 'app_key' ], $appInf[ 'app_secret' ], $proxy );
		}
		else if ( $driver == 'google_b' )
		{
			if ( $sendType == 'video' )
			{
				$res = [
					'status'    => 'error',
					'error_msg' => esc_html__( 'Google My Business doesn\'t support video type!', 'fs-poster' )
				];
			}
			else
			{
				if ( $sendType != 'image' )
				{
					$thumbURL = WPPostThumbnail::getPostThumbnailURL( $post_id );

					if ( ! empty( $thumbURL ) )
					{
						$sendType = 'image';
						$images   = [ $thumbURL ];
					}
				}

				$imageUrl = is_array( $images ) ? reset( $images ) : '';

				$options     = json_decode( $options, TRUE );
				$cookie_sid  = isset( $options[ 'sid' ] ) ? $options[ 'sid' ] : '';
				$cookie_hsid = isset( $options[ 'hsid' ] ) ? $options[ 'hsid' ] : '';
				$cookie_ssid = isset( $options[ 'ssid' ] ) ? $options[ 'ssid' ] : '';

				$linkButton = Helper::getOption( 'google_b_button_type', 'LEARN_MORE' );

				$fs_google_b_share_as_product = ( $postType == 'product' || $postType == 'product_variation' ) && Helper::getOption( 'google_b_share_as_product', '1' );

				$productName     = $fs_google_b_share_as_product ? $postTitle : NULL;
				$productPrice    = $fs_google_b_share_as_product ? Helper::getProductPrice( $postInf, 'price' ) : NULL;
				$productCurrency = $fs_google_b_share_as_product ? get_woocommerce_currency() : NULL;
				$productCategory = NULL;

				if ( $fs_google_b_share_as_product )
				{
					$productCategory = wp_get_post_terms( $post_id, 'product_cat' );

					if ( isset( $productCategory[ 0 ] ) )
					{
						$productCategory = $productCategory[ 0 ]->name;
					}
					else
					{
						$productCategory = esc_html__( 'Product', 'fs-poster' );
					}
				}

				$google = new GoogleMyBusiness( $cookie_sid, $cookie_hsid, $cookie_ssid, $proxy );
				$res    = $google->sendPost( $nodeProfileId, $message, $link, $linkButton, $imageUrl, $productName, $productPrice, $productCurrency, $productCategory );
			}
		}
		else if ( $driver == 'telegram' )
		{
			$fs_telegram_type_of_sharing = Helper::getOption( 'telegram_type_of_sharing', '1' );

			if ( ( $fs_telegram_type_of_sharing == '1' || $fs_telegram_type_of_sharing == '4' ) && ! empty( $link ) )
			{
				$message .= "\n" . $link;
			}

			if ( ( $fs_telegram_type_of_sharing == '3' || $fs_telegram_type_of_sharing == '4' ) && $sendType != 'image' && $sendType != 'video' )
			{
				$thumbURL = WPPostThumbnail::getPostThumbnailURL( $post_id );

				if ( ! empty( $thumbURL ) )
				{
					$sendType = 'image';
					$images   = [ $thumbURL ];
				}
			}

			if ( $sendType == 'image' )
			{
				$mediaURL = reset( $images );
			}
			else if ( $sendType == 'video' )
			{
				$mediaURL = $videoURL;
			}
			else
			{
				$mediaURL = '';
			}

			$tg  = new Telegram( $options, $proxy );
			$res = $tg->sendPost( $nodeProfileId, $message, $sendType, $mediaURL );
		}
		else if ( $driver == 'medium' )
		{
			$res = Medium::sendPost( $nInf[ 'info' ], $postTitle, $message, $accessToken, $proxy );
		}
		else if ( $driver == 'wordpress' )
		{
			$thumbnailPath = WPPostThumbnail::getPostThumbnail( $post_id );

			$post_title_wordpress   = Helper::getOption( 'post_title_wordpress', "{title}" );
			$post_excerpt_wordpress = Helper::getOption( 'post_excerpt_wordpress', "{excerpt}" );

			$post_title_wordpress   = Helper::replaceTags( $post_title_wordpress, $postInf, $longLink, $shortLink );
			$post_excerpt_wordpress = Helper::replaceTags( $post_excerpt_wordpress, $postInf, $longLink, $shortLink );

			$wordpress = new Wordpress( $options, $nInf[ 'username' ], $nInf[ 'password' ], $proxy );
			$res       = $wordpress->sendPost( $postInf, $postType, $post_title_wordpress, $post_excerpt_wordpress, $message, $thumbnailPath );
		}
		else
		{
			$res = [
				'status'    => 'error',
				'error_msg' => 'Driver error! Driver type: ' . htmlspecialchars( $driver )
			];
		}

		WPPostThumbnail::clearCache();

		if ( ! Helper::getOption( 'keep_logs', '1' ) )
		{
			DB::DB()->delete( DB::table( 'feeds' ), [
				'id' => $feedId
			] );
		}
		else
		{
			$udpateDate = [
				'is_sended'       => 1,
				'send_time'       => Date::dateTimeSQL(),
				'status'          => $res[ 'status' ],
				'error_msg'       => isset( $res[ 'error_msg' ] ) ? Helper::cutText( $res[ 'error_msg' ], 250 ) : '',
				'driver_post_id'  => isset( $res[ 'id' ] ) ? $res[ 'id' ] : NULL,
				'driver_post_id2' => isset( $res[ 'id2' ] ) ? $res[ 'id2' ] : NULL
			];

			DB::DB()->update( DB::table( 'feeds' ), $udpateDate, [ 'id' => $feedId ] );
		}

		if ( isset( $res[ 'id' ] ) )
		{
			if ( $driver == 'google_b' )
			{
				$username = $nodeProfileId;
			}
			else if ( $driver == 'wordpress' )
			{
				$username = $options;
			}
			else
			{
				$username = isset( $nInf[ 'info' ][ 'screen_name' ] ) ? $nInf[ 'info' ][ 'screen_name' ] : $nInf[ 'username' ];
			}

			if ( ! isset( $res[ 'post_link' ] ) )
			{
				$res[ 'post_link' ] = Helper::postLink( $res[ 'id' ], $driver . ( $driver == 'instagram' ? $feedInf[ 'feed_type' ] : '' ), $username );
			}
		}

		return $res;
	}
}
