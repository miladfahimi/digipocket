<?php

/**
 * @wordpress-plugin
 * Plugin Name:       WP-Persian
 * Plugin URI:        https://wordpress.org/plugins/wp-persian/
 * Description:       Fast and powerful plugin for support jalali date and persian language in wordpress and woocommerce and all other standard plugins.
 * Version:           3.3.0
 * Author:            Siavash Salemi
 * Author URI:        http://www.30yavash.ir
 * License:           GPL2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-persian
 * Domain Path:       /languages

*/

if ( ! defined( 'WPINC' ) ) exit;


//define('WPPERSIAN_ID', 'wppersian');
define('WPPERSIAN_NICK', 'WP Persian');
//define('WPPERSIAN_VER', '3.2.3');

/** @define "WPP_DIR" "./" */
define('WPP_DIR', plugin_dir_path(__FILE__));
define('WPP_URL', plugin_dir_url(__FILE__));
define('WPP_FILE',__FILE__);


require_once(WPP_DIR.'includes/class-wp-persian.php');
$wp_persian=WP_Persian::getInstance();
$wp_persian->run();


