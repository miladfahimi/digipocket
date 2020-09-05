<?php

/*
Plugin Name: toomoney-ads
Description: Add post types for custom advertisements
Author: Milad Fahimi
*/

// Hook <strong>lc_custom_post_custom_article()</strong> to the init action hook
add_action( 'init', 'custom_post_part' );

// The custom function to register a custom article post type
function custom_post_part() {

// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'Parts' ),
'singular_name'      => __( 'Part' ),
'add_new'            => __( 'Add New Part' ),
'add_new_item'       => __( 'Add New Part' ),
'edit_item'          => __( 'Edit Part' ),
'new_item'           => __( 'New Part' ),
'all_items'          => __( 'All Part' ),
'view_item'          => __( 'View Part' ),
'search_items'       => __( 'Search Part' ),
'featured_image'     => 'Image',
'set_featured_image' => 'Add Image'
);

// The arguments for our post type, to be entered as parameter 2 of register_post_type()
$args = array(
'labels'            => $labels,
'description'       => 'Holds our part post specific data',
'public'            => true,
'menu_position'     => 5,
'menu_icon'         => 'dashicons-admin-page',
'capability_type'   => 'part',
'map_meta_cap'      => true,
'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments'),
'has_archive'       => true,
'show_in_admin_bar' => true,
'show_in_nav_menus' => true,
'show_in_rest'      => true,
'query_var'         => true,
'taxonomies'        => array( 'category', 'post_tag' ),
'show_in_menu' => 'edit.php?post_type=course'
);

// Call the actual WordPress function
// Parameter 1 is a name for the post type
// Parameter 2 is the $args array
register_post_type( 'part', $args);

}

add_filter('manage_posts_columns', 'part_remove_unwanted_columns');
add_filter('manage_posts_columns', 'part_add_post_columns', 6);
add_action('manage_posts_custom_column', 'part_get_post_column_values', 6, 3);

// Remove unwanted columns
function part_remove_unwanted_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'part' ) {
    unset($defaults['comments']);
    }
    return $defaults;
}

// Add new columns
function part_add_post_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'part' ) {
    // field vs displayed title
    }
    return $defaults;
}

// Populate the new columns with values
function part_get_post_column_values($column_name, $postID){
    $post_type = get_post_type();
    if ( $post_type == 'part' ) {
        if($column_name == 'related_course'){
            echo get_post_meta($postID,'related_course',TRUE);
        }
    }
}

?>