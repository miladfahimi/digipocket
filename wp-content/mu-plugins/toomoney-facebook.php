<?php

/*
Plugin Name: toomoney-facebook
Description: Add post types for custom advertisements
Author: Milad Fahimi
*/

// Hook <strong>custom_post_facebook()</strong> to the init action hook
add_action( 'init', 'custom_post_facebook' );

// The custom function to register a custom article post type
function custom_post_facebook() {

// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'Facebook' ),
'singular_name'      => __( 'Facebook' ),
'add_new'            => __( 'Add New Facebook' ),
'add_new_item'       => __( 'Add New Facebook' ),
'edit_item'          => __( 'Edit Facebook' ),
'new_item'           => __( 'New Facebook' ),
'all_items'          => __( 'All Facebook' ),
'view_item'          => __( 'View Facebook' ),
'search_items'       => __( 'Search Facebook' ),
);

// The arguments for our post type, to be entered as parameter 2 of register_post_type()
$args = array(
    'labels'            => $labels,
    'description'       => 'Holds our custom facebook post specific data',
    'public'            => true,
    'show_ui'           => true,
    'menu_position'     => 5,
    'menu_icon'         => 'dashicons-facebook',
    'supports'          => array( 'title', 'editor','custom-fields','thumbnail'),
    'has_archive'       => true,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'show_in_rest'      => true,
    'query_var'         => true,
    'taxonomies'          => array( 'topics','category' ),
);

register_post_type( 'facebook', $args);
}