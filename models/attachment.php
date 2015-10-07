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
    $home = get_bloginfo('url');
    $attachment = wp_get_attachment_metadata($this->id);
    foreach ($attachment['sizes'] as $size => $data) {
      $filename = WP_CONTENT_DIR . '/uploads/' . pathinfo($attachment['file'])['dirname'] . '/' . $data['file'];
      if (file_exists($filename)) {
        list($measured_width, $measured_height) = getimagesize($filename);
        if ($measured_width == $data['width'] &&
            $measured_height == $data['height']) {
          $this->images[$size] = (object) array(
            'url' => wp_get_attachment_image_src($this->id, $size)[0],
            'width' => $data['width'],
            'height' => $data['height']
          );
        }
      }
    }
  }
  
}

?>
