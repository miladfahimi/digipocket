<?php

namespace FSPoster\App\Pages\Share\Controllers;

use FSPoster\App\Providers\Pages;

class Main
{
	private function load_assets ()
	{
		wp_enqueue_media();
		wp_register_script( 'fsp-share', Pages::asset( 'Share', 'js/fsp-share.js' ), [ 'jquery', 'fsp' ], NULL );
		wp_enqueue_script( 'fsp-share' );

		wp_enqueue_style( 'fsp-share', Pages::asset( 'Share', 'css/fsp-share.css' ), [ 'fsp-ui' ], NULL );
	}

	public function index ()
	{
		$this->load_assets();

		$data = Pages::action( 'Share', 'get_share' );

		Pages::view( 'Share', 'index', $data );
	}
}