<?php

class JSON_API_Author {
  
  var $id;          // Integer
  var $slug;        // String
  var $name;        // String
  var $first_name;  // String
  var $last_name;   // String
  var $nickname;    // String
  var $url;         // String
  var $description; // String
  
  // Note:
  //   JSON_API_Author objects can include additional values by using the
  //   author_meta query var.
  
  function JSON_API_Author($id = null) {
    if ($id) {
      $this->id = (int) $id;
    } else {
      $this->id = (int) get_the_author_meta('ID');
    }
    $this->set_value('slug', 'user_nicename');
    $this->set_value('name', 'display_name');
    $this->set_value('first_name', 'first_name');
    $this->set_value('last_name', 'last_name');
    $this->set_value('nickname', 'nickname');
    $this->set_value('url', 'user_url');
    $this->set_value('description', 'description');
    $this->set_author_meta();
    //$this->raw = get_userdata($this->id);
  }
  
  function set_value($key, $wp_key = false) {
    if (!$wp_key) {
      $wp_key = $key;
    }
    $this->$key = get_the_author_meta($wp_key, $this->id);
  }
  
  function set_author_meta() {
    global $json_api;
    if (!$json_api->query->author_meta) {
      return;
    }
    $protected_vars = array(
      'user_login',
      'user_pass',
      'user_email',
      'user_activation_key'
    );
    $vars = explode(',', $json_api->query->author_meta);
    $vars = array_diff($vars, $protected_vars);
    foreach ($vars as $var) {
      $this->set_value($var);
    }
  }
  
}

?>
