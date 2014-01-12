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
    $metadata = wp_get_attachment_metadata($this->id);
    $img_base_url = str_replace(wp_basename($this->url), "", $this->url);
    foreach($metadata['sizes'] as $key => $value) {
      $this->images[$key] = (object) array(
        'url' => $img_base_url.$value['file'],
        'width' => $value['width'],
        'height' => $value['height']
      );
    }
  }
  
}

?>
