<?php

/*
Plugin Name: toomoney-course
Description: Add post types for Custom Post Courses
Author: Milad Fahimi
*/

// Hook <strong>custom_post_course()</strong> to the init action hook
add_action( 'init', 'custom_post_course' );

// The custom function to register a custom article post type
function custom_post_course() {

// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'Courses' ),
'singular_name'      => __( 'Course' ),
'add_new'            => __( 'Add New Course' ),
'add_new_item'       => __( 'Add New Course' ),
'edit_item'          => __( 'Edit Course' ),
'new_item'           => __( 'New Course' ),
'all_items'          => __( 'All Course' ),
'view_item'          => __( 'View Course' ),
'search_items'       => __( 'Search Course' ),
//'featured_image'     => 'Poster',
//'set_featured_image' => 'Add Poster'
);

// The arguments for our post type, to be entered as parameter 2 of register_post_type()
$args = array(
'labels'            => $labels,
'description'       => 'Holds our custom course post specific data',
'public'            => true,
'menu_position'     => 5,
'menu_icon'         => 'dashicons-book-alt',
'supports'          => array( 'title', 'editor','thumbnail' ),
'has_archive'       => true,
'show_in_admin_bar' => true,
'show_in_nav_menus' => true,
'show_in_rest'      => true,
'query_var'         => true,
'taxonomies'        => array( 'category', 'post_tag' ),
);

// Call the actual WordPress function
// Parameter 1 is a name for the post type
// Parameter 2 is the $args array
register_post_type( 'course', $args);

}
?>