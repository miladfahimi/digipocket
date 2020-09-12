<?php

namespace FSPoster\App\Pages\Base\Controllers;

use FSPoster\App\Providers\Pages;

class Main
{
	public function index ( $data )
	{
		Pages::view( 'Base', 'index', [ 'page_name' => $data[ 'page_name' ] ] );
	}
}