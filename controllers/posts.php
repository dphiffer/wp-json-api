<?php
/*
Controller name: Posts
Controller description: Data manipulation methods for posts
*/

class JSON_API_Posts_Controller {

  public function create_post() {
    global $json_api;
    if (!current_user_can('edit_posts')) {
      $json_api->error("You need to login with a user that has 'edit_posts' capacity.", 403);
    }
    if (!$json_api->query->nonce) {
      $json_api->error("You must include a 'nonce' value to create posts. Use the `get_nonce` Core API method.", 403);
    }
    $nonce_id = $json_api->get_nonce_id('posts', 'create_post');
    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.", 403);
    }
    nocache_headers();
    $post = new JSON_API_Post();
    $id = $post->create($_REQUEST);
    if (empty($id)) {
      $json_api->error("Could not create post.", 500);
    }
    return array(
      'post' => $post
    );
  }
  
  public function update_post() {
    global $json_api;
    $post = $json_api->introspector->get_current_post();
    if (empty($post)) {
      $json_api->error("Post not found.");
    }
    if (!current_user_can('edit_post', $post->ID)) {
      $json_api->error("You need to login with a user that has the 'edit_post' capacity for that post.", 403);
    }
    if (!$json_api->query->nonce) {
      $json_api->error("You must include a 'nonce' value to update posts. Use the `get_nonce` Core API method.", 403);
    }
    $nonce_id = $json_api->get_nonce_id('posts', 'update_post');
    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.", 403);
    }
    nocache_headers();
    $post = new JSON_API_Post($post);
    $post->update($_REQUEST);
    return array(
      'post' => $post
    );
  }
  
  public function delete_post() {
    global $json_api;
    $post = $json_api->introspector->get_current_post();
    if (empty($post)) {
      $json_api->error("Post not found.");
    }
    if (!current_user_can('edit_post', $post->ID)) {
      $json_api->error("You need to login with a user that has the 'edit_post' capacity for that post.", 403);
    }
    if (!current_user_can('delete_posts')) {
      $json_api->error("You need to login with a user that has the 'delete_posts' capacity.", 403);
    }
    if ($post->post_author != get_current_user_id() && !current_user_can('delete_other_posts')) {
      $json_api->error("You need to login with a user that has the 'delete_other_posts' capacity.", 403);
    }
    if (!$json_api->query->nonce) {
      $json_api->error("You must include a 'nonce' value to update posts. Use the `get_nonce` Core API method.", 403);
    }
    $nonce_id = $json_api->get_nonce_id('posts', 'delete_post');
    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.", 403);
    }
    nocache_headers();
    wp_delete_post($post->ID);
    return array();
  }
  
}

?>
