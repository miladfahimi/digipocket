<?php

namespace FSPoster\App\Pages\Accounts\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

class Action
{
	public function get_fb_accounts ()
	{
		$accounts_list  = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id AND node_type='ownpage' AND (user_id=%d OR is_public=1)) ownpages,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id AND node_type='group' AND (user_id=%d OR is_public=1)) `groups`,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'accounts' ) . " tb1
	WHERE (user_id=%d OR is_public=1) AND driver='fb' AND blog_id=%d", [
			get_current_user_id(),
			get_current_user_id(),
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );
		$my_accounts_id = [ -1 ];

		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
				(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE (user_id=%d OR is_public=1) AND account_id=%d AND blog_id=%d", [
				get_current_user_id(),
				get_current_user_id(),
				$account_info[ 'id' ],
				Helper::getBlogId()
			] ), ARRAY_A );
		}

		$public_communities = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'account_nodes' ) . " tb1
	WHERE driver='fb' AND (user_id=%d OR is_public=1) AND blog_id=%d AND account_id NOT IN ('" . implode( "','", $my_accounts_id ) . "')", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list'      => $accounts_list,
			'public_communities' => $public_communities
		];
	}

	public function get_google_b_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id) locations,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active 
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE (user_id=%d OR is_public=1) AND driver='google_b' AND blog_id=%d", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
				(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE (user_id=%d OR is_public=1) AND account_id=%d AND blog_id=%d", [
				get_current_user_id(),
				get_current_user_id(),
				$account_info[ 'id' ],
				Helper::getBlogId()
			] ), ARRAY_A );
		}

		$public_communities = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'account_nodes' ) . " tb1
	WHERE driver='google_b' AND (user_id=%d OR is_public=1) AND blog_id=%d AND account_id NOT IN ('" . implode( "','", $my_accounts_id ) . "')", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list'      => $accounts_list,
			'public_communities' => $public_communities
		];
	}

	public function get_instagram_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active 
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE (user_id=%d OR is_public=1) AND driver='instagram' AND blog_id=%d", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		if ( version_compare( PHP_VERSION, '5.6.0' ) < 0 )
		{
			echo '<div >
				<div ><i class="fa fa-warning fa-exclamation-triangle fa-5x" ></i> </div>
				<div >For using instagram account, please update your PHP version 5.6 or higher!</div>
				<div>Your current PHP version is: ' . PHP_VERSION . '</div>
			</div>';

			return [];
		}

		return [
			'accounts_list' => $accounts_list
		];
	}

	public function get_linkedin_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
	 	*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id AND (user_id=%d OR is_public=1)) AS companies,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'accounts' ) . " tb1
	WHERE (user_id=%d OR is_public=1) AND driver='linkedin' AND blog_id=%d", [
			get_current_user_id(),
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
				(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE (user_id=%d OR is_public=1) AND account_id=%d AND blog_id=%d", [
				get_current_user_id(),
				get_current_user_id(),
				$account_info[ 'id' ],
				Helper::getBlogId()
			] ), ARRAY_A );
		}

		$public_communities = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'account_nodes' ) . " tb1
	WHERE driver='linkedin' AND (user_id=%d OR is_public=1) AND blog_id=%d AND account_id NOT IN ('" . implode( "','", $my_accounts_id ) . "')", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list'      => $accounts_list,
			'public_communities' => $public_communities
		];
	}

	public function get_medium_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id AND (user_id=%d OR is_public=1)) publications,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active 
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE (user_id=%d OR is_public=1) AND driver='medium' AND blog_id=%d", [
			get_current_user_id(),
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
				(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE (user_id=%d OR is_public=1) AND blog_id=%d AND account_id=%d", [
				get_current_user_id(),
				get_current_user_id(),
				Helper::getBlogId(),
				$account_info[ 'id' ]
			] ), ARRAY_A );
		}

		$public_communities = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'account_nodes' ) . " tb1
	WHERE driver='medium' AND (user_id=%d OR is_public=1) AND blog_id=%d AND account_id NOT IN ('" . implode( "','", $my_accounts_id ) . "')", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list'      => $accounts_list,
			'public_communities' => $public_communities
		];
	}

	public function get_ok_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
	 	*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id AND (user_id=%d OR is_public=1)) AS `groups`,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'accounts' ) . " tb1
	WHERE (user_id=%d OR is_public=1) AND driver='ok' AND blog_id=%d", [
			get_current_user_id(),
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
				(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE (user_id=%d OR is_public=1) AND blog_id=%d AND account_id=%d", [
				get_current_user_id(),
				get_current_user_id(),
				Helper::getBlogId(),
				$account_info[ 'id' ]
			] ), ARRAY_A );
		}

		$public_communities = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'account_nodes' ) . " tb1
	WHERE driver='ok' AND (user_id=%d OR is_public=1) AND blog_id=%d AND account_id NOT IN ('" . implode( "','", $my_accounts_id ) . "')", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list'      => $accounts_list,
			'public_communities' => $public_communities
		];
	}

	public function get_pinterest_accounts ()
	{
		$accountsList = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id AND (user_id=%d OR is_public=1)) boards,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE (user_id=%d OR is_public=1) AND driver='pinterest' AND blog_id=%d", [
			get_current_user_id(),
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		$collectMyAccountIDs = [ -1 ];
		foreach ( $accountsList as $i => $accountInf1 )
		{
			$collectMyAccountIDs[]             = (int) $accountInf1[ 'id' ];
			$accountsList[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
				(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE (user_id=%d OR is_public=1) AND account_id=%d AND blog_id=%d", [
				get_current_user_id(),
				get_current_user_id(),
				$accountInf1[ 'id' ],
				Helper::getBlogId()
			] ), ARRAY_A );
		}

		$publicCommunities = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'account_nodes' ) . " tb1
	WHERE driver='pinterest' AND (user_id=%d OR is_public=1) AND blog_id=%d AND account_id NOT IN ('" . implode( "','", $collectMyAccountIDs ) . "')", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list'      => $accountsList,
			'public_communities' => $publicCommunities
		];
	}

	public function get_reddit_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id AND (user_id=%d OR is_public=1)) subreddits,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active 
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE (user_id=%d OR is_public=1) AND driver='reddit' AND blog_id=%d", [
			get_current_user_id(),
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
				(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE (user_id=%d OR is_public=1) AND account_id=%d AND blog_id=%d", [
				get_current_user_id(),
				get_current_user_id(),
				$account_info[ 'id' ],
				Helper::getBlogId()
			] ), ARRAY_A );
		}

		$public_communities = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'account_nodes' ) . " tb1
	WHERE driver='reddit' AND (user_id=%d OR is_public=1) AND blog_id=%d AND account_id NOT IN ('" . implode( "','", $my_accounts_id ) . "')", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list'      => $accounts_list,
			'public_communities' => $public_communities
		];
	}

	public function get_telegram_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id) chats,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active 
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE (user_id=%d OR is_public=1) AND driver='telegram' AND blog_id=%d", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
				(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE (user_id=%d OR is_public=1) AND account_id=%d AND blog_id=%d", [
				get_current_user_id(),
				get_current_user_id(),
				$account_info[ 'id' ],
				Helper::getBlogId()
			] ), ARRAY_A );
		}

		$public_communities = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'account_nodes' ) . " tb1
	WHERE driver='telegram' AND (user_id=%d OR is_public=1) AND blog_id=%d AND account_id NOT IN ('" . implode( "','", $my_accounts_id ) . "')", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list'      => $accounts_list,
			'public_communities' => $public_communities
		];
	}

	public function get_tumblr_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id) AS blogs
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE (user_id=%d OR is_public=1) AND driver='tumblr' AND blog_id=%d", [
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
				(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE (user_id=%d OR is_public=1) AND account_id=%d AND blog_id=%d", [
				get_current_user_id(),
				get_current_user_id(),
				$account_info[ 'id' ],
				Helper::getBlogId()
			] ), ARRAY_A );
		}

		$public_communities = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'account_nodes' ) . " tb1
	WHERE driver='tumblr' AND (user_id=%d OR is_public=1) AND blog_id=%d AND account_id NOT IN ('" . implode( "','", $my_accounts_id ) . "')", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list'      => $accounts_list,
			'public_communities' => $public_communities
		];
	}

	public function get_twitter_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE (user_id=%d OR is_public=1) AND driver='twitter' AND blog_id=%d", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list' => $accounts_list
		];
	}

	public function get_vk_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id AND (user_id=%d OR is_public=1)) communities,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE (user_id=%d OR is_public=1) AND driver='vk' AND `blog_id`=%d", [
			get_current_user_id(),
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
				(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE (user_id=%d OR is_public=1) AND account_id=%d AND blog_id=%d", [
				get_current_user_id(),
				get_current_user_id(),
				$account_info[ 'id' ],
				Helper::getBlogId()
			] ), ARRAY_A );
		}

		$public_communities = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'account_nodes' ) . " tb1
	WHERE driver='vk' AND (user_id=%d OR is_public=1) AND blog_id=%d AND account_id NOT IN ('" . implode( "','", $my_accounts_id ) . "')", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list'      => $accounts_list,
			'public_communities' => $public_communities
		];
	}

	public function get_wordpress_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id AND (user_id=%d OR is_public=1)) publications,
		(SELECT filter_type FROM " . DB::table( 'account_status' ) . " WHERE account_id=tb1.id AND user_id=%d) is_active 
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE (user_id=%d OR is_public=1) AND driver='wordpress' AND blog_id=%d", [
			get_current_user_id(),
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];
		}

		$public_communities = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT filter_type FROM " . DB::table( 'account_node_status' ) . " WHERE node_id=tb1.id AND user_id=%d) is_active
	FROM " . DB::table( 'account_nodes' ) . " tb1
	WHERE driver='wordpress' AND (user_id=%d OR is_public=1) AND blog_id=%d AND account_id NOT IN ('" . implode( "','", $my_accounts_id ) . "')", [
			get_current_user_id(),
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		return [
			'accounts_list'      => $accounts_list,
			'public_communities' => $public_communities
		];
	}

	public function get_fb_apps ()
	{
		return [
			'applications' => DB::fetchAll( 'apps', [ 'driver' => 'fb' ] )
		];
	}

	public function get_twitter_apps ()
	{
		return [
			'applications' => DB::fetchAll( 'apps', [ 'driver' => 'twitter' ] )
		];
	}

	public function get_linkedin_apps ()
	{
		return [
			'applications' => DB::fetchAll( 'apps', [ 'driver' => 'linkedin' ] )
		];
	}

	public function get_ok_apps ()
	{
		return [
			'applications' => DB::fetchAll( 'apps', [ 'driver' => 'ok' ] )
		];
	}

	public function get_pinterest_apps ()
	{
		return [
			'applications' => DB::fetchAll( 'apps', [ 'driver' => 'pinterest' ] )
		];
	}

	public function get_reddit_apps ()
	{
		return [
			'applications' => DB::fetchAll( 'apps', [ 'driver' => 'reddit' ] )
		];
	}

	public function get_tumblr_apps ()
	{
		return [
			'applications' => DB::fetchAll( 'apps', [ 'driver' => 'tumblr' ] )
		];
	}

	public function get_vk_apps ()
	{
		return [
			'applications' => DB::fetchAll( 'apps', [ 'driver' => 'vk' ] )
		];
	}

	public function get_medium_apps ()
	{
		return [
			'applications' => DB::fetchAll( 'apps', [ 'driver' => 'medium' ] )
		];
	}

	public function get_subreddit_info ()
	{
		$accountId  = (int) Request::post( 'account_id', '0', 'num' );
		$userId     = (int) get_current_user_id();
		$accountInf = DB::DB()->get_row( "SELECT * FROM " . DB::table( 'accounts' ) . " WHERE id='{$accountId}' AND driver='reddit' AND (user_id='{$userId}' OR is_public=1) AND blog_id='" . Helper::getBlogId() . "' ", ARRAY_A );

		return [
			'accountId'  => $accountId,
			'userId'     => $userId,
			'accountInf' => ! $accountInf ? '' : $accountInf
		];
	}

	public function get_counts ()
	{
		DB::DB()->query( 'DELETE FROM `' . DB::table( 'account_status' ) . '` WHERE (SELECT count(0) FROM `' . DB::table( 'accounts' ) . '` WHERE id=account_id)=0' );
		DB::DB()->query( 'DELETE FROM `' . DB::table( 'account_node_status' ) . '` WHERE (SELECT count(0) FROM `' . DB::table( 'account_nodes' ) . '` WHERE id=`' . DB::table( 'account_node_status' ) . '`.node_id)=0' );

		$accounts_list     = DB::DB()->get_results( DB::DB()->prepare( "SELECT driver, COUNT(0) AS _count FROM " . DB::table( 'accounts' ) . " WHERE (user_id=%d OR is_public=1) AND blog_id=%d GROUP BY driver", [
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );
		$fsp_accountsCount = [
			'total'     => 0,
			'fb'        => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'twitter'   => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'instagram' => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'linkedin'  => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'vk'        => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'pinterest' => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'reddit'    => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'tumblr'    => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'google_b'  => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'ok'        => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'telegram'  => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'medium'    => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			],
			'wordpress' => [
				'total'  => 0,
				'failed' => 0,
				'active' => 0
			]
		];

		foreach ( $accounts_list as $aInf )
		{
			if ( isset( $fsp_accountsCount[ $aInf[ 'driver' ] ] ) )
			{
				$fsp_accountsCount[ $aInf[ 'driver' ] ][ 'total' ] = $aInf[ '_count' ];
				$fsp_accountsCount[ 'total' ]                      += $aInf[ '_count' ];
			}
		}

		$failed_accountsList = DB::DB()->get_results( DB::DB()->prepare( "SELECT driver, COUNT(0) AS _count FROM " . DB::table( 'accounts' ) . " WHERE status = 'error' AND (user_id=%d OR is_public=1) AND blog_id=%d GROUP BY driver", [
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		foreach ( $failed_accountsList as $aInf )
		{
			if ( isset( $fsp_accountsCount[ $aInf[ 'driver' ] ] ) )
			{
				$fsp_accountsCount[ $aInf[ 'driver' ] ][ 'failed' ] = $aInf[ '_count' ];
			}
		}

		$active_accounts = DB::DB()->get_results( DB::DB()->prepare( "SELECT `driver` FROM " . DB::table( 'accounts' ) . " WHERE ( `id` IN ( SELECT `account_id` FROM " . DB::table( 'account_status' ) . ") OR `id` IN ( SELECT `account_id` FROM " . DB::table( 'account_nodes' ) . " WHERE `id` IN ( SELECT `node_id` FROM " . DB::table( 'account_node_status' ) . " ) ) ) AND ( `user_id` = %d OR `is_public` = 1 ) AND `blog_id` = %d GROUP BY `driver`", [
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		foreach ( $active_accounts as $aInf )
		{
			if ( isset( $fsp_accountsCount[ $aInf[ 'driver' ] ] ) )
			{
				$fsp_accountsCount[ $aInf[ 'driver' ] ][ 'active' ] = 1;
			}
		}

		return $fsp_accountsCount;
	}
}