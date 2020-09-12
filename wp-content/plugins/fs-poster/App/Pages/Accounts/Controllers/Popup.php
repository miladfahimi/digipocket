<?php

namespace FSPoster\App\Pages\Accounts\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Request;

trait Popup
{
	public function add_fb_account ()
	{
		$data = Pages::action( 'Accounts', 'get_fb_apps' );

		Pages::modal( 'Accounts', 'fb/add_account', $data );
	}

	public function add_twitter_account ()
	{
		$data = Pages::action( 'Accounts', 'get_twitter_apps' );

		Pages::modal( 'Accounts', 'twitter/add_account', $data );
	}

	public function add_linkedin_account ()
	{
		$data = Pages::action( 'Accounts', 'get_linkedin_apps' );

		Pages::modal( 'Accounts', 'linkedin/add_account', $data );
	}

	public function add_ok_account ()
	{
		$data = Pages::action( 'Accounts', 'get_ok_apps' );

		Pages::modal( 'Accounts', 'ok/add_account', $data );
	}

	public function add_pinterest_account ()
	{
		$data = Pages::action( 'Accounts', 'get_pinterest_apps' );

		Pages::modal( 'Accounts', 'pinterest/add_account', $data );
	}

	public function add_reddit_account ()
	{
		$data = Pages::action( 'Accounts', 'get_reddit_apps' );

		Pages::modal( 'Accounts', 'reddit/add_account', $data );
	}

	public function add_tumblr_account ()
	{
		$data = Pages::action( 'Accounts', 'get_tumblr_apps' );

		Pages::modal( 'Accounts', 'tumblr/add_account', $data );
	}

	public function reddit_add_subreddit ()
	{
		$data = Pages::action( 'Accounts', 'get_subreddit_info' );

		Pages::modal( 'Accounts', 'reddit/add_subreddit', $data );
	}

	public function add_vk_account ()
	{
		$data = Pages::action( 'Accounts', 'get_vk_apps' );

		Pages::modal( 'Accounts', 'vk/add_account', $data );
	}

	public function add_instagram_account ()
	{
		Pages::modal( 'Accounts', 'instagram/add_account' );
	}

	public function activate_with_condition ()
	{
		$id   = Request::post( 'id', '0', 'num' );
		$type = Request::post( 'type', '', 'string' );

		if ( $type === 'node' )
		{
			$ajaxUrl   = 'settings_node_activity_change';
			$tableName = 'account_node_status';
			$fieldName = 'node_id';
		}
		else
		{
			$ajaxUrl   = 'account_activity_change';
			$tableName = 'account_status';
			$fieldName = 'account_id';
		}
		$info        = DB::fetch( $tableName, [ $fieldName => $id ] );
		$filter_type = $info ? $info[ 'filter_type' ] : 'in';
		$categories  = $info ? explode( ',', $info[ 'categories' ] ) : [];

		Pages::modal( 'Accounts', 'activate_with_condition', [
			'parameters' => [
				'id'          => $id,
				'ajaxUrl'     => $ajaxUrl,
				'filter_type' => $filter_type,
				'categories'  => $categories
			]
		] );
	}

	public function add_google_b_account ()
	{
		Pages::modal( 'Accounts', 'google_b/add_account' );
	}

	public function add_telegram_bot ()
	{
		Pages::modal( 'Accounts', 'telegram/add_bot' );
	}

	public function telegram_add_chat ()
	{
		Pages::modal( 'Accounts', 'telegram/add_chat', [
			'accountId' => (int) Request::post( 'account_id', '0', 'num' )
		] );
	}

	public function add_medium_account ()
	{
		$data = Pages::action( 'Accounts', 'get_medium_apps' );

		Pages::modal( 'Accounts', 'medium/add_account', $data );
	}

	public function add_wordpress_site ()
	{
		Pages::modal( 'Accounts', 'wordpress/add_site' );
	}
}