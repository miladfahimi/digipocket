<?php

/**
 * @wordpress-plugin
 * Plugin Name:       WC-Persian
 * Plugin URI:        https://wordpress.org/plugins/wp-persian/
 * Description:       Fast and powerful plugin based on WP-Persian for support jalali date in Woocommerce.
 * Version:           1.0.1
 * Author:            Siavash Salemi
 * Author URI:        http://www.30yavash.ir
 * License:           GPL2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-persian
 * Domain Path:       /languages

*/

if ( ! defined( 'WPINC' ) ) exit;


/** @define "WPP_DIR" "./" */

define('WCP_DIR', plugin_dir_path(__FILE__));

define('WCP_URL', plugin_dir_url(__FILE__));
//define('WPP_FILE',__FILE__);

function wcp_disable_jdate($callers)
{
/*
    for ($i=0;$i<count($callers);$i++){
        if (isset($callers[$i]['function']))
            error_log($i.' ' . $callers[$i]['function']);
        if (isset($callers[$i]['class']))
            error_log($i.' ' . $callers[$i]['class']);

    }
*/
    if ((class_exists('WooCommerce')) && (
            (isset($callers[4]['class'])) && ($callers[4]['class'] == 'WC_Meta_Box_Order_Data') ||
            (isset($callers[5]['class'])) && ($callers[5]['class'] == 'WC_Meta_Box_Order_Data') ||
            (isset($callers[6]['class'])) && ($callers[6]['class'] == 'WC_Meta_Box_Product_Data') ||
            (isset($callers[8]['class'])) && ($callers[8]['class'] == 'WC_Meta_Box_Product_Data')
        )) {
        return true;
    }

}
//if (class_exists( 'WP_Persian' ))
add_filter( 'wpp_disable_jalali_date', 'wcp_disable_jdate' , 10, 1 );

function wcp_admin_enqueue_scripts()
{

    if (class_exists('WooCommerce') && get_option( 'wpp_adminpanel_datepicker' )) {
        wp_enqueue_script('wcp-order', WCP_URL . 'assets/js/wc-persian.js');
        wp_enqueue_style('wc-persian', WCP_URL . 'assets/css/wc-persian.css');
    }
}

add_action( 'admin_enqueue_scripts','wcp_admin_enqueue_scripts' ) ;
