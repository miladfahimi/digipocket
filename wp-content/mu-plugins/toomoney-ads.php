<?php

/*
Plugin Name: toomoney-ads
Description: Add post types for custom advertisements
Author: Milad Fahimi
*/

// Hook <strong>lc_custom_post_custom_article()</strong> to the init action hook
add_action( 'init', 'custom_post_ads' );

// The custom function to register a custom article post type
function custom_post_ads() {

// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'Advertisements' ),
'singular_name'      => __( 'Advertisement' ),
'add_new'            => __( 'Add New Ads' ),
'add_new_item'       => __( 'Add New Ads' ),
'edit_item'          => __( 'Edit Ads' ),
'new_item'           => __( 'New Ads' ),
'all_items'          => __( 'All Ads' ),
'view_item'          => __( 'View Ads' ),
'search_items'       => __( 'Search Ads' ),
'featured_image'     => 'Poster',
'set_featured_image' => 'Add Poster'
);

// The arguments for our post type, to be entered as parameter 2 of register_post_type()
$args = array(
'labels'            => $labels,
'description'       => 'Holds our custom ads post specific data',
'public'            => true,
'show_ui'           => true,
'menu_position'     => 5,
'menu_icon'         => 'dashicons-tickets',
'capability_type'   => 'ads',
'map_meta_cap'      => true,
'supports'          => array( 'custom-fields' ),
'has_archive'       => true,
'show_in_admin_bar' => true,
'show_in_nav_menus' => true,
'show_in_rest'      => true,
'query_var'         => true,
);

// Call the actual WordPress function
// Parameter 1 is a name for the post type
// Parameter 2 is the $args array
register_post_type( 'ads', $args);

}

add_filter('manage_posts_columns', 'ads_remove_unwanted_columns');
add_filter('manage_posts_columns', 'ads_add_post_columns', 5);
add_action('manage_posts_custom_column', 'ads_get_post_column_values', 5, 2);

// Remove unwanted columns
function ads_remove_unwanted_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'ads' ) {
    unset($defaults['title']);
    }
    return $defaults;
}

// Add new columns
function ads_add_post_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'ads' ) {
    // field vs displayed title
    $defaults['index'] = __('Index');
    $defaults['amount'] = __('Amount');
    $defaults['buy_sale'] = __('Buy/Sale');
    $defaults['date'] = 'Status';
    $defaults['author'] = 'User';
    }
    return $defaults;
}

// Populate the new columns with values
function ads_get_post_column_values($column_name, $postID){
    $post_type = get_post_type();
    if ( $post_type == 'ads' ) {
        if($column_name === 'index'){
            echo get_post_meta($postID,'index',TRUE);
        }elseif($column_name === 'amount'){
            echo get_post_meta($postID,'amount',TRUE);
        }elseif($column_name === 'buy_sale'){
            echo get_post_meta($postID,'buy_sale',TRUE);
        }elseif($column_name === 'date'){
            echo get_post_meta($postID,'date',TRUE);
        }elseif($column_name === 'author'){
            echo get_post_meta($postID,'author',TRUE);
        }
    }
}

?>