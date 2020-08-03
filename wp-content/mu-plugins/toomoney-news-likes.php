<?php

/*
Plugin Name: toomoney-ads
Description: Add post types for custom advertisements
Author: Milad Fahimi
*/

// Hook <strong>lc_custom_post_custom_article()</strong> to the init action hook
add_action( 'init', 'custom_post_likes' );

// The custom function to register a custom article post type
function custom_post_likes() {

// Set the labels, this variable is used in the $args array
$labels = array(
'name'               => __( 'likes' ),
'singular_name'      => __( 'like' ),
'add_new_item'       => __( 'Add new like' ),
'edit_item'          => __( 'Edit likes' ),
'all_items'          => __( 'All likes' ),
'view_item'          => __( 'View likes' ),
);

// The arguments for our post type, to be entered as parameter 2 of register_post_type()
$args = array(
    'labels'            => $labels,
    'description'       => 'Holds our custom like post specific data',
    'public'            => true,
    'show_ui'           => true,
    'menu_position'     => 5,
    'menu_icon'         => 'dashicons-heart',
    'capability_type'   => 'like',
    'map_meta_cap'      => true,
    'supports'          => array( 'title','custom-fields' ),
    'show_in_admin_bar' => true,
    'show_in_rest'      => true,
);

// Call the actual WordPress function
// Parameter 1 is a name for the post type
// Parameter 2 is the $args array
register_post_type( 'like', $args);

}


add_filter('manage_posts_columns', 'like_remove_unwanted_columns');
add_filter('manage_posts_columns', 'like_add_post_columns', 5);
add_action('manage_posts_custom_column', 'like_get_post_column_values', 5, 2);

// Remove unwanted columns
function like_remove_unwanted_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'like' ) {
    }
    return $defaults;
}

// Add new columns
function like_add_post_columns($defaults){
    $post_type = get_post_type();
    if ( $post_type == 'like' ) {
    // field vs displayed title
    $defaults['like_id'] = __('Like_ID');
    $defaults['date'] = 'Status';
    $defaults['author'] = 'User';
    }
    return $defaults;
}

// Populate the new columns with values
function like_get_post_column_values($column_name, $postID){
    $post_type = get_post_type();
    if ( $post_type == 'like' ) {
        if($column_name === 'like_id'){
            echo get_post_meta($postID,'like_id',TRUE);
        }elseif($column_name === 'date'){
            echo get_post_meta($postID,'date',TRUE);
        }elseif($column_name === 'author'){
            echo get_post_meta($postID,'author',TRUE);
        }
    }
}
?>