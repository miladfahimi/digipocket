<?php

namespace FSPoster\App\Pages\Settings\Controllers;

use Exception;
use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

trait Ajax
{
	private function isAdmin ()
	{
		if ( ! current_user_can( 'administrator' ) )
		{
			exit();
		}
	}

	public function settings_general_save ()
	{
		$this->isAdmin();

		$fs_show_fs_poster_column  = Request::post( 'fs_show_fs_poster_column', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_check_accounts         = Request::post( 'fs_check_accounts', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_check_accounts_disable = Request::post( 'fs_check_accounts_disable', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_allowed_post_types     = Request::post( 'fs_allowed_post_types', [
			'post',
			'attachment',
			'page',
			'product'
		], 'array' );
		$fs_collect_statistics     = Request::post( 'fs_collect_statistics', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;

		$new_arrPostTypes = [];
		$allTypes         = get_post_types();
		foreach ( $fs_allowed_post_types as $fs_aPT )
		{
			if ( is_string( $fs_aPT ) && in_array( $fs_aPT, $allTypes ) )
			{
				$new_arrPostTypes[] = $fs_aPT;
			}
		}
		$new_arrPostTypes = implode( '|', $new_arrPostTypes );

		$fs_hide_for_roles   = Request::post( 'fs_hide_for_roles', [], 'array' );
		$new_arrHideForRoles = [];
		$allRoles            = get_editable_roles();
		foreach ( $fs_hide_for_roles as $fs_aPT )
		{
			if ( $fs_aPT != 'administrator' && is_string( $fs_aPT ) && isset( $allRoles[ $fs_aPT ] ) )
			{
				$new_arrHideForRoles[] = $fs_aPT;
			}
		}
		$new_arrHideForRoles = implode( '|', $new_arrHideForRoles );

		Helper::setOption( 'show_fs_poster_column', (string) $fs_show_fs_poster_column );
		Helper::setOption( 'check_accounts', (string) $fs_check_accounts );
		Helper::setOption( 'check_accounts_disable', (string) $fs_check_accounts_disable );
		Helper::setOption( 'allowed_post_types', $new_arrPostTypes );
		Helper::setOption( 'hide_menu_for', $new_arrHideForRoles );
		Helper::setOption( 'collect_statistics', (string) $fs_collect_statistics );

		Helper::response( TRUE );
	}

	public function settings_share_save ()
	{
		$this->isAdmin();

		$fs_auto_share_new_posts                = Request::post( 'fs_auto_share_new_posts', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_share_on_background                 = Request::post( 'fs_share_on_background', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_share_timer                         = Request::post( 'fs_share_timer', '0', 'integer' );
		$fs_keep_logs                           = Request::post( 'fs_keep_logs', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_post_interval                       = Request::post( 'fs_post_interval', '0', 'integer' );
		$fs_post_interval_type                  = Request::post( 'fs_post_interval_type', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_replace_whitespaces_with_underscore = Request::post( 'fs_replace_whitespaces_with_underscore', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_replace_wp_shortcodes               = Request::post( 'fs_replace_wp_shortcodes', 'off', 'string', [
			'off',
			'on',
			'del'
		] );

		Helper::setOption( 'auto_share_new_posts', (string) $fs_auto_share_new_posts );
		Helper::setOption( 'share_on_background', (string) $fs_share_on_background );
		Helper::setOption( 'share_timer', $fs_share_timer );
		Helper::setOption( 'keep_logs', (string) $fs_keep_logs );
		Helper::setOption( 'post_interval', (string) $fs_post_interval );
		Helper::setOption( 'post_interval_type', (string) $fs_post_interval_type );
		Helper::setOption( 'replace_whitespaces_with_underscore', (string) $fs_replace_whitespaces_with_underscore );
		Helper::setOption( 'replace_wp_shortcodes', (string) $fs_replace_wp_shortcodes );

		Helper::response( TRUE );
	}

	public function settings_url_save ()
	{
		$this->isAdmin();

		$fs_unique_link = Request::post( 'fs_unique_link', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;

		$fs_url_shortener                = Request::post( 'fs_url_shortener', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_shortener_service            = Request::post( 'fs_shortener_service', 0, 'string', [ 'tinyurl', 'bitly' ] );
		$fs_url_short_access_token_bitly = Request::post( 'fs_url_short_access_token_bitly', '', 'string' );
		$fs_url_additional               = Request::post( 'fs_url_additional', '', 'string' );

		$fs_share_custom_url    = Request::post( 'fs_share_custom_url', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_custom_url_to_share = Request::post( 'fs_custom_url_to_share', '', 'string' );

		Helper::setOption( 'unique_link', (string) $fs_unique_link );
		Helper::setOption( 'url_shortener', (string) $fs_url_shortener );
		Helper::setOption( 'shortener_service', $fs_shortener_service );
		Helper::setOption( 'url_short_access_token_bitly', $fs_url_short_access_token_bitly );
		Helper::setOption( 'url_additional', $fs_url_additional );

		Helper::setOption( 'share_custom_url', (string) $fs_share_custom_url );
		Helper::setOption( 'custom_url_to_share', $fs_custom_url_to_share );

		Helper::response( TRUE );
	}

	public function settings_facebook_save ()
	{
		$this->isAdmin();

		$fs_load_own_pages = Request::post( 'fs_load_own_pages', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_load_groups    = Request::post( 'fs_load_groups', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;

		$fs_max_groups_limit = Request::post( 'fs_max_groups_limit', '50', 'num' );

		if ( $fs_max_groups_limit > 1000 )
		{
			$fs_max_groups_limit = 1000;
		}

		$fs_post_text_message_fb  = Request::post( 'fs_post_text_message_fb', '', 'string' );
		$fs_facebook_posting_type = Request::post( 'fs_facebook_posting_type', '1', 'num', [ '1', '2', '3' ] );

		Helper::setOption( 'post_text_message_fb', $fs_post_text_message_fb );

		Helper::setOption( 'load_own_pages', (string) $fs_load_own_pages );

		Helper::setOption( 'load_groups', (string) $fs_load_groups );

		Helper::setOption( 'facebook_posting_type', $fs_facebook_posting_type );

		Helper::response( TRUE );
	}

	public function settings_instagram_save ()
	{
		$this->isAdmin();

		$fs_instagram_autocut_text  = Request::post( 'fs_instagram_autocut_text', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_instagram_post_in_type  = Request::post( 'fs_instagram_post_in_type', 0, 'int', [ 1, 2, 3 ] );
		$fs_instagram_story_link    = Request::post( 'fs_instagram_story_link', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_instagram_story_hashtag = Request::post( 'fs_instagram_story_hashtag', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;

		$fs_instagram_story_hashtag_name     = Request::post( 'fs_instagram_story_hashtag_name', '', 'string' );
		$fs_instagram_story_hashtag_position = Request::post( 'fs_instagram_story_hashtag_position', 'top', 'string', [
			'top',
			'bottom'
		] );

		if ( $fs_instagram_story_hashtag && empty( $fs_instagram_story_hashtag_name ) )
		{
			Helper::response( FALSE, esc_html__( 'Plase type the hashtag', 'fs-poster' ) );
		}

		$fs_post_text_message_instagram   = Request::post( 'fs_post_text_message_instagram', '', 'string' );
		$fs_post_text_message_instagram_h = Request::post( 'fs_post_text_message_instagram_h', '', 'string' );

		$fs_instagram_story_background               = Request::post( 'fs_instagram_story_background', '', 'string' );
		$fs_instagram_story_title_background         = Request::post( 'fs_instagram_story_title_background', '', 'string' );
		$fs_instagram_story_title_background_opacity = Request::post( 'fs_instagram_story_title_background_opacity', '', 'int' );
		$fs_instagram_story_title_color              = Request::post( 'fs_instagram_story_title_color', '', 'string' );
		$fs_instagram_story_title_top                = Request::post( 'fs_instagram_story_title_top', '', 'string' );
		$fs_instagram_story_title_left               = Request::post( 'fs_instagram_story_title_left', '', 'string' );
		$fs_instagram_story_title_width              = Request::post( 'fs_instagram_story_title_width', '', 'string' );
		$fs_instagram_story_title_font_size          = Request::post( 'fs_instagram_story_title_font_size', '', 'string' );
		$fs_instagram_story_title_rtl                = Request::post( 'fs_instagram_story_title_rtl', 'off', 'string', [
			'on',
			'off'
		] );

		Helper::setOption( 'instagram_autocut_text', $fs_instagram_autocut_text );
		Helper::setOption( 'post_text_message_instagram', $fs_post_text_message_instagram );
		Helper::setOption( 'post_text_message_instagram_h', $fs_post_text_message_instagram_h );

		Helper::setOption( 'instagram_post_in_type', $fs_instagram_post_in_type );
		Helper::setOption( 'instagram_story_link', (string) $fs_instagram_story_link );
		Helper::setOption( 'instagram_story_hashtag', (string) $fs_instagram_story_hashtag );

		Helper::setOption( 'instagram_story_hashtag_name', $fs_instagram_story_hashtag ? $fs_instagram_story_hashtag_name : '' );
		Helper::setOption( 'instagram_story_hashtag_position', $fs_instagram_story_hashtag ? $fs_instagram_story_hashtag_position : '' );

		Helper::setOption( 'instagram_story_background', $fs_instagram_story_background );
		Helper::setOption( 'instagram_story_title_background', $fs_instagram_story_title_background );
		Helper::setOption( 'instagram_story_title_background_opacity', ( $fs_instagram_story_title_background_opacity > 100 || $fs_instagram_story_title_background_opacity < 0 ? 30 : $fs_instagram_story_title_background_opacity ) );
		Helper::setOption( 'instagram_story_title_color', $fs_instagram_story_title_color );
		Helper::setOption( 'instagram_story_title_top', $fs_instagram_story_title_top );
		Helper::setOption( 'instagram_story_title_left', $fs_instagram_story_title_left );
		Helper::setOption( 'instagram_story_title_width', $fs_instagram_story_title_width );
		Helper::setOption( 'instagram_story_title_font_size', $fs_instagram_story_title_font_size );
		Helper::setOption( 'instagram_story_title_rtl', $fs_instagram_story_title_rtl );

		Helper::response( TRUE );
	}

	public function settings_vk_save ()
	{
		$this->isAdmin();

		$fs_vk_load_admin_communities   = Request::post( 'fs_vk_load_admin_communities', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_vk_load_members_communities = Request::post( 'fs_vk_load_members_communities', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_vk_upload_image             = Request::post( 'fs_vk_upload_image', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;

		$fs_vk_max_communities_limit = Request::post( 'fs_vk_max_communities_limit', '50', 'num' );

		if ( $fs_vk_max_communities_limit > 1000 )
		{
			$fs_vk_max_communities_limit = 1000;
		}

		$fs_post_text_message_vk = Request::post( 'fs_post_text_message_vk', '', 'string' );

		Helper::setOption( 'post_text_message_vk', $fs_post_text_message_vk );

		Helper::setOption( 'vk_load_admin_communities', (string) $fs_vk_load_admin_communities );
		Helper::setOption( 'vk_load_members_communities', (string) $fs_vk_load_members_communities );

		Helper::setOption( 'vk_max_communities_limit', $fs_vk_max_communities_limit );
		Helper::setOption( 'vk_upload_image', $fs_vk_upload_image );

		Helper::response( TRUE );
	}

	public function settings_twitter_save ()
	{
		$this->isAdmin();

		$fs_post_text_message_twitter = Request::post( 'fs_post_text_message_twitter', '', 'string' );
		$fs_twitter_auto_cut_tweets   = Request::post( 'fs_twitter_auto_cut_tweets', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_twitter_posting_type      = Request::post( 'fs_twitter_posting_type', '1', 'num', [ '1', '2', '3' ] );

		Helper::setOption( 'post_text_message_twitter', $fs_post_text_message_twitter );
		Helper::setOption( 'twitter_auto_cut_tweets', $fs_twitter_auto_cut_tweets );
		Helper::setOption( 'twitter_posting_type', $fs_twitter_posting_type );

		Helper::response( TRUE );
	}

	public function settings_linkedin_save ()
	{
		$this->isAdmin();

		$fs_linkedin_autocut_text      = Request::post( 'fs_linkedin_autocut_text', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_post_text_message_linkedin = Request::post( 'fs_post_text_message_linkedin', '', 'string' );
		$fs_linkedin_posting_type      = Request::post( 'fs_linkedin_posting_type', '1', 'num', [ '1', '2', '3' ] );

		Helper::setOption( 'linkedin_autocut_text', $fs_linkedin_autocut_text );
		Helper::setOption( 'post_text_message_linkedin', $fs_post_text_message_linkedin );
		Helper::setOption( 'linkedin_posting_type', $fs_linkedin_posting_type );

		Helper::response( TRUE );
	}

	public function settings_pinterest_save ()
	{
		$this->isAdmin();

		$fs_pinterest_autocut_title     = Request::post( 'fs_pinterest_autocut_title', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_post_text_message_pinterest = Request::post( 'fs_post_text_message_pinterest', '', 'string' );

		Helper::setOption( 'pinterest_autocut_title', $fs_pinterest_autocut_title );
		Helper::setOption( 'post_text_message_pinterest', $fs_post_text_message_pinterest );

		Helper::response( TRUE );
	}

	public function settings_google_b_save ()
	{
		$this->isAdmin();

		$fs_post_text_message_google_b = Request::post( 'fs_post_text_message_google_b', '', 'string' );
		$fs_google_b_share_as_product  = Request::post( 'fs_google_b_share_as_product', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_google_b_button_type       = Request::post( 'fs_google_b_button_type', 'LEARN_MORE', 'string', [
			'BOOK',
			'ORDER',
			'SHOP',
			'SIGN_UP',
			'WATCH_VIDEO',
			'RESERVE',
			'GET_OFFER',
			'CALL',
			'-'
		] );

		Helper::setOption( 'post_text_message_google_b', $fs_post_text_message_google_b );
		Helper::setOption( 'google_b_share_as_product', $fs_google_b_share_as_product );
		Helper::setOption( 'google_b_button_type', $fs_google_b_button_type );

		Helper::response( TRUE );
	}

	public function settings_tumblr_save ()
	{
		$this->isAdmin();

		$fs_post_text_message_tumblr = Request::post( 'fs_post_text_message_tumblr', '', 'string' );

		Helper::setOption( 'post_text_message_tumblr', $fs_post_text_message_tumblr );

		Helper::response( TRUE );
	}

	public function settings_reddit_save ()
	{
		$this->isAdmin();

		$fs_reddit_autocut_titles    = Request::post( 'fs_reddit_autocut_titles', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_post_text_message_reddit = Request::post( 'fs_post_text_message_reddit', '', 'string' );

		Helper::setOption( 'reddit_autocut_titles', $fs_reddit_autocut_titles );
		Helper::setOption( 'post_text_message_reddit', $fs_post_text_message_reddit );

		Helper::response( TRUE );
	}

	public function settings_ok_save ()
	{
		$this->isAdmin();

		$fs_post_text_message_ok = Request::post( 'fs_post_text_message_ok', '', 'string' );
		$fs_ok_posting_type      = Request::post( 'fs_ok_posting_type', '1', 'num', [ '1', '2', '3' ] );

		Helper::setOption( 'post_text_message_ok', $fs_post_text_message_ok );
		Helper::setOption( 'ok_posting_type', $fs_ok_posting_type );

		Helper::response( TRUE );
	}

	public function settings_telegram_save ()
	{
		$this->isAdmin();

		$fs_telegram_autocut_text      = Request::post( 'fs_telegram_autocut_text', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_post_text_message_telegram = Request::post( 'fs_post_text_message_telegram', '', 'string' );
		$fs_telegram_type_of_sharing   = Request::post( 'fs_telegram_type_of_sharing', '1', 'int', [
			'1',
			'2',
			'3',
			'4'
		] );

		Helper::setOption( 'telegram_autocut_text', $fs_telegram_autocut_text );
		Helper::setOption( 'post_text_message_telegram', $fs_post_text_message_telegram );
		Helper::setOption( 'telegram_type_of_sharing', $fs_telegram_type_of_sharing );

		Helper::response( TRUE );
	}

	public function settings_medium_save ()
	{
		$this->isAdmin();

		$fs_post_text_message_medium = Request::post( 'fs_post_text_message_medium', '', 'string' );

		Helper::setOption( 'post_text_message_medium', $fs_post_text_message_medium );

		Helper::response( TRUE );
	}

	public function settings_wordpress_save ()
	{
		$this->isAdmin();

		$fs_wordpress_posting_type         = Request::post( 'fs_wordpress_posting_type', 1, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_post_title_wordpress           = Request::post( 'fs_post_title_wordpress', '', 'string' );
		$fs_post_text_message_wordpress    = Request::post( 'fs_post_text_message_wordpress', '', 'string' );
		$fs_post_excerpt_wordpress         = Request::post( 'fs_post_excerpt_wordpress', '', 'string' );
		$fs_wordpress_post_with_categories = Request::post( 'fs_wordpress_post_with_categories', 0, 'string', [ 'on' ] ) !== 'on' ? 0 : 1;
		$fs_wordpress_post_with_tags       = Request::post( 'fs_wordpress_post_with_tags', 0, 'string', [ 'on' ] ) !== 'on' ? 0 : 1;
		$fs_wordpress_post_status          = Request::post( 'fs_wordpress_post_status', 'publish', 'string', [
			'publish',
			'private',
			'draft',
			'pending'
		] );

		Helper::setOption( 'post_title_wordpress', ( string ) $fs_post_title_wordpress );
		Helper::setOption( 'post_text_message_wordpress', ( string ) $fs_post_text_message_wordpress );
		Helper::setOption( 'post_excerpt_wordpress', ( string ) $fs_post_excerpt_wordpress );
		Helper::setOption( 'wordpress_post_with_categories', ( string ) $fs_wordpress_post_with_categories );
		Helper::setOption( 'wordpress_post_with_tags', ( string ) $fs_wordpress_post_with_tags );
		Helper::setOption( 'wordpress_posting_type', ( string ) $fs_wordpress_posting_type );
		Helper::setOption( 'wordpress_post_status', ( string ) $fs_wordpress_post_status );

		Helper::response( TRUE );
	}

	public function settings_export_save ()
	{
		$this->isAdmin();

		$fs_export_multisite         = Request::post( 'fs_export_multisite', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_export_accounts          = Request::post( 'fs_export_accounts', 1, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_export_failed_accounts   = Request::post( 'fs_export_failed_accounts', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_export_accounts_statuses = Request::post( 'fs_export_accounts_statuses', 1, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_export_apps              = Request::post( 'fs_export_apps', 1, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_export_logs              = Request::post( 'fs_export_logs', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_export_schedules         = Request::post( 'fs_export_schedules', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_export_settings          = Request::post( 'fs_export_settings', 1, 'string', [ 'on' ] ) === 'on' ? 1 : 0;

		Helper::setOption( 'export_multisite', (string) $fs_export_multisite );
		Helper::setOption( 'export_accounts', (string) $fs_export_accounts );
		Helper::setOption( 'export_failed_accounts', (string) $fs_export_failed_accounts );
		Helper::setOption( 'export_accounts_statuses', (string) $fs_export_accounts_statuses );
		Helper::setOption( 'export_apps', (string) $fs_export_apps );
		Helper::setOption( 'export_logs', (string) $fs_export_logs );
		Helper::setOption( 'export_schedules', (string) $fs_export_schedules );
		Helper::setOption( 'export_settings', (string) $fs_export_settings );

		$settings         = [];
		$export_multisite = '';

		if ( ! $fs_export_multisite )
		{
			$export_multisite = 'AND `blog_id` = ' . Helper::getBlogId();
		}

		if ( $fs_export_accounts )
		{
			$settings[ 'accounts' ] = DB::DB()->get_results( 'SELECT * FROM `' . DB::table( 'accounts' ) . '` WHERE 1 = 1 ' . $export_multisite . ' ' . ( $fs_export_failed_accounts ? '' : 'AND ( `status` IS NULL OR `status` != "error" )' ), ARRAY_A );

			$account_ids                         = array_map( function ( $acc ) {
				return $acc[ 'id' ];
			}, $settings[ 'accounts' ] );
			$settings[ 'account_access_tokens' ] = count( $account_ids ) > 0 ? DB::DB()->get_results( 'SELECT * FROM `' . DB::table( 'account_access_tokens' ) . '` WHERE `account_id` IN (' . implode( ',', $account_ids ) . ')', ARRAY_A ) : [];
			$settings[ 'account_nodes' ]         = count( $account_ids ) > 0 ? DB::DB()->get_results( 'SELECT * FROM `' . DB::table( 'account_nodes' ) . '` WHERE `account_id` IN (' . implode( ',', $account_ids ) . ')', ARRAY_A ) : [];

			if ( $fs_export_accounts_statuses )
			{
				$settings[ 'account_status' ] = count( $account_ids ) > 0 ? DB::DB()->get_results( 'SELECT * FROM `' . DB::table( 'account_status' ) . '` WHERE `account_id` IN (' . implode( ',', $account_ids ) . ')', ARRAY_A ) : [];

				$node_ids = array_map( function ( $acc ) {
					return $acc[ 'id' ];
				}, $settings[ 'account_nodes' ] );

				$settings[ 'account_node_status' ] = count( $node_ids ) > 0 ? DB::DB()->get_results( 'SELECT * FROM `' . DB::table( 'account_node_status' ) . '` WHERE `node_id` IN (' . implode( ',', $node_ids ) . ')', ARRAY_A ) : [];
			}
		}

		if ( $fs_export_apps )
		{
			$settings[ 'apps' ] = DB::DB()->get_results( 'SELECT * FROM `' . DB::table( 'apps' ) . '`', ARRAY_A );
		}

		if ( $fs_export_logs )
		{
			$settings[ 'feeds' ] = DB::DB()->get_results( 'SELECT * FROM `' . DB::table( 'feeds' ) . '` WHERE 1 = 1 ' . $export_multisite, ARRAY_A );

			if ( $fs_export_schedules )
			{
				$settings[ 'schedules' ] = DB::DB()->get_results( 'SELECT * FROM `' . DB::table( 'schedules' ) . '` WHERE 1 = 1 ' . $export_multisite, ARRAY_A );
			}
		}

		if ( $fs_export_settings )
		{
			$settings[ 'options' ] = DB::DB()->get_results( 'SELECT `option_name`, `option_value`, `autoload` FROM `' . DB::DB()->base_prefix . 'options` WHERE `option_name` LIKE "fs_%" AND `option_name` NOT IN ( "fs_poster_plugin_purchase_key", "fs_poster_plugin_installed" )', ARRAY_A );
		}

		$file_id = wp_generate_password( 8, FALSE );

		Helper::setOption( 'exported_json_' . $file_id, json_encode( $settings ) );
		Helper::response( TRUE, [ 'file_id' => $file_id ] );
	}

	public function settings_import_save ()
	{
		$this->isAdmin();

		if ( ! ( isset( $_FILES[ 'fsp_import_file' ] ) && is_string( $_FILES[ 'fsp_import_file' ][ 'name' ] ) && $_FILES[ 'fsp_import_file' ][ 'size' ] > 0 && $_FILES[ 'fsp_import_file' ][ 'type' ] === 'application/json' ) )
		{
			Helper::response( FALSE, [ 'error_msg' => esc_html__( 'No valid import file is selected!' ) ] );
		}

		try
		{
			$json         = file_get_contents( $_FILES[ 'fsp_import_file' ][ 'tmp_name' ] );
			$json_array   = json_decode( $json, TRUE );
			$allowed_keys = [
				'accounts',
				'account_access_tokens',
				'account_nodes',
				'account_status',
				'account_node_status',
				'apps',
				'feeds',
				'schedules'
			];

			DB::DB()->query( 'SET FOREIGN_KEY_CHECKS = 0;' );

			foreach ( $json_array as $table => $rows )
			{
				if ( in_array( $table, $allowed_keys ) && ! empty( $rows ) && is_array( $rows ) )
				{
					DB::DB()->query( 'TRUNCATE TABLE `' . DB::table( $table ) . '`' );

					foreach ( $rows as $row )
					{
						if ( ! is_array( $row ) || empty( $row ) )
						{
							continue;
						}

						DB::DB()->insert( DB::table( $table ), $row );
					}
				}
				else
				{
					continue;
				}
			}

			if ( isset( $json_array[ 'options' ] ) && is_array( $json_array[ 'options' ] ) && ! empty( $json_array[ 'options' ] ) )
			{
				DB::DB()->query( 'DELETE FROM `' . DB::DB()->base_prefix . 'options` WHERE `option_name` LIKE "fs_%" AND `option_name` NOT IN ( "fs_poster_plugin_purchase_key", "fs_poster_plugin_installed" )' );

				foreach ( $json_array[ 'options' ] as $option )
				{
					if ( ! is_array( $option ) || empty( $option ) || in_array( $option[ 'option_name' ], [
							'fs_poster_plugin_purchase_key',
							'fs_poster_plugin_installed'
						] ) )
					{
						continue;
					}

					DB::DB()->insert( DB::DB()->base_prefix . 'options', $option );
				}
			}

			DB::DB()->query( "SET FOREIGN_KEY_CHECKS = 1;" );
		}
		catch ( Exception $e )
		{
			Helper::response( FALSE, [ 'error_msg' => esc_html__( 'Error occurred while importing!', 'fs-poster' ) ] );
		}

		Helper::response( TRUE );
	}
}