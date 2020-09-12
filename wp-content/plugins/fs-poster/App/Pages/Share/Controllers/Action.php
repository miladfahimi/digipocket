<?php

namespace FSPoster\App\Pages\Share\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Request;

class Action
{
	public function get_share ()
	{
		$post_id = (int) Request::get( 'post_id', '0', 'int' );
		$postInf = FALSE;

		if ( $post_id > 0 )
		{
			DB::DB()->query( "DELETE FROM " . DB::WPtable( 'posts', TRUE ) . " WHERE post_type='fs_post_tmp' AND id<>'{$post_id}' AND CAST(post_date AS DATE)<CAST(NOW() AS DATE)" );
			$postInf = get_post( $post_id, ARRAY_A );
		}

		if ( ! $postInf || ! in_array( $postInf[ 'post_type' ], [
				'fs_post',
				'fs_post_tmp'
			] ) || $postInf[ 'post_author' ] != get_current_user_id() )
		{
			$post_id  = 0;
			$link     = '';
			$imageURL = '';
			$imageId  = '';
			$message  = '';
		}
		else
		{
			$link     = get_post_meta( $post_id, '_fs_link', TRUE );
			$imageId  = get_post_thumbnail_id( $post_id );
			$imageURL = $imageId > 0 ? wp_get_attachment_url( $imageId ) : '';
			$message  = $postInf[ 'post_content' ];
		}

		$currentUserId = (int) get_current_user_id();

		$posts = DB::DB()->get_results( 'SELECT * FROM ' . DB::WPtable( 'posts', TRUE ) . " WHERE post_type='fs_post' AND post_author='" . $currentUserId . "' ORDER BY ID DESC", ARRAY_A );

		return [
			'message'  => $message,
			'link'     => $link,
			'imageURL' => $imageURL,
			'post_id'  => $post_id,
			'imageId'  => $imageId,
			'posts' => $posts
		];
	}
}