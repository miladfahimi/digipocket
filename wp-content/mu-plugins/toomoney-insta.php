<?php

/*
Plugin Name: toomoney-insta
Description: Add post types for custom advertisements
Author: Milad Fahimi
*/

// Hook <strong>custom_post_insta()</strong> to the init action hook
add_action( 'init', 'custom_post_insta' );

// The custom function to register a custom article post type
function custom_post_insta() {

// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'instagram' ),
'singular_name'      => __( 'instagram' ),
'add_new'            => __( 'Add New Insta' ),
'add_new_item'       => __( 'Add New Insta' ),
'edit_item'          => __( 'Edit Insta' ),
'new_item'           => __( 'New Insta' ),
'all_items'          => __( 'All Insta' ),
'view_item'          => __( 'View Insta' ),
'search_items'       => __( 'Search Insta' ),
);

// The arguments for our post type, to be entered as parameter 2 of register_post_type()
$args = array(
'labels'            => $labels,
'description'       => 'Holds our custom insta post specific data',
'public'            => true,
'show_ui'           => true,
'menu_position'     => 5,
'menu_icon'         => 'dashicons-tickets',
'capability_type'   => 'ads',
'map_meta_cap'      => true,
'has_archive'       => true,
'show_in_admin_bar' => true,
'show_in_nav_menus' => true,
'query_var'         => true,
);

// Call the actual WordPress function
// Parameter 1 is a name for the post type
// Parameter 2 is the $args array
register_post_type( 'insta', $args);

}