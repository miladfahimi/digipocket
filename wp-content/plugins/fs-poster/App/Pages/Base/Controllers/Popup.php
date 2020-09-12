<?php

namespace FSPoster\App\Pages\Base\Controllers;

use FSPoster\App\Providers\Pages;

trait Popup
{
	public function add_node_to_list ()
	{
		$data = Pages::action( 'Base', 'get_nodes' );

		Pages::modal( 'Base', 'add_node_to_list', $data );
	}
}