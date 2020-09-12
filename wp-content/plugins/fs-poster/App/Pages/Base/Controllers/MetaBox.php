<?php

namespace FSPoster\App\Pages\Base\Controllers;

use FSPoster\App\Providers\Pages;

class MetaBox
{
	public function post_meta_box ( $params )
	{
		$post_id = $params[ 'post_id' ];
		$data    = Pages::action( 'Base', 'get_post_meta_box', $post_id );
		$data[ 'minified' ] = isset( $params[ 'minified_metabox' ] ) && $params[ 'minified_metabox' ] === true;

		if ( isset( $params[ 'active_nodes' ] ) )
		{
			$data[ 'active_nodes' ] = $params[ 'active_nodes' ];
		}

		Pages::view( 'Base', 'post_meta_box', $data );
	}

	public function post_meta_box_edit ( $params )
	{
		$data = Pages::action( 'Base', 'get_post_meta_box_edit', $params );
		$data[ 'minified' ] = isset( $params[ 'minified_metabox' ] ) && $params[ 'minified_metabox' ] === true;

		Pages::view( 'Base', 'post_meta_box_edit', $data );
	}
}