<?php
 
function toomoneyLikeRoutes(){
    register_rest_route('tm/v1', 'likes', array(
        'methods' => 'POST',
        'callback'=> 'makelike'
    ));
    register_rest_route('tm/v1', 'likes', array(
        'methods' => 'DELETE',
        'callback'=> 'removelike'
    ));
    register_rest_route('tm/v1', 'likes', array(
        'callback'=> 'showlike'
    ));

}

function showlike(){
    $existQuery = new WP_Query(array(
        'author' => get_current_user_id(),
        'post_type' => 'like'
    ));
    return $existQuery;
}
function makelike(){
    if (is_user_logged_in()) {
        $item = sanitize_text_field($data['like_id']);

        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
              array(
                'key' => 'like_id',
                'compare' => '=',
                'value' => $item
              )
            )
          ));

          if ($existQuery->found_posts == 0 ) {
            return wp_insert_post(array(
              'post_type' => 'like',
              'meta_input' => array(
                'like_id' => $item
              )
            ));
          } else {
            die("already liked");
          }
      
          
    } else {
        die("Only logged in users can create a like.");
    }
}

function removelike(){
    $item = sanitize_text_field($data['like_id']);
    $existQuery = new WP_Query(array(
        'author' => get_current_user_id(),
        'post_type' => 'like',
        'meta_query' => array(
          array(
            'key' => 'like_id',
            'compare' => '=',
            'value' => $item
          )
        )
      ));
    if (get_current_user_id() == get_post_field('post_author', get_current_user_id())) {
      wp_delete_post($existQuery, true);
      return 'Congrats, like deleted.';
    } else {
      die("You do not have permission to delete that.");
    }
}


add_action('rest_api_init','toomoneyLikeRoutes');

?>