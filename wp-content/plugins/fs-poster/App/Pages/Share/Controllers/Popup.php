<?php

namespace FSPoster\App\Pages\Share\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

trait Popup
{
	public function share_feeds ()
	{
		$post_id         = Request::post( 'post_id', '0', 'num' );
		$is_paused_feeds = Request::post( 'is_paused_feeds', 0, 'int' );

		if ( $is_paused_feeds !== 1 && ! ( $post_id > 0 ) )
		{
			exit();
		}

		if ( $is_paused_feeds === 1 )
		{
			$feeds = DB::DB()->get_results( DB::DB()->prepare( 'SELECT * FROM ' . DB::table( 'feeds' ) . ' WHERE blog_id = %d AND is_sended = %d AND share_on_background = %d', [
				Helper::getBlogId(),
				0,
				0
			] ), ARRAY_A );
		}
		else
		{
			$feeds = DB::DB()->get_results( DB::DB()->prepare( 'SELECT * FROM ' . DB::table( 'feeds' ) . ' WHERE blog_id = %d AND is_sended = %d AND post_id = %d AND send_time >= %s  AND share_on_background = %d', [
				Helper::getBlogId(),
				0,
				$post_id,
				Date::dateTimeSQL( '-30 seconds' ),
				0
			] ), ARRAY_A );
		}

		Pages::modal( 'Share', 'share_feeds', [
			'parameters' => [
				'feeds' => $feeds
			]
		] );
	}

	public function share_saved_post ()
	{
		$post_id = Request::post( 'post_id', '0', 'num' );

		if ( ! ( $post_id > 0 ) )
		{
			exit();
		}

		Pages::modal( 'Share', 'share_saved_post', [
			'parameters' => [
				'post_id' => $post_id
			]
		] );
	}
}