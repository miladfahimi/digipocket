<?php

/*
Plugin Name: toomoney-ads
Description: Add post types for custom advertisements
Author: Milad Fahimi
*/

// Hook <strong>lc_custom_post_custom_article()</strong> to the init action hook
add_action( 'init', 'custom_post_news' );

// The custom function to register a custom article post type
function custom_post_news() {

// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'News' ),
'singular_name'      => __( 'News' ),
'add_new'            => __( 'Add New News' ),
'add_new_item'       => __( 'Add New News' ),
'edit_item'          => __( 'Edit News' ),
'new_item'           => __( 'New News' ),
'all_items'          => __( 'All News' ),
'view_item'          => __( 'View News' ),
'search_items'       => __( 'Search News' ),
'featured_image'     => 'Image',
'set_featured_image' => 'Add Image'
);

// The arguments for our post type, to be entered as parameter 2 of register_post_type()
$args = array(
'labels'            => $labels,
'description'       => 'Holds our news post specific data',
'public'            => true,
'menu_position'     => 5,
'menu_icon'         => 'dashicons-text',
'capability_type'   => 'news',
'map_meta_cap'      => true,
'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
'has_archive'       => true,
'show_in_admin_bar' => true,
'show_in_nav_menus' => true,
'show_in_rest'      => true,
'query_var'         => true,
);

// Call the actual WordPress function
// Parameter 1 is a name for the post type
// Parameter 2 is the $args array
register_post_type( 'news', $args);

}
?>