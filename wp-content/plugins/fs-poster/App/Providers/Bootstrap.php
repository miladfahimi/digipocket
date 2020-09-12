<?php

namespace FSPoster\App\Providers;

/**
 * Class Bootstrap
 * @package FSPoster\App\Providers
 */
class Bootstrap
{
	/**
	 * Bootstrap constructor.
	 */
	public function __construct ()
	{
		CronJob::init();
		$this->registerDefines();

		$this->loadPluginTextdomaion();
		$this->loadPluginLinks();
		$this->createCustomPostTypes();
		$this->createPostSaveEvent();

		if ( is_admin() )
		{
			new BackEnd();
		}
		else
		{
			new FrontEnd();
		}
	}

	private function registerDefines ()
	{
		define( 'FS_ROOT_DIR', dirname( dirname( __DIR__ ) ) );
		define( 'FS_API_URL', 'https://www.fs-poster.com/api/' );
	}

	private function loadPluginLinks ()
	{
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
			$newLinks = [
				'<a href="https://support.fs-code.com" target="_blank">' . __( 'Support', 'fs-poster' ) . '</a>',
				'<a href="https://www.fs-poster.com/documentation/" target="_blank">' . __( 'Documentation', 'fs-poster' ) . '</a>'
			];

			return array_merge( $newLinks, $links );
		} );
	}

	private function loadPluginTextdomaion ()
	{
		add_action( 'plugins_loaded', function () {
			load_plugin_textdomain( 'fs-poster', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
		} );
	}

	private function createCustomPostTypes ()
	{
		add_action( 'init', function () {
			register_post_type( 'fs_post', [
				'labels'      => [
					'name'          => __( 'FS Posts' ),
					'singular_name' => __( 'FS Post' )
				],
				'public'      => FALSE,
				'has_archive' => TRUE
			] );

			register_post_type( 'fs_post_tmp', [
				'labels'      => [
					'name'          => __( 'FS Posts' ),
					'singular_name' => __( 'FS Post' )
				],
				'public'      => FALSE,
				'has_archive' => TRUE
			] );
		} );
	}

	private function createPostSaveEvent ()
	{
		add_action( 'transition_post_status', [ 'FSPoster\App\Providers\ShareService', 'postSaveEvent' ], 10, 3 );
		add_action( 'delete_post', [ 'FSPoster\App\Providers\ShareService', 'deletePostFeeds' ], 10 );
	}
}