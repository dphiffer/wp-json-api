<?php

class JSON_API_Category {
  
  var $id;          // Integer
  var $slug;        // String
  var $title;       // String
  var $description; // String
  var $parent;      // Integer
  var $post_count;  // Integer
  
  function JSON_API_Category($wp_category = null) {
    if ($wp_category) {
      $this->import_wp_object($wp_category);
    }
  }
  
  function import_wp_object($wp_category) {
    $this->id = (int) $wp_category->term_id;
    $this->slug = $wp_category->slug;
    $this->title = $wp_category->name;
    $this->description = $wp_category->description;
    $this->parent = (int) $wp_category->parent;
    $this->post_count = (int) $wp_category->count;
  }
  
}

?>
