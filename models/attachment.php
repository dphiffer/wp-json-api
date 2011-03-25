<?php

class JSON_API_Attachment {
  
  var $id;          // Integer
  var $url;         // String
  var $slug;        // String
  var $title;       // String
  var $description; // String
  var $caption;     // String
  var $parent;      // Integer
  var $mime_type;   // String
  
  function JSON_API_Attachment($wp_attachment = null) {
    if ($wp_attachment) {
      $this->import_wp_object($wp_attachment);
      if ($this->is_image()) {
        $this->query_images();
      }
    }
  }
  
  function import_wp_object($wp_attachment) {
    $this->id = (int) $wp_attachment->ID;
    $this->url = $wp_attachment->guid;
    $this->slug = $wp_attachment->post_name;
    $this->title = $wp_attachment->post_title;
    $this->description = $wp_attachment->post_content;
    $this->caption = $wp_attachment->post_excerpt;
    $this->parent = (int) $wp_attachment->post_parent;
    $this->mime_type = $wp_attachment->post_mime_type;
  }
  
  function is_image() {
    return (substr($this->mime_type, 0, 5) == 'image');
  }
  
  function query_images() {
    $sizes = array('thumbnail', 'medium', 'large', 'full');
    if (function_exists('get_intermediate_image_sizes')) {
      $sizes = array_merge(array('full'), get_intermediate_image_sizes());
    }
    $this->images = array();
    foreach ($sizes as $size) {
      list($url, $width, $height) = wp_get_attachment_image_src($this->id, $size);
      $this->images[$size] = (object) array(
        'url' => $url,
        'width' => $width,
        'height' => $height
      );
    }
  }
  
}

?>
