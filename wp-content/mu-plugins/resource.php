<?php

/*
Plugin Name: toomoney-ads
Description: Add post types for Custom Pro Resources
Author: Milad Fahimi
*/

// Hook <strong>lc_custom_post_custom_article()</strong> to the init action hook
add_action( 'init', 'custom_post_resource' );

// The custom function to register a custom article post type
function custom_post_resource() {

// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'Resources' ),
'singular_name'      => __( 'Resource' ),
'add_new'            => __( 'Add New Resource' ),
'add_new_item'       => __( 'Add New Resource' ),
'edit_item'          => __( 'Edit Resource' ),
'new_item'           => __( 'New Resource' ),
'all_items'          => __( 'All Resource' ),
'view_item'          => __( 'View Resource' ),
'search_items'       => __( 'Search Resource' ),
//'featured_image'     => 'Poster',
//'set_featured_image' => 'Add Poster'
);

// The arguments for our post type, to be entered as parameter 2 of register_post_type()
$args = array(
'labels'            => $labels,
'description'       => 'Holds our custom ads post specific data',
'public'            => true,
'menu_position'     => 5,
'menu_icon'         => 'dashicons-businessperson',
'supports'          => array( 'title', 'editor','thumbnail' ),
'rewrite'           => array( 'slug'=>'resources'),
'has_archive'       => true,
'show_in_admin_bar' => true,
'show_in_nav_menus' => true,
'show_in_rest'      => true,
'query_var'         => true,
);

// Call the actual WordPress function
// Parameter 1 is a name for the post type
// Parameter 2 is the $args array
register_post_type( 'resource', $args);

}
?>