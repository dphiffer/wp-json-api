<?php
/*
Controller name: Posts
Controller description: Data manipulation methods for posts
*/

class JSON_API_Posts_Controller {

  public function create_post() {
    global $json_api;
    $this->authenticate();
    if (!$json_api->query->nonce) {
      $json_api->error("You must include a 'nonce' value to create posts. Use the `get_nonce` Core API method.");
    }
    $nonce_id = $json_api->get_nonce_id('posts', 'create_post');
    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }
    nocache_headers();
    $post = new JSON_API_Post();
    $id = $post->create($_REQUEST);
    if (empty($id)) {
      $json_api->error("Could not create post.");
    }
    return array(
      'post' => $post
    );
  }
  
  /**
   * Attempts to authenticate user if author and user_password fields exist
   *
   * @return void
   * @author Achillefs Charmpilas
   */
  private function authenticate() {
    global $json_api;
    if ($json_api->query->author && $json_api->query->user_password) {
      $user = wp_signon(array('user_login' => $json_api->query->author, 'user_password' => $json_api->query->user_password));
      if (get_class($user) == 'WP_Error') {
        $json_api->error($user->errors);
      } else {
        if (!user_can($user->ID,'edit_posts')) {
          $json_api->error("You need to login with a user capable of creating posts.");
        }
      }
    } else {
      if (!current_user_can('edit_posts')) {
        $json_api->error("You need to login with a user capable of creating posts.");
      }
    }
  }
}

?>
