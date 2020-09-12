<?php

namespace FSPoster\App\Pages\Apps\Controllers;

use FSPoster\App\Providers\Pages;

class Main
{
	private function load_assets ()
	{
		wp_register_script( 'fsp-apps', Pages::asset( 'Apps', 'js/fsp-apps.js' ), [ 'jquery', 'fsp' ], NULL );
		wp_enqueue_script( 'fsp-apps' );

		wp_enqueue_style( 'fsp-apps', Pages::asset( 'Apps', 'css/fsp-apps.css' ), [ 'fsp-ui' ], NULL );
	}

	public function index ()
	{
		$this->load_assets();
		$data = Pages::action( 'Apps', 'get_apps' );

		Pages::view( 'Apps', 'index', $data );
	}
}