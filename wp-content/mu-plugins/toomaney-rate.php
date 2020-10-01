<?php

/*
Plugin Name: toomoney-rate
Description: rate post types for rate queries
Author: Milad Fahimi
*/

// Hook <strong>lc_custom_post_custom_article()</strong> to the init action hook
add_action( 'init', 'custom_post_rate' );

// The custom function to register a custom article post type
function custom_post_rate() {

// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'Rates' ),
'singular_name'      => __( 'Rate' ),
'add_new'            => __( 'Add New Rate' ),
'add_new_item'       => __( 'Add New Rate' ),
'edit_item'          => __( 'Edit Rate' ),
'new_item'           => __( 'New Rate' ),
'all_items'          => __( 'All Rate' ),
'view_item'          => __( 'View Rate' ),
'search_items'       => __( 'Search Rate' ),
);

$args = array(
'labels'            => $labels,
'description'       => 'Holds our custom rate post specific data',
'public'            => true,
'show_ui'           => true,
'menu_position'     => 5,
'menu_icon'         => 'dashicons-money',
'capability_type'   => 'ads',
'map_meta_cap'      => true,
'supports'          => array('custom-fields'),
'has_archive'       => true,
'show_in_admin_bar' => true,
'show_in_nav_menus' => true,
'show_in_rest'      => true,
'query_var'         => true,
);

// Call the actual WordPress function
// Parameter 1 is a name for the post type
// Parameter 2 is the $args array
register_post_type( 'rate', $args);

}

add_filter('manage_posts_columns', 'rate_remove_unwanted_columns');
add_filter('manage_posts_columns', 'rate_add_post_columns', 5);
add_action('manage_posts_custom_column', 'rate_get_post_column_values', 5, 2);

// Remove unwanted columns
function rate_remove_unwanted_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'rate' ) {
    unset($defaults['title']);
    }
    return $defaults;
}

// Add new columns
function rate_add_post_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'rate' ) {
    // field vs displayed title
    $defaults['aed_usd'] = __('AED to USD');
    $defaults['usd_sek'] = __('USD to SEK');
    $defaults['usd_dkk'] = __('USD to DKK');
    $defaults['usd_nok'] = __('USD to NOK');
    $defaults['btc_usd'] = __('BTC to USD');
    $defaults['date'] = 'Status';
    }
    return $defaults;
}

// Populate the new columns with values
function rate_get_post_column_values($column_name, $postID){
    $post_type = get_post_type();
    if ( $post_type == 'rate' ) {
        if($column_name === 'aed_usd'){
            echo get_post_meta($postID,'aed_usd',TRUE);
        }elseif($column_name === 'usd_sek'){
            echo get_post_meta($postID,'usd_sek',TRUE);
        }elseif($column_name === 'usd_dkk'){
            echo get_post_meta($postID,'usd_dkk',TRUE);
        }elseif($column_name === 'usd_nok'){
            echo get_post_meta($postID,'usd_nok',TRUE);
        }elseif($column_name === 'btc_usd'){
            echo get_post_meta($postID,'btc_usd',TRUE);
        }elseif($column_name === 'date'){
            echo get_post_meta($postID,'date',TRUE);
        }
    }
}

?>