<?php

namespace FSPoster\App\Providers;

class Pages
{
	private static $pages = [
		'Dashboard',
		'Accounts',
		'Schedules',
		'Share',
		'Logs',
		'Apps',
		'Settings'
	];

	public static function load_page ()
	{
		$page_name = Request::get( 'page', reset( Pages::$pages ), 'string' );
		$page_name = ucfirst( strtolower( str_replace( 'fs-poster-', '', $page_name ) ) );

		if ( ! in_array( $page_name, Pages::$pages ) )
		{
			$page_name = reset( Pages::$pages );
		}

		echo '<style>
			#wpcontent {
				padding-left: 0 !important;
			}

			body {
				background: #f3f7fa !important;
			}
		</style>';

		Pages::controller( 'Base', 'Main', 'index', [ 'page_name' => $page_name ] );
	}

	public static function action ( $__page_name, $__method_name, $__params = [] )
	{
		return Pages::controller( $__page_name, 'Action', $__method_name, $__params );
	}

	public static function controller ( $__page_name, $__controller_name, $__method_name, $__params = [] )
	{
		$__controller_path = FS_ROOT_DIR . '/App/Pages/' . $__page_name . '/Controllers/' . $__controller_name . '.php';

		if ( ! file_exists( $__controller_path ) )
		{
			exit( $__page_name . '/Controllers/' . $__controller_name . ' not found!' );
		}

		$__controller_class = '\FSPoster\App\Pages\\' . $__page_name . '\Controllers\\' . $__controller_name;
		$__controller       = new $__controller_class;

		if ( ! method_exists( $__controller, $__method_name ) )
		{
			exit( $__page_name . '/Controllers/' . $__controller_name . ' is not contain ' . $__method_name . ' method!' );
		}

		return $__controller->$__method_name( $__params );
	}

	public static function modal ( $__page_name, $__view_name, $fsp_params = [] )
	{
		$__view_path = FS_ROOT_DIR . '/App/Pages/' . $__page_name . '/Views/Modals/' . $__view_name . '.php';

		if ( ! file_exists( $__view_path ) )
		{
			exit( $__page_name . '/Views/Modals/' . $__view_name . ' not found!' );
		}

		ob_start();

		require_once $__view_path;

		Helper::response( TRUE, [
			'html' => htmlspecialchars( ob_get_clean() )
		] );
	}

	public static function view ( $__page_name, $__view_name, $fsp_params = [] )
	{
		$__view_path = FS_ROOT_DIR . '/App/Pages/' . $__page_name . '/Views/' . $__view_name . '.php';

		if ( ! file_exists( $__view_path ) )
		{
			exit( $__page_name . '/Views/' . $__view_name . ' not found!' );
		}

		require_once $__view_path;
	}

	public static function asset ( $__page_name, $__path )
	{
		$append_version = substr( $__path, -4 ) === '.css' || substr( $__path, -3 ) === '.js' ? '?version=' . Helper::getInstalledVersion() : '';

		return plugin_dir_url( FS_ROOT_DIR . '/init.php' ) . 'App/Pages/' . $__page_name . '/Assets/' . $__path . $append_version;
	}
}