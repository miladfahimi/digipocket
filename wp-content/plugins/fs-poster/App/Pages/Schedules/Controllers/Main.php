<?php

namespace FSPoster\App\Pages\Schedules\Controllers;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Request;

class Main
{
	private function load_assets ()
	{
		wp_register_script( 'fsp-schedules', Pages::asset( 'Schedules', 'js/fsp-schedules.js' ), [ 'jquery', 'fsp' ], null );
		wp_enqueue_script( 'fsp-schedules' );

		wp_enqueue_style( 'fsp-schedules', Pages::asset( 'Schedules', 'css/fsp-schedules.css' ), [ 'fsp-ui' ], NULL );
	}

	public function index ()
	{
		$this->load_assets();

		$view = Request::get( 'view', 'list', 'string', [ 'list', 'calendar' ] );

		if ( $view === 'list' )
		{
			$this->list_view();
		}
		else
		{
			if ( $view === 'calendar' )
			{
				$this->calendar_view();
			}
		}
	}

	public function list_view ()
	{
		$data = Pages::action( 'Schedules', 'get_list' );

		Pages::view( 'Schedules', 'list', $data );
	}

	public function calendar_view ()
	{
		Pages::view( 'Schedules', 'calendar' );
	}
}