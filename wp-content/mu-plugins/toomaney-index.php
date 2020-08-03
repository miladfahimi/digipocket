<?php

/*
Plugin Name: toomoney-index
Description: index post types for index queries
Author: Milad Fahimi
*/

// Hook <strong>lc_custom_post_custom_article()</strong> to the init action hook
add_action( 'init', 'custom_post_index' );

// The custom function to register a custom article post type
function custom_post_index() {

// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'Index' ),
'singular_name'      => __( 'Index' ),
'add_new'            => __( 'Add New Index' ),
'add_new_item'       => __( 'Add New Index' ),
'edit_item'          => __( 'Edit Index' ),
'new_item'           => __( 'New Index' ),
'all_items'          => __( 'All Index' ),
'view_item'          => __( 'View Index' ),
'search_items'       => __( 'Search Index' ),
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
'menu_icon'         => 'dashicons-money',
'capability_type'   => 'ads',
'map_meta_cap'      => true,
'supports'          => array( 'custom-fields'),
'has_archive'       => true,
'show_in_admin_bar' => true,
'show_in_nav_menus' => true,
'show_in_rest'      => true,
'query_var'         => true,
);

// Call the actual WordPress function
// Parameter 1 is a name for the post type
// Parameter 2 is the $args array
register_post_type( 'index', $args);

}

add_filter('manage_posts_columns', 'index_remove_unwanted_columns');
add_filter('manage_posts_columns', 'index_add_post_columns', 5);
add_action('manage_posts_custom_column', 'index_get_post_column_values', 5, 2);

// Remove unwanted columns
function index_remove_unwanted_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'index' ) {
    unset($defaults['title']);
    }
    return $defaults;
}

// Add new columns
function index_add_post_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'index' ) {
    // field vs displayed title
    $defaults['sek_buy'] = __('SEK Buy');
    $defaults['sek_sale'] = __('SEK Sale');
    $defaults['usd_buy'] = __('USD Buy');
    $defaults['usd_sale'] = __('USD Sale');
    $defaults['date'] = 'Status';
    }
    return $defaults;
}

// Populate the new columns with values
function index_get_post_column_values($column_name, $postID){
    $post_type = get_post_type();
    if ( $post_type == 'index' ) {
        if($column_name === 'sek_buy'){
            echo get_post_meta($postID,'sek_buy',TRUE);
        }elseif($column_name === 'sek_sale'){
            echo get_post_meta($postID,'sek_sale',TRUE);
        }elseif($column_name === 'buy_sale'){
            echo get_post_meta($postID,'buy_sale',TRUE);
        }elseif($column_name === 'usd_buy'){
            echo get_post_meta($postID,'usd_buy',TRUE);
        }elseif($column_name === 'usd_sale'){
            echo get_post_meta($postID,'usd_sale',TRUE);
        }elseif($column_name === 'date'){
            echo get_post_meta($postID,'date',TRUE);
        }elseif($column_name === 'author'){
            echo get_post_meta($postID,'author',TRUE);
        }
    }
}

?>