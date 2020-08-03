<?php

/*
Plugin Name: toomoney-slide
Description: Add post types for custom slide
Author: Milad Fahimi
*/

// Hook <strong>lc_custom_post_custom_slide()</strong> to the init action hook
add_action( 'init', 'custom_post_slide' );

// The custom function to register a custom article post type
function custom_post_slide() {

// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'Slides' ),
'singular_name'      => __( 'Slide' ),
'add_new'            => __( 'Add New Slides' ),
'add_new_item'       => __( 'Add New Slides' ),
'edit_item'          => __( 'Edit Slides' ),
'new_item'           => __( 'New Slides' ),
'all_items'          => __( 'All Slides' ),
'view_item'          => __( 'View Slides' ),
'search_items'       => __( 'Search Slides' ),
'featured_image'     => 'Poster',
'set_featured_image' => 'Add Poster'
);

// The arguments for our post type, to be entered as parameter 2 of register_post_type()
$args = array(
'labels'            => $labels,
'description'       => 'Holds our custom ads post specific data',
'public'            => true,
'menu_position'     => 5,
'menu_icon'         => 'dashicons-images-alt',
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
register_post_type( 'slide', $args);

}

add_filter('manage_posts_columns', 'slide_remove_unwanted_columns');
add_filter('manage_posts_columns', 'slide_add_post_columns', 5);
add_action('manage_posts_custom_column', 'slide_get_post_column_values', 5, 2);

// Remove unwanted columns
function slide_remove_unwanted_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'slide' ) {
    unset($defaults['title']);
    unset($defaults['date']);
    unset($defaults['amount']);
    unset($defaults['index']);
    unset($defaults['post_status']);
    unset($defaults['buy_sale']);
    }
    return $defaults;
}

// Add new columns
function slide_add_post_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'slide' ) {
    // field vs displayed title
    $defaults['header_1'] = __('Header');
    $defaults['header_2'] = __('Header 2');
    $defaults['header_3'] = __('Header 3');
    $defaults['back_ground'] = __('Back Ground');
    }
    return $defaults;
}

// Populate the new columns with values
function slide_get_post_column_values($column_name, $postID){
    $post_type = get_post_type();
    if ( $post_type == 'slide' ) {
        if($column_name === 'header_1'){
            echo get_post_meta($postID,'header_1',TRUE);
        }
        if($column_name === 'header_2'){
            echo get_post_meta($postID,'header_2',TRUE);
        }
        if($column_name === 'header_3'){
            echo get_post_meta($postID,'header_3',TRUE);
        }
        if($column_name === 'back_ground'){
            echo get_post_meta($postID,'back_ground',TRUE);
        }
    }
}
?>