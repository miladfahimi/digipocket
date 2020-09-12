<?php

namespace FSPoster\App\Providers;

trait PluginMenu
{
	public function initMenu ()
	{
		add_action( 'init', function () {
			$this->getNotifications();
			$res1 = $this->checkLicense();
			if ( FALSE === $res1 )
			{
				return;
			}

			$plgnVer = Helper::getOption( 'poster_plugin_installed', '0', TRUE );

			$hideFSPosterForRoles = explode( '|', Helper::getOption( 'hide_menu_for', '' ) );

			$userInf   = wp_get_current_user();
			$userRoles = (array) $userInf->roles;

			if ( ! in_array( 'administrator', $userRoles ) )
			{
				foreach ( $userRoles as $roleId )
				{
					if ( in_array( $roleId, $hideFSPosterForRoles ) )
					{
						return;
					}
				}
			}

			if ( empty( $plgnVer ) )
			{
				add_action( 'admin_menu', function () {
					add_menu_page( 'FS Poster', 'FS Poster', 'read', 'fs-poster', function () {
						Pages::controller( 'Base', 'App', 'install' );
					}, Pages::asset( 'Base', 'img/logo_xs.png' ), 90 );
				} );

				return;
			}
			else
			{
				if ( $plgnVer != Helper::getVersion() )
				{
					$fsPurchaseKey = Helper::getOption( 'poster_plugin_purchase_key', '', TRUE );

					if ( $fsPurchaseKey != '' )
					{
						$result = Ajax::updatePlugin( $fsPurchaseKey );
						if ( $result[ 0 ] == FALSE )
						{
							add_action( 'admin_menu', function () {
								add_menu_page( 'FS Poster', 'FS Poster', 'read', 'fs-poster', function () {
									Pages::controller( 'Base', 'App', 'update' );
								}, Pages::asset( 'Base', 'img/logo_xs.png' ), 90 );
							} );

							return;
						}
					}
					else
					{
						add_action( 'admin_menu', function () {
							add_menu_page( 'FS Poster', 'FS Poster', 'read', 'fs-poster', function () {
								Pages::controller( 'Base', 'App', 'update' );
							}, Pages::asset( 'Base', 'img/logo_xs.png' ), 90 );
						} );

						return;
					}
				}
			}

			add_action( 'admin_menu', function () {
				add_menu_page( 'FS Poster', 'FS Poster', 'read', 'fs-poster', [
					Pages::class,
					'load_page'
				], Pages::asset( 'Base', 'img/logo_xs.png' ), 90 );

				add_submenu_page( 'fs-poster', esc_html__( 'Dashboard', 'fs-poster' ), esc_html__( 'Dashboard', 'fs-poster' ), 'read', 'fs-poster', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', esc_html__( 'Accounts', 'fs-poster' ), esc_html__( 'Accounts', 'fs-poster' ), 'read', 'fs-poster-accounts', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', esc_html__( 'Schedules', 'fs-poster' ), esc_html__( 'Schedules', 'fs-poster' ), 'read', 'fs-poster-schedules', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', esc_html__( 'Direct Share', 'fs-poster' ), esc_html__( 'Direct Share', 'fs-poster' ), 'read', 'fs-poster-share', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', esc_html__( 'Logs', 'fs-poster' ), esc_html__( 'Logs', 'fs-poster' ), 'read', 'fs-poster-logs', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', esc_html__( 'Apps', 'fs-poster' ), esc_html__( 'Apps', 'fs-poster' ), 'read', 'fs-poster-apps', [
					Pages::class,
					'load_page'
				] );

				if ( current_user_can( 'administrator' ) )
				{
					add_submenu_page( 'fs-poster', esc_html__( 'Settings', 'fs-poster' ), esc_html__( 'Settings', 'fs-poster' ), 'read', 'fs-poster-settings', [
						Pages::class,
						'load_page'
					] );
				}
			} );
		} );
	}

	public function app_disable ()
	{
		register_uninstall_hook( FS_ROOT_DIR . '/init.php', [ Helper::class, 'removePlugin' ] );

		Pages::controller( 'Base', 'App', 'disable' );
	}

	public function getNotifications ()
	{
		$lastTime = Helper::getOption( 'license_last_checked_time', 0 );

		if ( Date::epoch() - $lastTime < 10 * 60 * 60 )
		{
			return;
		}

		$fsPurchaseKey = Helper::getOption( 'poster_plugin_purchase_key', '', TRUE );

		$checkPurchaseCodeURL = FS_API_URL . "api.php?act=get_notifications&purchase_code=" . $fsPurchaseKey . "&domain=" . network_site_url();
		$result2              = Curl::getURL( $checkPurchaseCodeURL );
		$result               = json_decode( $result2, TRUE );

		if ( ! isset( $result ) || empty( $result ) )
		{
			return;
		}

		if ( $result[ 'action' ] === 'empty' )
		{
			Helper::setOption( 'plugin_alert', '', TRUE );
			Helper::setOption( 'plugin_disabled', '0', TRUE );
		}
		else if ( $result[ 'action' ] === 'warning' && ! empty( $result[ 'message' ] ) )
		{
			Helper::setOption( 'plugin_alert', $result[ 'message' ], TRUE );
			Helper::setOption( 'plugin_disabled', '0', TRUE );
		}
		else if ( $result[ 'action' ] === 'disable' )
		{
			if ( ! empty( $result[ 'message' ] ) )
			{
				Helper::setOption( 'plugin_alert', $result[ 'message' ], TRUE );
			}

			Helper::setOption( 'plugin_disabled', '1', TRUE );
		}
		else if ( $result[ 'action' ] === 'error' )
		{
			if ( ! empty( $result[ 'message' ] ) )
			{
				Helper::setOption( 'plugin_alert', $result[ 'message' ], TRUE );
			}

			Helper::setOption( 'plugin_disabled', '2', TRUE );
		}

		if ( ! empty( $result[ 'remove_license' ] ) )
		{
			Helper::deleteOption( 'poster_plugin_installed', TRUE );
			Helper::deleteOption( 'poster_plugin_purchase_key', TRUE );
		}

		Helper::setOption( 'license_last_checked_time', time() );
	}

	public function checkLicense ()
	{
		$alert    = Helper::getOption( 'plugin_alert', '', TRUE );
		$disabled = Helper::getOption( 'plugin_disabled', '0', TRUE );

		if ( $disabled === '1' )
		{
			add_action( 'admin_menu', function () {
				add_menu_page( 'FS Poster (!)', 'FS Poster (!)', 'read', 'fs-poster', [
					$this,
					'app_disable'
				], Pages::asset( 'Base', 'img/logo_xs.png' ), 90 );
			} );

			return FALSE;
		}
		else if ( $disabled === '2' )
		{
			if ( ! empty( $alert ) )
			{
				echo $alert;
			}

			exit();
		}

		if ( ! empty( $alert ) )
		{
			add_action( 'admin_notices', function () use ( $alert ) {
				?>
				<div class="notice notice-error">
					<p><?php _e( $alert, 'fs-poster' ); ?></p>
				</div>
				<?php
			} );
		}

		return TRUE;
	}
}
