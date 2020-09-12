<?php

namespace FSPoster\App\Pages\Share\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;
use FSPoster\App\Providers\ShareService;

trait Ajax
{
	public function share_post ()
	{
		$post_id = Request::post( 'id', 0, 'num' );

		if ( ! ( $post_id && $post_id > 0 ) )
		{
			exit();
		}

		$feedId = (int) $post_id;

		$res = ShareService::post( $feedId );

		Helper::response( TRUE, [ 'result' => $res ] );
	}

	public function share_saved_post ()
	{
		$post_id         = Request::post( 'post_id', '0', 'num' );
		$nodes           = Request::post( 'nodes', [], 'array' );
		$background      = ! ( Request::post( 'background', '0', 'string' ) ) ? 0 : 1;
		$custom_messages = Request::post( 'custom_messages', [], 'array' );

		if ( empty( $post_id ) || empty( $nodes ) || $post_id <= 0 )
		{
			Helper::response( FALSE );
		}

		if ( ! ShareService::insertFeeds( $post_id, $nodes, $custom_messages, FALSE, NULL, $background, NULL, TRUE ) )
		{
			Helper::response( FALSE, esc_html__( 'There isn\'t any active account or community to share the post!' ) );
		}

		Helper::response( TRUE );
	}

	public function get_feed_details ()
	{
		$feedId = Request::post( 'feed_id', '0', 'num' );

		if ( empty( $feedId ) || $feedId <= 0 )
		{
			Helper::response( FALSE );
		}

		$feed = DB::fetch( 'feeds', [
			'id' => $feedId
		] );

		if ( ! $feed )
		{
			Helper::response( FALSE );
		}

		$result = [
			'post_id'        => $feed[ 'post_id' ],
			'nodes'          => [ $feed[ 'driver' ] . ':' . $feed[ 'node_type' ] . ':' . $feed[ 'node_id' ] ],
			'customMessages' => []
		];

		if ( ! is_null( $feed[ 'custom_post_message' ] ) )
		{
			$result[ 'customMessages' ][ $feed[ 'driver' ] ] = $feed[ 'custom_post_message' ];
		}
		else
		{
			$result[ 'customMessages' ][ $feed[ 'driver' ] ] = Helper::getOption( 'post_text_message_' . $feed[ 'driver' ], "{title}" );
		}

		DB::DB()->delete( DB::table( 'feeds' ), [ 'id' => $feed[ 'id' ] ] );

		Helper::response( TRUE, [ 'result' => $result ] );
	}

	public function manual_share_save ()
	{
		$id      = Request::post( 'id', '0', 'num' );
		$link    = Request::post( 'link', '', 'string' );
		$message = Request::post( 'message', '', 'string' );
		$image   = Request::post( 'image', '0', 'num' );
		$tmp     = Request::post( 'tmp', '0', 'num', [ '0', '1' ] );

		$sqlData = [
			'post_type'      => 'fs_post' . ( $tmp ? '_tmp' : '' ),
			'post_content'   => $message,
			'post_status'    => 'publish',
			'comment_status' => 'closed',
			'ping_status'    => 'closed'
		];

		if ( $id > 0 )
		{
			$sqlData[ 'ID' ] = $id;

			wp_insert_post( $sqlData );

			delete_post_meta( $id, '_fs_link' );
			delete_post_meta( $id, '_thumbnail_id' );
		}
		else
		{
			$id = wp_insert_post( $sqlData );
		}

		add_post_meta( $id, '_fs_link', $link );

		if ( $image > 0 )
		{
			add_post_meta( $id, '_thumbnail_id', $image );
		}
		else
		{
			delete_post_meta( $id, '_thumbnail_id' );
		}

		Helper::response( TRUE, [ 'id' => $id ] );
	}

	public function manual_share_delete ()
	{
		$id = Request::post( 'id', '0', 'num' );

		if ( ! ( $id > 0 ) )
		{
			Helper::response( FALSE );
		}

		$currentUserId = (int) get_current_user_id();

		$checkPost = DB::DB()->get_row( 'SELECT * FROM ' . DB::WPtable( 'posts', TRUE ) . " WHERE post_type='fs_post' AND post_author='{$currentUserId}' AND ID='{$id}'", ARRAY_A );

		if ( ! $checkPost )
		{
			Helper::response( FALSE, 'Post not found!' );
		}

		delete_post_meta( $id, '_fs_link' );
		delete_post_meta( $id, '_thumbnail_id' );
		wp_delete_post( $id );

		Helper::response( TRUE, [ 'id' => $id ] );
	}

	public function check_post_is_published ()
	{
		$id = Request::post( 'id', '0', 'num' );

		$postStatus = get_post_status( $id );

		Helper::response( TRUE, [
			'post_status' => $postStatus === 'publish'
		] );
	}

	public function share_on_bg_paused_feeds ()
	{
		DB::DB()->update( DB::table( 'feeds' ), [
			'share_on_background' => 1
		], [ 'share_on_background' => 0, 'is_sended' => 0, 'blog_id' => Helper::getBlogId() ] );

		Helper::response( TRUE );
	}

	public function do_not_share_paused_feeds ()
	{
		DB::DB()->delete( DB::table( 'feeds' ), [ 'share_on_background' => 0, 'is_sended' => 0, 'blog_id' => Helper::getBlogId() ] );

		Helper::response( TRUE );
	}
}