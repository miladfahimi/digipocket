<?php

namespace FSPoster\App\Pages\Dashboard\Controllers;

use FSPoster\App\Providers\Pages;

class Main
{
	private function load_assets ()
	{
		wp_register_script( 'fsp-chart', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js' );
		wp_enqueue_script( 'fsp-chart' );
		wp_register_script( 'fsp-dashboard', Pages::asset( 'Dashboard', 'js/fsp-dashboard.js' ), [ 'jquery', 'fsp', 'fsp-chart' ], NULL );
		wp_enqueue_script( 'fsp-dashboard' );

		wp_enqueue_style( 'fsp-dashboard', Pages::asset( 'Dashboard', 'css/fsp-dashboard.css' ), [ 'fsp-ui' ], NULL );
	}

	public function index ()
	{
		$this->load_assets();

		$data = Pages::action( 'Dashboard', 'get_stats' );

		Pages::view( 'Dashboard', 'index', $data );
	}
}