<?php

namespace FSPoster\App\Providers;

class BackEnd
{
	use PluginMenu;

	private $active_custom_post_types;

	public function __construct ()
	{
		if ( ! Helper::pluginDisabled() )
		{
			new Ajax();
			new Popups();
		}

		$this->initMenu();

		$this->enqueueAssets();
		$this->updateService();
		$this->registerMetaBox();

		$this->registerActions();
		$this->registerBulkAction();
		$this->registerNotifications();
	}

	private function registerMetaBox ()
	{
		add_action( 'add_meta_boxes', function () {
			add_meta_box( 'fs_poster_meta_box', 'FS Poster', [
				$this,
				'publish_meta_box'
			], $this->getActiveCustomPostTypes(), 'side', 'high' );
		} );
	}

	public function publish_meta_box ( $post )
	{
		if ( in_array( $post->post_status, [ 'new', 'auto-draft', 'draft', 'pending' ] ) )
		{
			Pages::controller( 'Base', 'MetaBox', 'post_meta_box', [
				'post_id'          => $post->ID,
				'minified_metabox' => TRUE
			] );
		}
		else
		{
			Pages::controller( 'Base', 'MetaBox', 'post_meta_box_edit', [
				'post'             => $post,
				'minified_metabox' => TRUE
			] );
		}
	}

	private function enqueueAssets ()
	{
		add_action( 'admin_enqueue_scripts', function () {
			wp_register_script( 'fsp-select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js' );
			wp_enqueue_script( 'fsp-select2' );
			wp_register_script( 'fsp', Pages::asset( 'Base', 'js/fsp.js' ), [ 'jquery' ], NULL );
			wp_enqueue_script( 'fsp' );
			wp_localize_script( 'fsp', 'fspConfig', [
				'pagesURL' => plugins_url( 'Pages/', dirname( __FILE__ ) ),
				'siteURL'  => site_url()
			] );
			wp_localize_script( 'fsp', 'FSPObject', [
				'modals' => []
			] );
			$this->bulkScheduleAction();

			wp_enqueue_style( 'fsp-fonts', '//fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,600;1,400;1,600&display=swap' );
			wp_enqueue_style( 'fsp-fontawesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css' );
			wp_enqueue_style( 'fsp-select2', '//cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css' );
			wp_enqueue_style( 'fsp-ui', Pages::asset( 'Base', 'css/fsp-ui.css' ), [], NULL );
			wp_enqueue_style( 'fsp-select2-custom', Pages::asset( 'Base', 'css/fsp-select2.css' ), [
				'fsp-select2',
				'fsp-ui'
			], NULL );
		} );
	}

	private function updateService ()
	{
		$activationKey = Helper::getOption( 'poster_plugin_purchase_key', '', TRUE );

		if ( ! empty( $activationKey ) )
		{
			add_action( 'init', function () use ( $activationKey ) {
				$updater = new FSCodeUpdater( 'fs-poster', FS_API_URL . '/api.php', $activationKey );
			} );
		}
	}

	private function registerActions ()
	{
		$page                = Request::get( 'page', '', 'string' );
		$is_download_request = Request::get( 'download', '', 'string' );
		$exported_json       = Helper::getOption( 'exported_json_' . $is_download_request, '' );

		if ( $page === 'fs-poster-settings' && ! empty( $is_download_request ) && ! empty( $exported_json ) )
		{
			Helper::deleteOption( 'exported_json_' . $is_download_request );

			add_action( 'admin_init', function () use ( $exported_json, $is_download_request ) {
				header( 'Content-disposition: attachment; filename=fs_poster_' . $is_download_request . '.json' );
				header( 'Content-type: application/json' );

				exit( $exported_json );
			} );
		}

		if ( Helper::getOption( 'show_fs_poster_column', '1' ) )
		{
			$usedColumnsSave = [];

			foreach ( $this->getActiveCustomPostTypes() as $postType )
			{
				$postType = preg_replace( '/[^a-zA-Z0-9\-\_]/', '', $postType );

				switch ( $postType )
				{
					case 'post':
						$typeName = 'posts';
						break;
					case 'page':
						$typeName = 'pages';
						break;
					case 'attachment':
						$typeName = 'media';
						break;
					default:
						$typeName = $postType . '_posts';
				}

				add_action( 'manage_' . $typeName . '_custom_column', function ( $column_name, $post_id ) use ( &$usedColumnsSave ) {
					if ( $column_name === 'fsp-share-column' && get_post_status( $post_id ) === 'publish' && ! isset( $usedColumnsSave[ $post_id ] ) )
					{
						echo '<i class="fas fa-rocket fsp-tooltip" data-title="' . esc_html__( 'Share', 'fs-poster' ) . '" data-load-modal="share_saved_post" data-parameter-post_id="' . $post_id . '"></i><i class="fas fa-history fsp-tooltip" data-title="' . esc_html__( 'Schedule', 'fs-poster' ) . '" data-load-modal="add_schedule" data-parameter-post_ids="' . $post_id . '"></i>';

						$usedColumnsSave[ $post_id ] = TRUE;
					}
				}, 10, 2 );

				add_filter( 'manage_' . $typeName . '_columns', function ( $columns ) {
					if ( is_array( $columns ) && ! isset( $columns[ 'fsp-share-column' ] ) )
					{
						$columns[ 'fsp-share-column' ] = esc_html__( 'FS Poster', 'fs-poster' );
					}

					return $columns;
				} );

			}
		}
	}

	private function registerBulkAction ()
	{
		foreach ( $this->getActiveCustomPostTypes() as $postType )
		{
			if ( $postType === 'attachment' )
			{
				$postType = 'upload';
			}
			else
			{
				$postType = 'edit-' . $postType;
			}

			add_filter( 'bulk_actions-' . $postType, function ( $bulk_actions ) {
				$bulk_actions[ 'fs_schedule' ] = __( 'FS Poster: Schedule', 'fs-poster' );

				return $bulk_actions;
			} );

			add_filter( 'handle_bulk_actions-' . $postType, function ( $redirect_to, $doaction, $post_ids ) {
				if ( $doaction !== 'fs_schedule' )
				{
					return $redirect_to;
				}

				$redirect_to = add_query_arg( 'fs_schedule_posts', implode( ',', $post_ids ), $redirect_to );

				return $redirect_to;
			}, 10, 3 );
		}
	}

	private function getActiveCustomPostTypes ()
	{
		if ( is_null( $this->active_custom_post_types ) )
		{
			$this->active_custom_post_types = explode( '|', Helper::getOption( 'allowed_post_types', 'post|page|attachment|product' ) );
		}

		return $this->active_custom_post_types;
	}

	private function bulkScheduleAction ()
	{
		$posts = Request::get( 'fs_schedule_posts', '', 'string' );

		$posts    = explode( ',', $posts );
		$post_ids = [];
		foreach ( $posts as $post_id )
		{
			if ( is_numeric( $post_id ) && $post_id > 0 )
			{
				$post_ids[] = (int) $post_id;
			}
		}

		if ( empty( $post_ids ) )
		{
			return;
		}

		wp_add_inline_script( 'fsp', 'jQuery(document).ready(function(){ FSPoster.loadModal("add_schedule" , {"post_ids": "' . implode( ',', $post_ids ) . '"}) });' );
	}

	private function registerNotifications ()
	{
		add_action( 'init', function () {
			$plgnVer = Helper::getOption( 'poster_plugin_installed', '0', TRUE );

			if ( ! $plgnVer )
			{
				return;
			}

			$failed_accounts = DB::DB()->get_row( DB::DB()->prepare( 'SELECT COUNT(id) AS total FROM ' . DB::table( 'accounts' ) . ' WHERE status = \'error\' AND ( is_public = 1 OR user_id = %d ) AND blog_id=%d', [
				get_current_user_id(),
				Helper::getBlogId()
			] ), ARRAY_A );

			if ( $failed_accounts && $failed_accounts[ 'total' ] > 0 )
			{
				add_action( 'admin_notices', function () use ( $failed_accounts ) {
					$verb = (int) $failed_accounts[ 'total' ] >= 2 ? 'are' : 'is';

					echo '<div class="fsp-notification-container"><div class="fsp-notification"><div class="fsp-notification-info"><div class="fsp-notification-icon fsp-is-warning"></div><div class="fsp-notification-text"><div class="fsp-notification-status">' . esc_html__( 'FS Poster', 'fs-poster' ) . '</div><div class="fsp-notification-message">' . esc_html__( 'There', 'fs-poster' ) . ' ' . $verb . ' <b>' . $failed_accounts[ 'total' ] . '</b> ' . esc_html__( 'failed feed(s).', 'fs-poster' ) . '</div></div></div><div class="fsp-notification-buttons"><a class="fsp-button" href="' . admin_url() . 'admin.php?page=fs-poster-accounts">' . esc_html__( 'REVIEW ACCOUNTS', 'fs-poster' ) . '</a><button class="fsp-button fsp-is-gray fsp-close-notification">' . esc_html__( 'HIDE', 'fs-poster' ) . '</button></div></div></div>';
				} );
			}

			$failed_feeds = DB::DB()->get_row( DB::DB()->prepare( 'SELECT COUNT(id) AS total FROM ' . DB::table( 'feeds' ) . ' tb1 WHERE blog_id="' . Helper::getBlogId() . '" AND is_sended = 1 AND ( ( node_type="account" AND ( SELECT COUNT(0) FROM ' . DB::table( 'accounts' ) . ' tb2 WHERE tb2.id = tb1.node_id AND ( tb2.user_id = %d OR tb2.is_public=1) ) > 0 ) OR ( node_type <> "account" AND (SELECT COUNT(0) FROM ' . DB::table( 'account_nodes' ) . ' tb2 WHERE tb2.id = tb1.node_id AND ( tb2.user_id = %d ) > 0 OR tb2.is_public = 1 ) ) ) AND status = "error" AND is_seen = "0"', (int) get_current_user_id(), (int) get_current_user_id() ), ARRAY_A );

			if ( $failed_feeds && $failed_feeds[ 'total' ] > 0 )
			{
				add_action( 'admin_notices', function () use ( $failed_feeds ) {
					$verb = (int) $failed_feeds[ 'total' ] >= 2 ? 'are' : 'is';

					echo '<div class="fsp-notification-container"><div class="fsp-notification"><div class="fsp-notification-info"><div class="fsp-notification-icon fsp-is-warning"></div><div class="fsp-notification-text"><div class="fsp-notification-status">' . esc_html__( 'FS Poster', 'fs-poster' ) . '</div><div class="fsp-notification-message">' . esc_html__( 'There', 'fs-poster' ) . ' ' . $verb . ' <b>' . $failed_feeds[ 'total' ] . '</b> ' . esc_html__( 'failed feed(s).', 'fs-poster' ) . '</div></div></div><div class="fsp-notification-buttons"><a class="fsp-button" href="' . admin_url() . 'admin.php?page=fs-poster-logs&filter_by=error">' . esc_html__( 'GO TO THE LOGS', 'fs-poster' ) . '</a><button class="fsp-button fsp-is-gray fsp-close-notification" data-hide="true">' . esc_html__( 'HIDE', 'fs-poster' ) . '</button></div></div></div>';
				} );
			}

			$not_sended_feeds = DB::DB()->get_row( DB::DB()->prepare( 'SELECT COUNT(id) AS total FROM ' . DB::table( 'feeds' ) . ' tb1 WHERE blog_id="' . Helper::getBlogId() . '" AND is_sended = 0 AND ( ( node_type="account" AND ( SELECT COUNT(0) FROM ' . DB::table( 'accounts' ) . ' tb2 WHERE tb2.id = tb1.node_id AND ( tb2.user_id = %d OR tb2.is_public=1) ) > 0 ) OR ( node_type <> "account" AND (SELECT COUNT(0) FROM ' . DB::table( 'account_nodes' ) . ' tb2 WHERE tb2.id = tb1.node_id AND ( tb2.user_id = %d ) > 0 OR tb2.is_public = 1 ) ) ) AND status IS NULL AND share_on_background = 0', (int) get_current_user_id(), (int) get_current_user_id() ), ARRAY_A );

			if ( $not_sended_feeds && $not_sended_feeds[ 'total' ] > 0 )
			{
				add_action( 'admin_notices', function () use ( $not_sended_feeds ) {
					$verb = (int) $not_sended_feeds[ 'total' ] >= 2 ? 'are' : 'is';

					echo '<div class="fsp-notification-container"><div class="fsp-notification"><div class="fsp-notification-info"><div class="fsp-notification-icon fsp-is-warning"></div><div class="fsp-notification-text"><div class="fsp-notification-status">' . esc_html__( 'FS Poster', 'fs-poster' ) . '</div><div class="fsp-notification-message">' . esc_html__( 'There', 'fs-poster' ) . ' ' . $verb . ' <b>' . $not_sended_feeds[ 'total' ] . '</b> ' . esc_html__( 'feed(s) that require action.', 'fs-poster' ) . ' <i class="far fa-question-circle fsp-tooltip" data-title="' . esc_html__( 'If you didn\'t select to share posts in the background when sharing posts, for various reasons like refreshing the page might interrupt the sharing process. That will cause some posts to remain unshared. You can share them in the background or with pop-up.', 'fs-poster' ) . '"></i></div></div></div><div class="fsp-notification-buttons"><button id="fspNotificationShareWithPopup" class="fsp-button">' . esc_html__( 'SHARE WITH POP-UP', 'fs-poster' ) . '</button><button id="fspNotificationShareOnBackground" class="fsp-button">' . esc_html__( 'SHARE IN THE BACKGROUND', 'fs-poster' ) . '</button><button id="fspNotificationDoNotShare" class="fsp-button fsp-is-gray fsp-close-notification" data-hide="true">' . esc_html__( 'DON\'T SHARE', 'fs-poster' ) . '</button></div></div></div>';
				} );
			}
		} );
	}
}
