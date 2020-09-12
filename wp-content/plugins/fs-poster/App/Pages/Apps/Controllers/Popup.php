<?php

namespace FSPoster\App\Pages\Apps\Controllers;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Request;

trait Popup
{
	public function add_app ()
	{
		$fields = Request::post( 'fields', '', 'string' );
		$driver = Request::post( 'driver', '', 'string' );

		Pages::modal( 'Apps', 'add', [
			'fields' => explode( ',', $fields ),
			'driver' => $driver
		] );
	}
}