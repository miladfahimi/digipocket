<?php

namespace FSPoster\App\Pages\Schedules\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

trait Popup
{
	private function load_assets ()
	{
		wp_enqueue_script( 'fsp-logs' );

		wp_enqueue_style( 'fsp-logs', Pages::asset( 'Logs', 'css/fsp-logs.css' ), [ 'fsp-ui' ], NULL );
	}

	public function add_schedule ()
	{
		$not_include_js   = Request::post( 'not_include_js', 0, 'int' ) === 1;
		$account_andNodes = Request::post( 'nodes', NULL, 'array' );

		if ( ! is_null( $account_andNodes ) )
		{
			$account_ids = [];
			$node_ids    = [];
			foreach ( $account_andNodes as $accountNodeInf )
			{
				if ( empty( $accountNodeInf ) )
				{
					continue;
				}

				$accountNodeInf = explode( ':', $accountNodeInf );

				if ( ! isset( $accountNodeInf[ 1 ] ) )
				{
					continue;
				}

				if ( $accountNodeInf[ 0 ] === 'account' )
				{
					$account_ids[] = (int) $accountNodeInf[ 1 ];
				}
				else
				{
					$node_ids[] = (int) $accountNodeInf[ 1 ];
				}
			}

			$account_ids = implode( ',', $account_ids );
			$node_ids    = implode( ',', $node_ids );

			if ( ! empty( $account_ids ) )
			{
				$accounts = DB::DB()->get_results( DB::DB()->prepare( "
					SELECT 
						tb2.*, IFNULL(tb1.filter_type, 'no') AS filter_type, tb1.categories, (SELECT GROUP_CONCAT(`name`) FROM " . DB::WPtable( 'terms', TRUE ) . " WHERE FIND_IN_SET(term_id,tb1.categories) ) AS categories_name,'account' AS node_type 
					FROM " . DB::table( 'accounts' ) . " tb2
					LEFT JOIN " . DB::table( 'account_status' ) . " tb1 ON tb2.id=tb1.account_id AND tb1.user_id=%d
					WHERE (tb2.user_id=%d OR tb2.is_public=1) AND tb2.blog_id=%d AND tb2.id IN ({$account_ids})
					ORDER BY name", [ get_current_user_id(), get_current_user_id(), Helper::getBlogId() ] ), ARRAY_A );
			}
			else
			{
				$accounts = [];
			}

			if ( ! empty( $node_ids ) )
			{
				$active_nodes = DB::DB()->get_results( DB::DB()->prepare( "
				SELECT 
					tb2.*, IFNULL(tb1.filter_type, 'no') AS filter_type, tb1.categories, (SELECT GROUP_CONCAT(`name`) FROM " . DB::WPtable( 'terms', TRUE ) . " WHERE FIND_IN_SET(term_id,tb1.categories) ) AS categories_name 
				FROM " . DB::table( 'account_nodes' ) . " tb2
				LEFT JOIN " . DB::table( 'account_node_status' ) . " tb1 ON tb2.id=tb1.node_id AND tb1.user_id=%d
				WHERE (tb2.user_id=%d OR tb2.is_public=1) AND tb2.blog_id=%d AND tb2.id IN ({$node_ids})
				ORDER BY (CASE node_type WHEN 'ownpage' THEN 1 WHEN 'group' THEN 2 WHEN 'page' THEN 3 END), name", [
					get_current_user_id(),
					get_current_user_id(),
					Helper::getBlogId()
				] ), ARRAY_A );
			}
			else
			{
				$active_nodes = [];
			}
		}
		else
		{
			$accounts = DB::DB()->get_results( DB::DB()->prepare( "
				SELECT tb2.*, tb1.filter_type, tb1.categories, (SELECT GROUP_CONCAT(`name`) FROM " . DB::WPtable( 'terms', TRUE ) . " WHERE FIND_IN_SET(term_id,tb1.categories) ) AS categories_name,'account' AS node_type 
				FROM " . DB::table( 'account_status' ) . " tb1
				INNER JOIN " . DB::table( 'accounts' ) . " tb2 ON tb2.id=tb1.account_id
				WHERE tb1.user_id=%d AND tb2.blog_id=%d
				ORDER BY name", [ get_current_user_id(), Helper::getBlogId() ] ), ARRAY_A );

			$active_nodes = DB::DB()->get_results( DB::DB()->prepare( "
				SELECT tb2.*, tb1.filter_type, tb1.categories, (SELECT GROUP_CONCAT(`name`) FROM " . DB::WPtable( 'terms', TRUE ) . " WHERE FIND_IN_SET(term_id,tb1.categories) ) AS categories_name FROM " . DB::table( 'account_node_status' ) . " tb1
				LEFT JOIN " . DB::table( 'account_nodes' ) . " tb2 ON tb2.id=tb1.node_id
				WHERE tb1.user_id=%d AND tb2.blog_id=%d
				ORDER BY (CASE node_type WHEN 'ownpage' THEN 1 WHEN 'group' THEN 2 WHEN 'page' THEN 3 END), name", [
				get_current_user_id(),
				Helper::getBlogId()
			] ), ARRAY_A );
		}

		$active_nodes = array_merge( $accounts, $active_nodes );

		foreach ( $active_nodes as $aKey => $node_info )
		{
			if ( $node_info[ 'filter_type' ] === 'no' )
			{
				$titleText = '';
			}
			else
			{
				$titleText = ( $node_info[ 'filter_type' ] === 'in' ? esc_html__( 'Only the posts of the selected categories, tags, etc. will be shared:', 'fs-poster' ) : esc_html__( 'The posts of the selected categories, tags, etc. will not be shared:', 'fs-poster' ) ) . "\n";
				$titleText .= str_replace( ',', ', ', $node_info[ 'categories_name' ] );
			}

			$active_nodes[ $aKey ][ 'title_text' ] = $titleText;
		}

		$post_types       = [];
		$allowedPostTypes = explode( '|', Helper::getOption( 'allowed_post_types', 'post|page|attachment|product' ) );

		foreach ( get_post_types() as $post_type )
		{
			if ( ! in_array( $post_type, $allowedPostTypes ) )
			{
				continue;
			}

			$post_types[] = $post_type;
		}

		$postCategories    = get_terms( [ 'category' ], [ 'hide_empty' => FALSE ] );
		$productCategories = taxonomy_exists( 'product_cat' ) ? get_terms( [ 'product_cat' ], [ 'hide_empty' => FALSE ] ) : [];
		$customTaxonomies  = [];

		$getCustomTaxs = get_terms( [ 'hide_empty' => FALSE, 'orderby' => 'taxonomy' ] );

		foreach ( $getCustomTaxs as $categ )
		{
			if ( in_array( $categ->taxonomy, [ 'category', 'product_cat' ] ) )
			{
				continue;
			}

			if ( ! isset( $customTaxonomies[ $categ->taxonomy ] ) )
			{
				$customTaxonomies[ $categ->taxonomy ] = [];
			}

			$customTaxonomies[ $categ->taxonomy ][] = $categ;
		}

		$custom_messages = [
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
		$post_ids        = Request::post( 'post_ids', '', 'string' );
		$schedule_name   = '';
		$post_ids_count  = 0;

		if ( ! empty( $post_ids ) )
		{
			$post_ids       = explode( ',', $post_ids );
			$post_ids_count = count( $post_ids );

			if ( $post_ids_count == 1 )
			{
				$onePostId  = reset( $post_ids );
				$onePostInf = get_post( $onePostId, ARRAY_A );

				$schedule_name = 'Scheduled post: "' . Helper::cutText( ! empty( $onePostInf[ 'post_title' ] ) ? $onePostInf[ 'post_title' ] : $onePostInf[ 'post_content' ] ) . '"';
			}
			else
			{
				$schedule_name = 'Schedule ( ' . $post_ids_count . ' posts )';
			}

			$post_ids = implode( ',', $post_ids );
		}

		Pages::modal( 'Schedules', 'add', [
			'parameters' => [
				'not_include_js'    => $not_include_js,
				'name'              => $schedule_name,
				'activeNodes'       => $active_nodes,
				'postTypes'         => $post_types,
				'postCategories'    => $postCategories,
				'productCategories' => $productCategories,
				'customTaxonomies'  => $customTaxonomies,
				'customMessages'    => $custom_messages,
				'title'             => esc_html__( 'ADD A NEW SCHEDULE', 'fs-poster' ),
				'btn_title'         => esc_html__( 'ADD A SCHEDULE', 'fs-poster' ),
				'post_ids'          => $post_ids,
				'post_ids_count'    => $post_ids_count
			]
		] );
	}

	public function edit_schedule ()
	{
		$scheduleId = Request::post( 'schedule_id', 0, 'int' );

		$schedule_info = DB::fetch( 'schedules', $scheduleId );

		if ( ! $schedule_info )
		{
			Helper::response( FALSE, esc_html__( 'There isn\'t a schedule.', 'fs-poster' ) );
		}

		if ( $schedule_info[ 'interval' ] % 1440 === 0 )
		{
			$schedule_info[ 'interval' ]      = $schedule_info[ 'interval' ] / 1440;
			$schedule_info[ 'interval_type' ] = 1440;
		}
		else if ( $schedule_info[ 'interval' ] % 60 === 0 )
		{
			$schedule_info[ 'interval' ]      = $schedule_info[ 'interval' ] / 60;
			$schedule_info[ 'interval_type' ] = 60;
		}
		else
		{
			$schedule_info[ 'interval_type' ] = 1;
		}

		$account_andNodes = explode( ',', $schedule_info[ 'share_on_accounts' ] );
		$account_ids      = [];
		$node_ids         = [];
		foreach ( $account_andNodes as $accountNodeInf )
		{
			if ( empty( $accountNodeInf ) )
			{
				continue;
			}

			$accountNodeInf = explode( ':', $accountNodeInf );

			if ( ! isset( $accountNodeInf[ 1 ] ) )
			{
				continue;
			}

			if ( $accountNodeInf[ 0 ] === 'account' )
			{
				$account_ids[] = (int) $accountNodeInf[ 1 ];
			}
			else
			{
				$node_ids[] = (int) $accountNodeInf[ 1 ];
			}
		}

		$account_ids = implode( ',', $account_ids );
		$node_ids    = implode( ',', $node_ids );

		if ( ! empty( $account_ids ) )
		{
			$accounts = DB::DB()->get_results( DB::DB()->prepare( "
				SELECT 
					tb2.*, IFNULL(tb1.filter_type, 'no') AS filter_type, tb1.categories, (SELECT GROUP_CONCAT(`name`) FROM " . DB::WPtable( 'terms', TRUE ) . " WHERE FIND_IN_SET(term_id,tb1.categories) ) AS categories_name,'account' AS node_type 
				FROM " . DB::table( 'accounts' ) . " tb2
				LEFT JOIN " . DB::table( 'account_status' ) . " tb1 ON tb2.id=tb1.account_id AND tb1.user_id=%d
				WHERE (tb2.user_id=%d OR is_public=1) AND tb2.blog_id=%d AND tb2.id IN ({$account_ids})
				ORDER BY name", [ get_current_user_id(), get_current_user_id(), Helper::getBlogId() ] ), ARRAY_A );
		}
		else
		{
			$accounts = [];
		}

		if ( ! empty( $node_ids ) )
		{
			$active_nodes = DB::DB()->get_results( DB::DB()->prepare( "
				SELECT 
					tb2.*, IFNULL(tb1.filter_type, 'no') AS filter_type, tb1.categories, (SELECT GROUP_CONCAT(`name`) FROM " . DB::WPtable( 'terms', TRUE ) . " WHERE FIND_IN_SET(term_id,tb1.categories) ) AS categories_name 
				FROM " . DB::table( 'account_nodes' ) . " tb2
				LEFT JOIN " . DB::table( 'account_node_status' ) . " tb1 ON tb2.id=tb1.node_id AND tb1.user_id=%d
				WHERE (tb2.user_id=%d OR tb2.is_public=1) AND tb2.blog_id=%d AND tb2.id IN ({$node_ids})
				ORDER BY (CASE node_type WHEN 'ownpage' THEN 1 WHEN 'group' THEN 2 WHEN 'page' THEN 3 END), name", [
				get_current_user_id(),
				get_current_user_id(),
				Helper::getBlogId()
			] ), ARRAY_A );
		}
		else
		{
			$active_nodes = [];
		}

		$active_nodes = array_merge( $accounts, $active_nodes );
		foreach ( $active_nodes as $aKey => $active_node )
		{
			if ( $active_node[ 'filter_type' ] === 'no' )
			{
				$titleText = '';
			}
			else
			{
				$titleText = ( $active_node[ 'filter_type' ] === 'in' ? esc_html__( 'Only the posts of the selected categories, tags, etc. will be shared:', 'fs-poster' ) : esc_html__( 'The posts of the selected categories, tags, etc. will not be shared:', 'fs-poster' ) ) . "\n";
				$titleText .= str_replace( ',', ', ', $active_node[ 'categories_name' ] );
			}

			$active_nodes[ $aKey ][ 'title_text' ] = $titleText;
		}

		/*
		 * Fetch all Custom Post types...
		 */
		$post_types       = [];
		$allowedPostTypes = explode( '|', Helper::getOption( 'allowed_post_types', 'post|page|attachment|product' ) );
		foreach ( get_post_types() as $post_type )
		{
			if ( ! in_array( $post_type, $allowedPostTypes ) )
			{
				continue;
			}

			$post_types[] = $post_type;
		}

		$postCategories    = get_terms( [ 'category' ], [ 'hide_empty' => FALSE ] );
		$productCategories = taxonomy_exists( 'product_cat' ) ? get_terms( [ 'product_cat' ], [ 'hide_empty' => FALSE ] ) : [];
		$customTaxonomies  = [];

		$getCustomTaxs = get_terms( [ 'hide_empty' => FALSE, 'orderby' => 'taxonomy' ] );
		foreach ( $getCustomTaxs as $categ )
		{
			if ( in_array( $categ->taxonomy, [ 'category', 'product_cat' ] ) )
			{
				continue;
			}

			if ( ! isset( $customTaxonomies[ $categ->taxonomy ] ) )
			{
				$customTaxonomies[ $categ->taxonomy ] = [];
			}

			$customTaxonomies[ $categ->taxonomy ][] = $categ;
		}

		$default_custom_messages = [
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
			'medium'      => Helper::getOption( 'post_text_message_medium', "{title}" )
		];

		$custom_messages = array_merge( $default_custom_messages, json_decode( $schedule_info[ 'custom_post_message' ], TRUE ) );

		if ( $schedule_info[ 'status' ] === 'finished' )
		{
			$schedule_info[ 'title' ] = 'Re: ' . $schedule_info[ 'title' ];
		}

		$post_ids_count = empty( $schedule_info[ 'save_post_ids' ] ) ? 0 : count( explode( ',', $schedule_info[ 'save_post_ids' ] ) );

		Pages::modal( 'Schedules', 'add', [
			'parameters' => [
				'id'                => $scheduleId,
				'info'              => $schedule_info,
				'activeNodes'       => $active_nodes,
				'postTypes'         => $post_types,
				'postCategories'    => $postCategories,
				'productCategories' => $productCategories,
				'customTaxonomies'  => $customTaxonomies,
				'customMessages'    => $custom_messages,
				'title'             => $schedule_info[ 'status' ] === 'finished' ? esc_html__( 'RE-SCHEDULE', 'fs-poster' ) : esc_html__( 'EDIT SCHEDULE', 'fs-poster' ),
				'btn_title'         => $schedule_info[ 'status' ] === 'finished' ? esc_html__( 'RE-SCHEDULE', 'fs-poster' ) : esc_html__( 'SAVE THE SCHEDULE', 'fs-poster' ),
				'post_ids_count'    => $post_ids_count
			]
		] );
	}

	public function posts_list ()
	{
		$this->load_assets();
		$data = Pages::action( 'Logs', 'get_logs' );

		Pages::modal( 'Schedules', 'posts_list', $data );
	}

	public function edit_wp_native_schedule ()
	{
		$post_id = Request::post( 'post_id', 0, 'int' );

		if ( $post_id > 0 )
		{
			$feeds = DB::fetchAll( 'feeds', [
				'post_id'   => $post_id,
				'is_sended' => 0,
				'blog_id'   => Helper::getBlogId()
			] );

			$info               = [
				'post_id'   => $post_id,
				'send_time' => NULL
			];
			$account_ids        = [];
			$node_ids           = [];
			$customPostMessages = [];

			if ( $feeds )
			{
				foreach ( $feeds as $feed )
				{
					if ( is_null( $info[ 'send_time' ] ) )
					{
						$info[ 'send_time' ] = $feed[ 'send_time' ];
					}

					if ( $feed[ 'node_type' ] === 'account' )
					{
						$account_ids[] = $feed[ 'node_id' ];
					}
					else
					{
						$node_ids[] = $feed[ 'node_id' ];
					}

					if ( ! array_key_exists( $feed[ 'driver' ], $customPostMessages ) )
					{
						$customPostMessages[ $feed[ 'driver' ] ] = $feed[ 'custom_post_message' ];
					}
				}

				if ( is_null( $info[ 'send_time' ] ) )
				{
					Helper::response( FALSE, esc_html__( 'There isn\'t a schedule.', 'fs-poster' ) );
				}
			}
			else
			{
				$post = get_post( $post_id );

				if ( $post )
				{
					$info[ 'send_time' ] = Date::dateTimeSQL( $post->post_date, '+1 minute' );
				}
				else
				{
					Helper::response( FALSE, esc_html__( 'There isn\'t a schedule.', 'fs-poster' ) );
				}
			}

			$account_ids = implode( ',', $account_ids );
			$node_ids    = implode( ',', $node_ids );

			if ( ! empty( $account_ids ) )
			{
				$accounts = DB::DB()->get_results( DB::DB()->prepare( "
				SELECT 
					tb2.*, IFNULL(tb1.filter_type, 'no') AS filter_type, tb1.categories, (SELECT GROUP_CONCAT(`name`) FROM " . DB::WPtable( 'terms', TRUE ) . " WHERE FIND_IN_SET(term_id,tb1.categories) ) AS categories_name,'account' AS node_type 
				FROM " . DB::table( 'accounts' ) . " tb2
				LEFT JOIN " . DB::table( 'account_status' ) . " tb1 ON tb2.id=tb1.account_id AND tb1.user_id=%d
				WHERE (tb2.user_id=%d OR is_public=1) AND tb2.blog_id=%d AND tb2.id IN ({$account_ids})
				ORDER BY name", [ get_current_user_id(), get_current_user_id(), Helper::getBlogId() ] ), ARRAY_A );
			}
			else
			{
				$accounts = [];
			}

			if ( ! empty( $node_ids ) )
			{
				$active_nodes = DB::DB()->get_results( DB::DB()->prepare( "
				SELECT 
					tb2.*, IFNULL(tb1.filter_type, 'no') AS filter_type, tb1.categories, (SELECT GROUP_CONCAT(`name`) FROM " . DB::WPtable( 'terms', TRUE ) . " WHERE FIND_IN_SET(term_id,tb1.categories) ) AS categories_name 
				FROM " . DB::table( 'account_nodes' ) . " tb2
				LEFT JOIN " . DB::table( 'account_node_status' ) . " tb1 ON tb2.id=tb1.node_id AND tb1.user_id=%d
				WHERE (tb2.user_id=%d OR tb2.is_public=1) AND tb2.blog_id=%d AND tb2.id IN ({$node_ids})
				ORDER BY (CASE node_type WHEN 'ownpage' THEN 1 WHEN 'group' THEN 2 WHEN 'page' THEN 3 END), name", [
					get_current_user_id(),
					get_current_user_id(),
					Helper::getBlogId()
				] ), ARRAY_A );
			}
			else
			{
				$active_nodes = [];
			}

			$active_nodes = array_merge( $accounts, $active_nodes );

			foreach ( $active_nodes as $aKey => $active_node )
			{
				if ( $active_node[ 'filter_type' ] === 'no' )
				{
					$titleText = '';
				}
				else
				{
					$titleText = ( $active_node[ 'filter_type' ] === 'in' ? esc_html__( 'Only the posts of the selected categories, tags, etc. will be shared:', 'fs-poster' ) : esc_html__( 'The posts of the selected categories, tags, etc. will not be shared:', 'fs-poster' ) ) . "\n";
					$titleText .= str_replace( ',', ', ', $active_node[ 'categories_name' ] );
				}

				$active_nodes[ $aKey ][ 'title_text' ] = $titleText;
			}

			$customPostMessages      = array_filter( $customPostMessages, function ( $message ) {
				return ! is_null( $message ) && ! empty( $message );
			} );
			$default_custom_messages = [
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
				'medium'      => Helper::getOption( 'post_text_message_medium', "{title}" )
			];
			$custom_messages         = array_merge( $default_custom_messages, $customPostMessages );

			Pages::modal( 'Schedules', 'add', [
				'is_native'  => TRUE,
				'parameters' => [
					'info'           => $info,
					'activeNodes'    => $active_nodes,
					'customMessages' => $custom_messages,
					'title'          => esc_html__( 'EDIT SCHEDULE', 'fs-poster' ),
					'btn_title'      => esc_html__( 'SAVE CHANGES', 'fs-poster' ),
					'post_ids_count' => 1
				]
			] );
		}

		Helper::response( FALSE, esc_html__( 'There isn\'t a schedule.', 'fs-poster' ) );
	}
}