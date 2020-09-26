<?php

if ( ! defined( 'ABSPATH' ) ) exit;


require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );


class WPP_Options {
	public $help_tabs = array();

	private static $instance;

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	protected function __construct() {

	}

	public function run() {
		add_action( "admin_menu", array( $this, 'admin_menu' ), 10, 0 );
		add_action( "admin_init", array( $this, 'register_fields' ), 10, 0 );
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {
		//add_filter( 'pre_update_option_wpp_adminpanel_locale', array('update_field_wpp_adminpanel_locale'), 10, 2 );
		//add_filter( 'pre_update_option', array($this,'wpp_pre_update_option'), 10, 3 );
	}

	public function admin_menu() {
		$this->help_tabs = array(
			'wpp_admin_panel' => array(
				'title'   => __( 'Administrator Panel', 'wp-persian' ),
				'content' => '<p>' . __( 'Change language and calendar in administrator panel.', 'wp-persian' ) . '</p>',
			),
			'wpp_frontpage'   => array(
				'title'   => __( 'Frontpage', 'wp-persian' ),
				'content' => '<p>' . __( 'Change language , calendar and numbers in frontpage.', 'wp-persian' ) . '</p>',
			)
		);
		$option_page     = add_options_page( WPPERSIAN_NICK . ' Plugin Options', __( 'Persian', 'wp-persian' ), 'manage_options', 'wpp-options', array(
			$this,
			'display'
		) );
		add_action( 'load-' . $option_page, array( $this, 'help_sidebar' ) );

	}

	public function help_sidebar() {
		$screen = get_current_screen();

		foreach ( $this->help_tabs as $id => $data ) {
			$screen->add_help_tab( array(
				'id'      => $id,
				'title'   => $data['title'],
				'content' => $data['content']
				//,'callback' => array( $this, 'prepare' )
			) );
		}
		$screen->set_help_sidebar(
			'<p><strong>' . __( 'For more information:', 'wp-persian' ) . '</strong></p>' .
			'<p><a href="http://www.30yavash.com/tag/wp-persian/" target="_blank">' . __( 'Support', 'wp-persian' ) . '</a></p>'
		);

	}

	public function register_fields() {

		//Section2 - Adminpanel
		add_settings_section(
			"wppo_adminpanel_section",
			__( "Administrator Panel", 'wp-persian' ),
			null,
			"wppo_adminpanel_page"
		);

		add_settings_field(
			"wpp_adminpanel_locale",
			__( "Language", 'wp-persian' ),
			array(
				$this,
				'element_adminpanel_locale'
			),
			"wppo_adminpanel_page",
			"wppo_adminpanel_section"
		);
        add_settings_field(
            "wpp_adminpanel_context",
            __( "Context", 'wp-persian' ),
            array(
                $this,
                'element_adminpanel_context'
            ),
            "wppo_adminpanel_page",
            "wppo_adminpanel_section"
        );

        add_settings_field(
			"wpp_adminpanel_convert_date",
			__( "Date & Time", 'wp-persian' ),
			array(
				$this,
				'element_adminpanel_convert_date'
			),
			"wppo_adminpanel_page",
			"wppo_adminpanel_section"
		);
		add_settings_field(
			"wpp_adminpanel_numbers",
			__( "Numbers", 'wp-persian' ),
			array(
				$this,
				'element_adminpanel_numbers'
			),
			"wppo_adminpanel_page",
			"wppo_adminpanel_section"
		);
		add_settings_field(
			"wpp_adminpanel_letters",
			__( "Letters", 'wp-persian' ),
			array(
				$this,
				'element_adminpanel_letters'
			),
			"wppo_adminpanel_page",
			"wppo_adminpanel_section"
		);
		add_settings_field(
			"wpp_wysiwyg_editor",
			__( "Editor", 'wp-persian' ),
			array(
				$this,
				'element_adminpanel_wysiwyg_editor'
			),
			"wppo_adminpanel_page",
			"wppo_adminpanel_section"
		);

        add_settings_field(
            "wpp_fonts",
            __( "Fonts", 'wp-persian' ),
            array(
                $this,
                'element_adminpanel_fonts'
            ),
            "wppo_adminpanel_page",
            "wppo_adminpanel_section"
        );


        add_settings_field(
            "wpp_adminpanel_datepicker",
            __( "Calendar", 'wp-persian' ),
            array(
                $this,
                'element_adminpanel_datepicker'
            ),
            "wppo_adminpanel_page",
            "wppo_adminpanel_section"
        );


        register_setting( "wppo_adminpanel_section", "wpp_adminpanel_locale", array(
			$this,
			"validate_locale"
		) );

		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_convert_date" );
		register_setting( "wppo_adminpanel_section", "wpp_tinymce_bidi_buttons" );
		register_setting( "wppo_adminpanel_section", "wpp_tinymce_css" );
		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_context" );
		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_numbers_post_content" );
		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_numbers_post_excerpt" );
		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_numbers_post_title" );
		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_numbers_get_term" );
		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_numbers_comment" );
		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_numbers_date_i18n" );
		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_letters" );
		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_thousands_sep" );
		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_decimal_point" );
		register_setting( "wppo_adminpanel_section", "wpp_adminpanel_numbers_format_i18n" );

        register_setting( "wppo_adminpanel_section", "wpp_adminpanel_font_main" );
        register_setting( "wppo_adminpanel_section", "wpp_adminpanel_font_h" );
        register_setting( "wppo_adminpanel_section", "wpp_adminpanel_font_nav" );

        register_setting( "wppo_adminpanel_section", "wpp_adminpanel_datepicker" );


		//Section1 - Frontpage
		add_settings_section(
			"wppo_frontpage_section",                //sectionID
			__( "Frontpage", 'wp-persian' ),
			null,
			"wppo_frontpage_page"                    //page
		);

		add_settings_field(
			"wpp_frontpage_locale",
			__( "Language", 'wp-persian' ),
			array(
				$this,
				'element_frontpage_locale'
			),
			"wppo_frontpage_page",                  //page
			"wppo_frontpage_section"                //sectionID
		);
		add_settings_field(
			"wpp_frontpage_convert_date",
			__( "Date & Time", 'wp-persian' ),
			array(
				$this,
				'element_frontpage_convert_date'
			),
			"wppo_frontpage_page",
			"wppo_frontpage_section"
		);
		add_settings_field(
			"wpp_frontpage_numbers",
			__( "Numbers", 'wp-persian' ),
			array(
				$this,
				'element_frontpage_numbers'
			),
			"wppo_frontpage_page",
			"wppo_frontpage_section"
		);
		add_settings_field(
			"wpp_frontpage_letters",
			__( "Letters", 'wp-persian' ),
			array(
				$this,
				'element_frontpage_letters'
			),
			"wppo_frontpage_page",
			"wppo_frontpage_section"
		);
		add_settings_field(
			"wpp_convert_permalink",
			__( "Permalink", 'wp-persian' ),
			array(
				$this,
				'element_frontpage_convert_permalink'
			),
			"wppo_frontpage_page",
			"wppo_frontpage_section"
		);

		register_setting( "wppo_frontpage_section", "wpp_frontpage_locale", array(
			$this,
			"validate_locale"
		) );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_convert_date" );
		register_setting( "wppo_frontpage_section", "wpp_convert_permalink" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_numbers_the_content" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_numbers_wp_title" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_numbers_the_excerpt" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_numbers_comment_text" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_numbers_comments_number" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_numbers_the_title" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_numbers_wp_list_categories" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_numbers_date_i18n" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_numbers_format_i18n" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_letters" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_thousands_sep" );
		register_setting( "wppo_frontpage_section", "wpp_frontpage_decimal_point" );



        //Section3 - Plugins
        add_settings_section(
            "wppo_plugins_section",                //sectionID
            __( "Plugins", 'wp-persian' ),
            null,
            "wppo_plugins_page"                    //page
        );

        add_settings_field(
            "wpp_plugins_active_plugins",
            __( "Active Plugins", 'wp-persian' ),
            array(
                $this,
                'element_plugins_list'
            ),
            "wppo_plugins_page",                  //page
            "wppo_plugins_section"                //sectionID
        );

    }

	public function validate_locale( $input ) {
		if ( ! isset( $input ) || empty( $input ) ) {
			$input = 'en_US';
		}

		return $input;
	}

    public function element_adminpanel_datepicker() {
        ?>
        <label for="wpp_adminpanel_datepicker">
            <input name="wpp_adminpanel_datepicker" type="checkbox" id="wpp_adminpanel_datepicker"
                   value="1" <?php checked( get_option( 'wpp_adminpanel_datepicker' ), 1, true ) ?>><?php _e( 'Enable Date Picker Calendar', 'wp-persian' ) ?>
        </label>
        <?php
    }


    public function element_plugins_list() {
        ?>
        <label for="wpp_plugin_woocommerce">
            <input name="wpp_plugin_woocommerce" type="checkbox" id="wpp_plugin_woocommerce"
                   value="1" <?php checked( get_option( 'wpp_plugin_woocommerce' ), 1, true ) ?>><?php _e( 'Enable Woocommerce Jalali Date Converter', 'wp-persian' ) ?>
        </label>
        <?php
    }

	public function element_adminpanel_context() {
		?>
		<label for="wpp_adminpanel_context">
			<input name="wpp_adminpanel_context" type="checkbox" id="wpp_adminpanel_context"
			       value="1" <?php checked( get_option( 'wpp_adminpanel_context' ), 1, true ) ?>><?php _e( 'Use Context Feature in administrator panel to use RTL direction in LTR Languages', 'wp-persian' ) ?>
		</label>
		<?php
	}

	public function element_adminpanel_convert_date() {
		?>
		<label for="wpp_adminpanel_convert_date">
			<input name="wpp_adminpanel_convert_date" type="checkbox" id="wpp_adminpanel_convert_date"
			       value="1" <?php checked( get_option( 'wpp_adminpanel_convert_date' ), 1, true ) ?>>
			<?php _e( 'Convert dates in wordpress administrator panel', 'wp-persian' ) ?></label>
		<?php
	}

	public function element_adminpanel_wysiwyg_editor() {
		?>
		<fieldset>
			<label for="wpp_tinymce_bidi_buttons">
				<input name="wpp_tinymce_bidi_buttons" type="checkbox" id="wpp_tinymce_bidi_buttons"
				       value="1" <?php echo get_option( 'wpp_tinymce_bidi_buttons' ) == '1' ? 'checked="checked"' : '' ?>>
				<?php _e( 'Use Bidirectional button in visual editor', 'wp-persian' ) ?></label><br>
			<label for="wpp_tinymce_css">
				<input name="wpp_tinymce_css" type="checkbox" id="wpp_tinymce_css"
				       value="1" <?php echo get_option( 'wpp_tinymce_css' ) == '1' ? 'checked="checked"' : '' ?>>
				<?php _e( 'Use RTL style for visual editor', 'wp-persian' ) ?></label>
		</fieldset>
		<?php
	}

    public function element_adminpanel_fonts() {
        ?>
        <label for="wpp_adminpanel_font_main">
        <select name="wpp_adminpanel_font_main" id="wpp_adminpanel_font_main">
            <option value="Segoe UI" <?php selected( get_option( 'wpp_adminpanel_font_main' ), 'Segoe UI', true ) ?>>Segoe UI</option>
            <option value="Tahoma" <?php selected( get_option( 'wpp_adminpanel_font_main' ), 'Tahoma', true ) ?>>Tahoma</option>
            <option value="Vazir" <?php selected( get_option( 'wpp_adminpanel_font_main' ), 'Vazir', true ) ?>>Vazir</option>
            <option value="Samim" <?php selected( get_option( 'wpp_adminpanel_font_main' ), 'Samim', true ) ?>>Samim</option>
            <option value="Shabnam" <?php selected( get_option( 'wpp_adminpanel_font_main' ), 'Shabnam', true ) ?>>Shabnam</option>
            <option value="Sahel" <?php selected( get_option( 'wpp_adminpanel_font_main' ), 'Sahel', true ) ?>>Sahel</option>
            <option value="Nahid" <?php selected( get_option( 'wpp_adminpanel_font_main' ), 'Nahid', true ) ?>>Nahid</option>
            <option value="Tanha" <?php selected( get_option( 'wpp_adminpanel_font_main' ), 'Tanha', true ) ?>>Tanha</option>
        </select> <?php _e('As main font','wp-persian') ?>
        </label><br />

<label for="wpp_adminpanel_font_h">
        <select name="wpp_adminpanel_font_h" id="wpp_adminpanel_font_h">
            <option value="Segoe UI" <?php selected( get_option( 'wpp_adminpanel_font_h' ), 'Segoe UI', true ) ?>>Segoe UI</option>
            <option value="Tahoma" <?php selected( get_option( 'wpp_adminpanel_font_h' ), 'Tahoma', true ) ?>>Tahoma</option>
            <option value="Vazir" <?php selected( get_option( 'wpp_adminpanel_font_h' ), 'Vazir', true ) ?>>Vazir</option>
            <option value="Samim" <?php selected( get_option( 'wpp_adminpanel_font_h' ), 'Samim', true ) ?>>Samim</option>
            <option value="Shabnam" <?php selected( get_option( 'wpp_adminpanel_font_h' ), 'Shabnam', true ) ?>>Shabnam</option>
            <option value="Sahel" <?php selected( get_option( 'wpp_adminpanel_font_h' ), 'Sahel', true ) ?>>Sahel</option>
            <option value="Nahid" <?php selected( get_option( 'wpp_adminpanel_font_h' ), 'Nahid', true ) ?>>Nahid</option>
            <option value="Tanha" <?php selected( get_option( 'wpp_adminpanel_font_h' ), 'Tanha', true ) ?>>Tanha</option>
        </select> <?php _e('As header font','wp-persian') ?>
</label><br />
<label for="wpp_adminpanel_font_nav">
        <select name="wpp_adminpanel_font_nav" id="wpp_adminpanel_font_nav">
            <option value="Segoe UI" <?php selected( get_option( 'wpp_adminpanel_font_nav' ), 'Segoe UI', true ) ?>>Segoe UI</option>
            <option value="Tahoma" <?php selected( get_option( 'wpp_adminpanel_font_nav' ), 'Tahoma', true ) ?>>Tahoma</option>
            <option value="Vazir" <?php selected( get_option( 'wpp_adminpanel_font_nav' ), 'Vazir', true ) ?>>Vazir</option>
            <option value="Samim" <?php selected( get_option( 'wpp_adminpanel_font_nav' ), 'Samim', true ) ?>>Samim</option>
            <option value="Shabnam" <?php selected( get_option( 'wpp_adminpanel_font_nav' ), 'Shabnam', true ) ?>>Shabnam</option>
            <option value="Sahel" <?php selected( get_option( 'wpp_adminpanel_font_nav' ), 'Sahel', true ) ?>>Sahel</option>
            <option value="Nahid" <?php selected( get_option( 'wpp_adminpanel_font_nav' ), 'Nahid', true ) ?>>Nahid</option>
            <option value="Tanha" <?php selected( get_option( 'wpp_adminpanel_font_nav' ), 'Tanha', true ) ?>>Tanha</option>

        </select> <?php _e('As navigation font','wp-persian') ?></label>
        <?php
    }

    public function element_adminpanel_locale() {
		$languages    = get_available_languages();
		$translations = wp_get_available_translations();
		if ( ! is_multisite() && defined( 'WPLANG' ) && '' !== WPLANG && 'en_US' !== WPLANG && ! in_array( WPLANG, $languages ) ) {
			$languages[] = WPLANG;
		}
		if (! in_array( 'fa_IR', $languages ) && file_exists(WPP_DIR.'repository/admin-fa_IR.mo')){
			$languages[] = 'fa_IR';
		}

		if ( ! empty( $languages ) || ! empty( $translations ) ) {
			$locale = get_option( 'wpp_adminpanel_locale' );
			if ( ! in_array( $locale, $languages ) ) {
				$locale = '';
			}
			wp_dropdown_languages( array(
				'name'                        => 'wpp_adminpanel_locale',
				'id'                          => 'wpp_adminpanel_locale',
				'selected'                    => $locale,
				'languages'                   => $languages,
				'translations'                => $translations,
				'show_available_translations' => false,
				//'show_available_translations' => ( ! is_multisite() || is_super_admin() ) && wp_can_install_language_pack(),
			) );
		}
	}

	public function element_frontpage_locale() {
		$languages    = get_available_languages();
		$translations = wp_get_available_translations();
		if ( ! is_multisite() && defined( 'WPLANG' ) && '' !== WPLANG && 'en_US' !== WPLANG && ! in_array( WPLANG, $languages ) ) {
			$languages[] = WPLANG;
		}

		if (! in_array( 'fa_IR', $languages ) && file_exists(WPP_DIR.'repository/fa_IR.mo')){
			$languages[] = 'fa_IR';
		}

		if ( ! empty( $languages ) || ! empty( $translations ) ) {
			$locale = get_option( 'wpp_frontpage_locale' );
			if ( ! in_array( $locale, $languages ) ) {
				$locale = '';
			}
			wp_dropdown_languages( array(
				'name'                        => 'wpp_frontpage_locale',
				'id'                          => 'wpp_frontpage_locale',
				'selected'                    => $locale,
				'languages'                   => $languages,
				'translations'                => $translations,
				'show_available_translations' => false,
				//'show_available_translations' => ( ! is_multisite() || is_super_admin() ) && wp_can_install_language_pack(),
			) );
		}
	}

	public function element_frontpage_convert_date() {
		?>
		<label for="wpp_frontpage_convert_date">
			<input name="wpp_frontpage_convert_date" type="checkbox" id="wpp_frontpage_convert_date"
			       value="1" <?php checked( get_option( 'wpp_frontpage_convert_date' ), 1, true ) ?>>
			<?php _e( 'Convert dates in wordpress frontpage', 'wp-persian' ) ?></label>
		<?php
	}

	public function element_frontpage_convert_permalink() {
		?>
		<label for="wpp_convert_permalink">
			<input name="wpp_convert_permalink" type="checkbox" id="wpp_convert_permalink"
			       value="1" <?php checked( get_option( 'wpp_convert_permalink' ), 1, true ) ?>>
			<?php _e( 'Convert dates in permalinks', 'wp-persian' ) ?></label>
		<?php
	}

	public function element_frontpage_numbers() {
		?>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'Numbers', 'wp-persian' ); ?></span></legend>
			<label for="wpp_frontpage_decimal_point">
				<span class="wpp-decimal-point"><?php _e( 'Decimal symbol:', 'wp-persian' ) ?></span> <select
					name="wpp_frontpage_decimal_point" id="wpp_frontpage_decimal_point">
					<option value="." <?php selected( get_option( 'wpp_frontpage_decimal_point' ), '.' ); ?>>.</option>
					<option value="/" <?php selected( get_option( 'wpp_frontpage_decimal_point' ), '/' ); ?>>/</option>
					<option value="'" <?php selected( get_option( 'wpp_frontpage_decimal_point' ), "'" ); ?>>'</option>
					<option value="," <?php selected( get_option( 'wpp_frontpage_decimal_point' ), ',' ); ?>>,</option>
				</select>
			</label><br>
			<label for="wpp_frontpage_thousands_sep">
				<span class="wpp-thousands-separator"><?php _e( 'Thousand separator symbol:', 'wp-persian' ) ?></span>
				<select name="wpp_frontpage_thousands_sep" id="wpp_frontpage_thousands_sep">
					<option value="," <?php selected( get_option( 'wpp_frontpage_thousands_sep' ), ',' ); ?>>,</option>
					<option value="." <?php selected( get_option( 'wpp_frontpage_thousands_sep' ), '.' ); ?>>.</option>
					<option value=" " <?php selected( get_option( 'wpp_frontpage_thousands_sep' ), ' ' ); ?>></option>
					<option value="'" <?php selected( get_option( 'wpp_frontpage_thousands_sep' ), "'" ); ?>>'</option>
				</select>
			</label><br>
			<label for="wpp_frontpage_numbers_wp_title">
				<input name="wpp_frontpage_numbers_wp_title" type="checkbox"
				       id="wpp_frontpage_numbers_wp_title"
				       value="1" <?php checked( get_option( 'wpp_frontpage_numbers_wp_title' ), 1, true ) ?>>
				<?php _e( 'Change numbers to Farsi in Page Title', 'wp-persian' ) ?></label><br>
			<label for="wpp_frontpage_numbers_the_title">
				<input name="wpp_frontpage_numbers_the_title" type="checkbox"
				       id="wpp_frontpage_numbers_the_title"
				       value="1" <?php checked( get_option( 'wpp_frontpage_numbers_the_title' ), 1, true ) ?>>
				<?php _e( 'Change numbers to Farsi in Post Title', 'wp-persian' ) ?></label><br>
			<label for="wpp_frontpage_numbers_the_excerpt">
				<input name="wpp_frontpage_numbers_the_excerpt" type="checkbox"
				       id="wpp_frontpage_numbers_the_excerpt"
				       value="1" <?php checked( get_option( 'wpp_frontpage_numbers_the_excerpt' ), 1, true ) ?>>
				<?php _e( 'Change numbers to Farsi in Post excerpt', 'wp-persian' ) ?></label><br>
			<label for="wpp_frontpage_numbers_the_content">
				<input name="wpp_frontpage_numbers_the_content" type="checkbox"
				       id="wpp_frontpage_numbers_the_content"
				       value="1" <?php checked( get_option( 'wpp_frontpage_numbers_the_content' ), 1, true ) ?>>
				<?php _e( 'Change numbers to Farsi in Post Content', 'wp-persian' ) ?></label><br>
			<label for="wpp_frontpage_numbers_comment_text">
				<input name="wpp_frontpage_numbers_comment_text" type="checkbox"
				       id="wpp_frontpage_numbers_comment_text"
				       value="1" <?php checked( get_option( 'wpp_frontpage_numbers_comment_text' ), 1, true ) ?>>
				<?php _e( 'Change numbers to Farsi in Comments', 'wp-persian' ) ?></label><br>
			<label for="wpp_frontpage_numbers_comments_number">
				<input name="wpp_frontpage_numbers_comments_number" type="checkbox"
				       id="wpp_frontpage_numbers_comments_number"
				       value="1" <?php checked( get_option( 'wpp_frontpage_numbers_comments_number' ), 1, true ) ?>>
				<?php _e( 'Change numbers to Farsi in Comments count', 'wp-persian' ) ?></label><br>
			<label for="wpp_frontpage_numbers_wp_list_categories">
				<input name="wpp_frontpage_numbers_wp_list_categories" type="checkbox"
				       id="wpp_frontpage_numbers_wp_list_categories"
				       value="1" <?php checked( get_option( 'wpp_frontpage_numbers_wp_list_categories' ), 1, true ) ?>>
				<?php _e( 'Change numbers to Farsi in Categories', 'wp-persian' ) ?></label><br>
			<label for="wpp_frontpage_numbers_date_i18n">
				<input name="wpp_frontpage_numbers_date_i18n" type="checkbox"
				       id="wpp_frontpage_numbers_date_i18n"
				       value="1" <?php checked( get_option( 'wpp_frontpage_numbers_date_i18n' ), 1, true ) ?>>
				<?php _e( 'Change numbers to Farsi in Dates', 'wp-persian' ) ?></label><br>
			<label for="wpp_frontpage_numbers_format_i18n">
				<input name="wpp_frontpage_numbers_format_i18n" type="checkbox" id="wpp_frontpage_numbers_format_i18n"
				       value="1" <?php checked( get_option( 'wpp_frontpage_numbers_format_i18n' ), 1, true ) ?>>
				<?php _e( 'Change other numbers to Farsi', 'wp-persian' ) ?></label>

		</fieldset>
		<?php
	}

	public function element_frontpage_letters() {
		?>
		<label for="wpp_frontpage_letters">
			<input name="wpp_frontpage_letters" type="checkbox" id="wpp_frontpage_letters"
			       value="1" <?php checked( get_option( 'wpp_frontpage_letters' ), 1, true ) ?>>
			<?php _e( 'Change arabic letters to farsi', 'wp-persian' ) ?></label><br>

		<?php
	}

	public function element_adminpanel_numbers() {
		?>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'Numbers', 'wp-persian' ); ?></span></legend>
			<label for="wpp_adminpanel_decimal_point">
				<span class="wpp-decimal-point"><?php _e( 'Decimal symbol:', 'wp-persian' ) ?></span> <select
					name="wpp_adminpanel_decimal_point" id="wpp_adminpanel_decimal_point">
					<option value="." <?php selected( get_option( 'wpp_adminpanel_decimal_point' ), '.' ); ?>>.</option>
					<option value="/" <?php selected( get_option( 'wpp_adminpanel_decimal_point' ), '/' ); ?>>/</option>
					<option value="'" <?php selected( get_option( 'wpp_adminpanel_decimal_point' ), '\'' ); ?>>'
					</option>
					<option value="," <?php selected( get_option( 'wpp_adminpanel_decimal_point' ), ',' ); ?>>,</option>
				</select>
			</label><br>
			<label for="wpp_adminpanel_thousands_sep">
				<span class="wpp-thousands-separator"><?php _e( 'Thousand separator symbol:', 'wp-persian' ) ?></span>
				<select name="wpp_adminpanel_thousands_sep" id="wpp_adminpanel_thousands_sep">
					<option value="," <?php selected( get_option( 'wpp_adminpanel_thousands_sep' ), ',' ); ?>>,</option>
					<option value="." <?php selected( get_option( 'wpp_adminpanel_thousands_sep' ), '.' ); ?>>.</option>
					<option value=" " <?php selected( get_option( 'wpp_adminpanel_thousands_sep' ), ' ' ); ?>></option>
					<option value="'" <?php selected( get_option( 'wpp_adminpanel_thousands_sep' ), '\'' ); ?>>'
					</option>
				</select>
			</label><br>
			<label for="wpp_adminpanel_numbers_post_title">
				<input name="wpp_adminpanel_numbers_post_title" type="checkbox" id="wpp_adminpanel_numbers_post_title"
				       value="1" <?php checked( get_option( 'wpp_adminpanel_numbers_post_title' ), 1, true ) ?>>
				<?php _e( 'Change &amp; Save numbers to Farsi in Post Title', 'wp-persian' ) ?></label><br>
			<label for="wpp_adminpanel_numbers_post_excerpt">
				<input name="wpp_adminpanel_numbers_post_excerpt" type="checkbox"
				       id="wpp_adminpanel_numbers_post_excerpt"
				       value="1" <?php checked( get_option( 'wpp_adminpanel_numbers_post_excerpt' ), 1, true ) ?>>
				<?php _e( 'Change &amp; Save numbers to Farsi in Post excerpt', 'wp-persian' ) ?></label><br>
			<label for="wpp_adminpanel_numbers_post_content">
				<input name="wpp_adminpanel_numbers_post_content" type="checkbox"
				       id="wpp_adminpanel_numbers_post_content"
				       value="1" <?php checked( get_option( 'wpp_adminpanel_numbers_post_content' ), 1, true ) ?>>
				<?php _e( 'Change &amp; Save numbers to Farsi in Post Content', 'wp-persian' ) ?></label><br>
			<?php /* ?>
		<label for="wpp_adminpanel_numbers_comment_text">
			<input name="wpp_adminpanel_numbers_comment_text" type="checkbox" id="wpp_adminpanel_numbers_comment_text" value="1" <?php checked(get_option('wpp_adminpanel_numbers_comment_text'),1,true) ?>>
			<?php _e('Convert numbers in Comments','wp-persian') ?></label><br>
		<label for="wpp_adminpanel_numbers_comments_number">
			<input name="wpp_adminpanel_numbers_comments_number" type="checkbox" id="wpp_adminpanel_numbers_comments_number" value="1" <?php checked(get_option('wpp_adminpanel_numbers_comments_number'),1,true) ?>>
			<?php _e('Convert Comments count','wp-persian') ?></label><br>
        <?php */ ?>
			<label for="wpp_adminpanel_numbers_get_term">
				<input name="wpp_adminpanel_numbers_get_term" type="checkbox" id="wpp_adminpanel_numbers_get_term"
				       value="1" <?php checked( get_option( 'wpp_adminpanel_numbers_get_term' ), 1, true ) ?>>
				<?php _e( 'Change &amp; Save numbers to Farsi in Categories', 'wp-persian' ) ?></label><br>
			<label for="wpp_adminpanel_numbers_comment">
				<input name="wpp_adminpanel_numbers_comment" type="checkbox" id="wpp_adminpanel_numbers_comment"
				       value="1" <?php checked( get_option( 'wpp_adminpanel_numbers_comment' ), 1, true ) ?>>
				<?php _e( 'Change &amp; Save numbers to Farsi in Comments', 'wp-persian' ) ?></label><br>
			<label for="wpp_adminpanel_numbers_date_i18n">
				<input name="wpp_adminpanel_numbers_date_i18n" type="checkbox" id="wpp_adminpanel_numbers_date_i18n"
				       value="1" <?php checked( get_option( 'wpp_adminpanel_numbers_date_i18n' ), 1, true ) ?>>
				<?php _e( 'Change numbers to Farsi in Dates', 'wp-persian' ) ?></label><br>
			<label for="wpp_adminpanel_numbers_format_i18n">
				<input name="wpp_adminpanel_numbers_format_i18n" type="checkbox" id="wpp_adminpanel_numbers_format_i18n"
				       value="1" <?php checked( get_option( 'wpp_adminpanel_numbers_format_i18n' ), 1, true ) ?>>
				<?php _e( 'Change other numbers to Farsi', 'wp-persian' ) ?></label>
		</fieldset>
		<?php
	}

	public function element_adminpanel_letters() {
		?>
		<label for="wpp_adminpanel_letters">
			<input name="wpp_adminpanel_letters" type="checkbox" id="wpp_adminpanel_letters"
			       value="1" <?php checked( get_option( 'wpp_adminpanel_letters' ), 1, true ) ?>>
			<?php _e( 'Change arabic letters to farsi', 'wp-persian' ) ?></label><br>
		<?php
	}

	public function display() {
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'frontpage';

		?>
		<div class="wrap">
			<h1><?php _e( 'Persian', 'wp-persian' ); ?></h1>
			<h2 class="nav-tab-wrapper">
				<a href="?page=wpp-options&tab=frontpage"
				   class="nav-tab <?php echo $active_tab == 'frontpage' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Frontpage', 'wp-persian' ); ?></a>
				<a href="?page=wpp-options&tab=adminpanel"
				   class="nav-tab <?php echo $active_tab == 'adminpanel' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Adminpanel', 'wp-persian' ); ?></a>
                <?php /* ?>
                <a href="?page=wpp-options&tab=plugins"
                   class="nav-tab <?php echo $active_tab == 'plugins' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Plugins', 'wp-persian' ); ?></a>
                <?php */ ?>
			</h2>
			<form method="post" action="options.php">
				<?php

				if ( 'adminpanel' == $active_tab ) {
					settings_fields( "wppo_adminpanel_section" );
					do_settings_sections( "wppo_adminpanel_page" );

				}
				/*
				else if ( 'plugins' == $active_tab ) {
                    settings_fields( "wppo_plugins_section" );
                    do_settings_sections( "wppo_plugins_page" );

                }
                */
                else {
					settings_fields( "wppo_frontpage_section" );
					do_settings_sections( "wppo_frontpage_page" );
				}
				submit_button();
				?>
			</form>
		</div>
		<?php
	}


}

?>
