<?php

if ( ! defined( 'ABSPATH' ) ) exit;


    /**
     * WPPersian
     * 
     * @package WP-Persian
     * @author Siavash Salemi
     * @copyright 2018
     * @version $Id$
     * @access public
     */
class WP_Persian {

	private static $_instance=null;
	public $wpp_options;
	public $frontpage_locale;
	public $adminpanel_locale;
	public $user_locale;
	public $version="3.2.5";

	public static function getInstance()
	{
		if (is_null(self::$_instance) || !isset(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    /**
     * WP_Persian constructor.
     */
	protected function __construct() {
	    $this->includes();
        $this->adminpanel_locale=get_option( 'wpp_adminpanel_locale' );
        $this->frontpage_locale=get_option( 'wpp_frontpage_locale' );
        $this->load_plugins();
	}

	public function includes()
    {
        include_once(WPP_DIR.'includes/wpp-jdate.php');
        include_once(WPP_DIR.'includes/wpp-farsi.php');
        include_once(WPP_DIR.'includes/general-template.php');
        include_once(WPP_DIR.'includes/class-wpp-hooks.php');
        include_once(WPP_DIR.'includes/class-wpp-options.php');
        include_once(WPP_DIR.'includes/widgets/class-wpp-widget-jarchive.php');
        include_once(WPP_DIR.'includes/widgets/class-wpp-widget-jcalendar.php');
    }

    private function load_plugins(){
        include_once(WPP_DIR . 'plugins/wc-persian/wc-persian.php');
    }

	public function run() {
		register_activation_hook( WPP_FILE,  array($this, 'activate')  );
		register_deactivation_hook( WPP_FILE,  array($this,'deactivate')  );

        add_filter( 'locale', array( 'WPP_Hooks', 'wpp_set_locale' ),  10 );

		add_action( 'widgets_init', array($this, 'register_wpp_widgets') );

		add_action( 'init', array( $this, 'init' ), 10, 0 );



        add_filter( 'load_textdomain_mofile', array( $this, 'load_textdomain_mofile' ), 99, 2 );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 10, 0 );
		add_action( 'setup_theme', array( $this,'setup_theme'), 999, 1 );
		add_action( 'after_setup_theme', array( $this,'after_setup_theme'), 999, 1 );

		add_action( 'shutdown', array( $this, 'wp_shutdown' ), 10, 0 );

		if ( is_admin() ) {
			$this->admin_hooks();
		} else {
			$this->frontpage_hooks();
		}

		$this->wpp_options=WPP_Options::getInstance();
		$this->wpp_options->run();

	}

	public function wp_shutdown() {
	}

	/**
	 * action: init
	 * Fires after WordPress has finished loading but before any headers are sent
	 */
	public function init() {
		global  $wp_locale;
        $this->user_locale = get_user_locale(get_current_user_id());

		if ( is_admin() ) {
            add_filter('media_library_months_with_files', array('WPP_Hooks','wpp_media_library_months_with_files'),10,0 );
            add_filter('ajax_query_attachments_args', array('WPP_Hooks','wpp_ajax_query_attachments_args'),10,1 );

			if ( get_option( 'wpp_adminpanel_thousands_sep' ) ) {
				$wp_locale->number_format['thousands_sep'] = get_option( 'wpp_adminpanel_thousands_sep' );
			}
			if ( get_option( 'wpp_adminpanel_decimal_point' ) ) {
				$wp_locale->number_format['decimal_point'] = get_option( 'wpp_adminpanel_decimal_point' );
			}
		}else {
			if ( get_option( 'wpp_frontpage_thousands_sep' ) ) {
				$wp_locale->number_format['thousands_sep'] = get_option( 'wpp_frontpage_thousands_sep' );
			}
			if ( get_option( 'wpp_frontpage_decimal_point' ) ) {
				$wp_locale->number_format['decimal_point'] = get_option( 'wpp_frontpage_decimal_point' );
			}
		}

		if(get_option("wpp_tinymce_bidi_buttons")){
			add_filter( "mce_external_plugins", array('WPP_Hooks','wpp_mce_external_plugins')  );
			add_filter( "mce_buttons", array('WPP_Hooks','wpp_mce_buttons') );
		}

		if(get_option("wpp_tinymce_css")){
			add_action( 'mce_css', array('WPP_Hooks','wpp_mce_css') );
		}


	}
	public function setup_theme( $array) {

	}

	public function after_setup_theme( $array)
	{
		$this->load_repository_language();

	}

	private function load_repository_language()
	{
		global $wp_locale;

		unload_textdomain( 'default' );

        $locale=get_locale();

		if (file_exists( WPP_DIR . "repository/" . $locale . ".mo" )) {
			load_textdomain( 'default', WPP_DIR . "repository/" . $locale . ".mo" );
		}else{
			load_textdomain( 'default', WP_LANG_DIR . "/$locale.mo" );
		}

		if ( is_admin() || wp_installing() || ( defined( 'WP_REPAIRING' ) && WP_REPAIRING )) {
			if ( file_exists( WPP_DIR . "repository/admin-" . $locale . ".mo" ) ) {
				load_textdomain( 'default', WPP_DIR . "repository/admin-" . $locale . ".mo" );
			} else {
				load_textdomain( 'default', WP_LANG_DIR . "/admin-$locale.mo" );
			}
		}


		if ( isset( $GLOBALS['text_direction'] ) )
			$wp_locale->text_direction = $GLOBALS['text_direction'];
		elseif ( 'rtl' == _x( 'ltr', 'text direction' ) ) {
			$wp_locale->text_direction = 'rtl';
		}

		if ( 'rtl' === $wp_locale->text_direction && strpos( $GLOBALS['wp_version'], '-src' ) ) {
			$wp_locale->text_direction = 'ltr';
			add_action( 'all_admin_notices', array( $wp_locale, 'rtl_src_admin_notice' ) );
		}

	}


	/**
	 * action: plugins_loaded
	 * Runs after all plugins have been loaded.
	 */
	public function plugins_loaded() {
		load_plugin_textdomain( 'wp-persian', false, basename(WPP_DIR) . '/languages' );
	}

	public function add_settings_link($links) {
		if (current_user_can( 'manage_options' )){
			$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=wpp-options' ), __( 'Settings', 'wp-persian' ) );
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	private function admin_hooks() {
        //add_action('admin_notices', array($this,'wpp_admin_notice'));

        if ( get_option( 'wpp_adminpanel_convert_date' ) ) {
			add_filter( 'date_formats', array( 'WPP_Hooks', 'wpp_date_formats' ), 10, 1 );
            add_filter( "date_i18n", array( 'WPP_Hooks', 'wpp_date_i18n' ), 10, 4 );
			add_action( 'restrict_manage_posts', array( 'WPP_Hooks', 'wpp_restrict_manage_posts' ) );
            add_filter( 'disable_months_dropdown' , array( 'WPP_Hooks', 'wpp_disable_months_dropdown' ) , 10 , 2 );
			add_action( 'load-edit.php', array( 'WPP_Hooks', 'wpp_load_editphp' ), 10, 0 );
            add_action( 'load-upload.php', array( 'WPP_Hooks', 'wpp_load_editphp' ), 10, 0 );
		}
		add_action( 'admin_enqueue_scripts', array( 'WPP_Hooks', 'wpp_admin_enqueue_scripts' ) );

		add_action( 'load-options-general.php', array( 'WPP_Hooks', 'wpp_load_options_general' ), 10, 0 );

		if ( get_option( 'wpp_adminpanel_numbers_format_i18n' ) ) {
			add_filter( 'number_format_i18n', 'wpp_numbers_en2fa' );
		}

		add_filter( 'get_term', array( 'WPP_Hooks', 'wpp_get_term' ) );
		add_filter( 'get_comment', array( 'WPP_Hooks', 'wpp_comment' ) );
		add_filter( 'comment_save_pre', array( 'WPP_Hooks', 'wpp_comment' ) );

		if ( get_option( 'wpp_adminpanel_numbers_post_title' ) ) {
			add_filter( 'the_title', 'wpp_numbers_en2fa' );
		}
		if ( get_option( 'wpp_adminpanel_letters' ) ) {
			add_filter( 'the_title', 'wpp_letters_ar2fa' );
		}

		add_filter( 'plugin_action_links_'.plugin_basename(WPP_FILE), array( $this, 'add_settings_link' ) );
        add_filter( 'plugin_row_meta', array(  'WPP_Hooks', 'wpp_plugin_row_meta' ), 10, 2 );


        //add_action( 'edit_form_top', array( 'WPP_Hooks', 'wpp_edit_form_top' ) );
		add_action( 'save_post', array( 'WPP_Hooks', 'wpp_save_post' ), 10, 2 );

        add_action( 'woocommerce_before_save_order_items', array( 'WPP_Hooks', 'wpp_woocommerce_before_save_order_items' ), 10, 2 );


    }

	private function frontpage_hooks() {

		if ( get_option( 'wpp_frontpage_convert_date' ) ) {
		    # before ver 5.3.2
			#add_filter( "date_i18n", array( 'WPP_Hooks', 'wpp_date_i18n' ), 10, 4 );
            # from wordpress ver 5.3.2
            add_filter( "wp_date", array( 'WPP_Hooks', 'wpp_date_i18n' ), 10, 4 );

			if ( get_option( 'wpp_convert_permalink' ) ) {
				add_filter( "post_link", array( 'WPP_Hooks', 'wpp_post_link' ), 10, 3 );
			}
		}

		add_filter( "posts_where", array( 'WPP_Hooks', 'wpp_jalali_query' ), 10, 2 );
		add_filter( "pre_get_posts", array( 'WPP_Hooks', 'wpp_filter_posts' ) );



		if ( get_option( 'wpp_frontpage_numbers_format_i18n' ) ) {
			add_filter( 'number_format_i18n',  'wpp_numbers_en2fa' );
		}
		if ( get_option( 'wpp_frontpage_numbers_wp_title' ) ) {
			add_filter( 'wp_title', 'wpp_numbers_en2fa' );
		}
		if ( get_option( 'wpp_frontpage_numbers_the_title' ) ) {
			add_filter( 'the_title', 'wpp_numbers_en2fa' );
		}
		if ( get_option( 'wpp_frontpage_numbers_the_excerpt' ) ) {
			add_filter( 'the_excerpt', 'wpp_numbers_en2fa' );
		}
		if ( get_option( 'wpp_frontpage_numbers_the_content' ) ) {
			add_filter( 'the_content','wpp_numbers_en2fa' );
		}
		if ( get_option( 'wpp_frontpage_numbers_comment_text' ) ) {
			add_filter( 'comment_text', 'wpp_numbers_en2fa' );
		}
		if ( get_option( 'wpp_frontpage_numbers_comments_number' ) ) {
			add_filter( 'comments_number', 'wpp_numbers_en2fa' );
		}
		if ( get_option( 'wpp_frontpage_numbers_wp_list_categories' ) ) {
			add_filter( 'wp_list_categories', 'wpp_numbers_en2fa' );
		}

		if ( get_option( 'wpp_frontpage_letters' ) ) {
			add_filter( 'wp_title', 'wpp_letters_ar2fa' );
			add_filter( 'the_title', 'wpp_letters_ar2fa' );
			add_filter( 'the_excerpt','wpp_letters_ar2fa' );
			add_filter( 'the_content', 'wpp_letters_ar2fa' );
			add_filter( 'comment_text', 'wpp_letters_ar2fa' );
			add_filter( 'comments_number', 'wpp_letters_ar2fa' );
			add_filter( 'wp_list_categories', 'wpp_letters_ar2fa' );
			add_filter( 'the_category', 'wpp_letters_ar2fa' );
		}
	}


	public function load_textdomain_mofile($mofile, $domain) {
       $locale=get_locale();

		if ( file_exists( WPP_DIR . "repository/" . $domain . '/' . $locale . '.mo' ) ) {
			$mofile = WPP_DIR . "repository/" . $domain . '/' . $locale . '.mo';
		} elseif ( file_exists( WPP_DIR . "repository/" . $domain . '-' . $locale . '.mo' ) ) {
			$mofile = WPP_DIR . "repository/" . $domain . '-' . $locale . '.mo';
		}
		return $mofile;
	}

	public function register_wpp_widgets() {
		register_widget( 'WPP_Widget_JArchive' );
		register_widget( 'WPP_Widget_JCalendar' );
	}

	public function wpp_admin_notice()
    {
    }


	public function activate() {

		update_option( 'WPLANG', 'en_US' );

		//frontpage defaults
		update_option( 'wpp_frontpage_thousands_sep', ',' );
		update_option( 'wpp_frontpage_decimal_point', '.' );
		update_option( 'wpp_frontpage_locale', 'fa_IR' );
		update_option( 'wpp_frontpage_convert_date', 1 );
		update_option( 'wpp_convert_permalink', 1 );
		update_option( 'wpp_frontpage_numbers_the_content', 0 );
		update_option( 'wpp_frontpage_numbers_wp_title', 0 );
		update_option( 'wpp_frontpage_numbers_the_excerpt', 0 );
		update_option( 'wpp_frontpage_numbers_comment_text', 1 );
		update_option( 'wpp_frontpage_numbers_comments_number', 1 );
		update_option( 'wpp_frontpage_numbers_the_title', 0 );
		update_option( 'wpp_frontpage_numbers_wp_list_categories', 0 );
		update_option( 'wpp_frontpage_numbers_date_i18n', 1 );
		update_option( 'wpp_frontpage_numbers_format_i18n', 1 );
		update_option( 'wpp_frontpage_letters', 1 );

		//adminpanel defaults
		update_option( 'wpp_adminpanel_locale', 'fa_IR' );
		update_option( 'wpp_adminpanel_convert_date', 1 );
		update_option( 'wpp_adminpanel_thousands_sep', ',' );
		update_option( 'wpp_adminpanel_decimal_point', '.' );
		update_option( 'wpp_tinymce_bidi_buttons', 1 );
		update_option( 'wpp_tinymce_css', 1 );
		update_option( 'wpp_adminpanel_context', 1 );
		update_option( 'wpp_adminpanel_numbers_post_content', 0 );
		update_option( 'wpp_adminpanel_numbers_post_excerpt', 0 );
		update_option( 'wpp_adminpanel_numbers_post_title', 0 );
		update_option( 'wpp_adminpanel_numbers_get_term', 0 );
		update_option( 'wpp_adminpanel_numbers_comment', 0 );
		update_option( 'wpp_adminpanel_numbers_date_i18n', 1 );
		update_option( 'wpp_adminpanel_letters', 1 );
		update_option( 'wpp_adminpanel_numbers_format_i18n', 0 );

        update_option( 'wpp_adminpanel_font_main', 'Vazir' );
        update_option( 'wpp_adminpanel_font_h', 'Vazir' );
        update_option( 'wpp_adminpanel_font_nav', 'Vazir' );

        update_option( 'wpp_adminpanel_datepicker', 1 );

		update_option( 'wpp_installed_version', $this->version );

        if (!function_exists('wp_download_language_pack')) {
            require_once ABSPATH . '/wp-admin/includes/translation-install.php';
        }

        if (wp_can_install_language_pack()) {
            @wp_download_language_pack('fa_IR');
        }


	}

	public function deactivate() {
	    global $locale;

	    update_option( 'WPLANG', 'en_US' );
        $locale='en_US';
        update_option( 'wpp_adminpanel_locale', 'en_US' );
        update_option( 'wpp_frontpage_locale', 'en_US' );

	}


}
	
