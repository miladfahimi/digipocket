<?php

namespace FSPoster\App\Pages\Logs\Controllers;

use FSPoster\App\Providers\Pages;

class Main
{
	public function index ()
	{
		$data = Pages::action( 'Logs', 'get_logs' );

		Pages::view( 'Logs', 'index', $data );
	}
}