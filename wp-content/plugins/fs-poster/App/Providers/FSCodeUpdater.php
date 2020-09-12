<?php

namespace FSPoster\App\Providers;

use stdClass;

class FSCodeUpdater
{
	private $expiration    = 12;// horus
	private $plugin_slug;
	private $update_url;
	private $plugin_base;
	private $purchase_code;

	public function __construct ( $plugin, $updateURL, $purchase_code )
	{
		$this->plugin_slug   = $plugin;
		$this->update_url    = $updateURL;
		$this->plugin_base   = $plugin . '/init.php';
		$this->purchase_code = $purchase_code;

		$this->check_if_forced_for_update();

		add_filter( 'plugins_api', [ $this, 'plugin_info' ], 20, 3 );

		add_filter( 'site_transient_update_plugins', [ $this, 'push_update' ] );

		add_action( 'upgrader_process_complete', [ $this, 'after_update' ], 10, 2 );

		add_filter( 'plugin_row_meta', [ $this, 'check_for_update' ], 10, 2 );
	}

	public function plugin_info ( $res, $action, $args )
	{
		if ( $action !== 'plugin_information' )
		{
			return FALSE;
		}

		if ( $args->slug !== $this->plugin_slug )
		{
			return FALSE;
		}

		$remote = $this->get_transient();

		if ( $remote )
		{
			$res = new stdClass();

			$res->name    = $remote->name;
			$res->slug    = $this->plugin_slug;
			$res->version = $remote->version;
			$res->tested  = $remote->tested;

			$res->author         = '<a href="https://www.fs-code.com">FS Code</a>';
			$res->author_profile = 'https://www.fs-code.com';
			$res->download_link  = $remote->download_url;
			$res->trunk          = $remote->download_url;
			$res->last_updated   = $remote->last_updated;

			$res->sections = [
				'description'  => $remote->sections->description,
				'installation' => $remote->sections->installation,
				'changelog'    => $remote->sections->changelog
			];

			return $res;

		}

		return FALSE;

	}

	public function push_update ( $transient )
	{
		if ( empty( $transient->checked ) )
		{
			return $transient;
		}

		$remote = $this->get_transient();

		if ( $remote && version_compare( Helper::getVersion(), $remote->version, '<' ) )
		{
			$res                = new stdClass();
			$res->slug          = $this->plugin_slug;
			$res->plugin        = $this->plugin_base;
			$res->new_version   = $remote->version;
			$res->tested        = $remote->tested;
			$res->package       = $remote->download_url;
			$res->compatibility = new stdClass();

			$transient->response[ $res->plugin ] = $res;
		}

		return $transient;
	}

	public function after_update ( $upgrader_object, $options )
	{
		if ( $options[ 'action' ] === 'update' && $options[ 'type' ] === 'plugin' )
		{
			delete_transient( 'fscode_upgrade_' . $this->plugin_slug );
		}
	}

	public function check_for_update ( $links, $file )
	{
		if ( strpos( $file, $this->plugin_base ) !== FALSE )
		{
			$new_links = [
				'check_for_update' => '<a href="plugins.php?fscode_check_for_update=1&plugin=' . urlencode( $this->plugin_slug ) . '&_wpnonce=' . wp_create_nonce( 'fscode_check_for_update_' . $this->plugin_slug ) . '">Check for update</a>'
			];

			$links = array_merge( $links, $new_links );
		}

		return $links;
	}

	private function get_transient ()
	{
		$remote = get_transient( 'fscode_upgrade_' . $this->plugin_slug );

		if ( ! $remote )
		{
			$remote = wp_remote_get( $this->update_url . '?act=check_update&domain=' . network_site_url() . '&purchase_code=' . $this->purchase_code );

			if ( ! is_wp_error( $remote ) && isset( $remote[ 'response' ][ 'code' ] ) && $remote[ 'response' ][ 'code' ] == 200 && ! empty( $remote[ 'body' ] ) )
			{
				set_transient( 'fscode_upgrade_' . $this->plugin_slug, $remote, $this->expiration * 60 * 60 );
			}
			else
			{
				return FALSE;
			}
		}

		$remote = json_decode( $remote[ 'body' ] );

		return $remote;
	}

	private function check_if_forced_for_update ()
	{
		$check_update = Request::get( 'fscode_check_for_update', '', 'string' );
		$plugin       = Request::get( 'plugin', '', 'string' );
		$_wpnonce     = Request::get( '_wpnonce', '', 'string' );

		if ( $check_update === '1' && $plugin === $this->plugin_slug && wp_verify_nonce( $_wpnonce, 'fscode_check_for_update_' . $this->plugin_slug ) )
		{
			delete_transient( 'fscode_upgrade_' . $this->plugin_slug );
		}
	}
}
