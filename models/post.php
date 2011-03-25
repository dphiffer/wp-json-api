<?php

class JSON_API_Post {
  
  // Note:
  //   JSON_API_Post objects must be instantiated within The Loop.
  
  var $id;              // Integer
  var $type;            // String
  var $slug;            // String
  var $url;             // String
  var $status;          // String ("draft", "published", or "pending")
  var $title;           // String
  var $title_plain;     // String
  var $content;         // String (modified by read_more query var)
  var $excerpt;         // String
  var $date;            // String (modified by date_format query var)
  var $modified;        // String (modified by date_format query var)
  var $categories;      // Array of objects
  var $tags;            // Array of objects
  var $author;          // Object
  var $comments;        // Array of objects
  var $attachments;     // Array of objects
  var $comment_count;   // Integer
  var $comment_status;  // String ("open" or "closed")
  var $thumbnail;       // String
  var $custom_fields;   // Object (included by using custom_fields query var)
  
  function JSON_API_Post($wp_post = null) {
    if (!empty($wp_post)) {
      $this->import_wp_object($wp_post);
    }
  }
  
  function create($values = null) {
    unset($values['id']);
    if (empty($values) || empty($values['title'])) {
      $values = array(
        'title' => 'Untitled',
        'content' => ''
      );
    }
    return $this->save($values);
  }
  
  function update($values) {
    $values['id'] = $this->id;
    return $this->save($values);
  }
  
  function save($values = null) {
    global $json_api, $user_ID;
    
    $wp_values = array();
    
    if (!empty($values['id'])) {
      $wp_values['ID'] = $values['id'];
    }
    
    if (!empty($values['type'])) {
      $wp_values['post_type'] = $values['type'];
    }
    
    if (!empty($values['status'])) {
      $wp_values['post_status'] = $values['status'];
    }
    
    if (!empty($values['title'])) {
      $wp_values['post_title'] = $values['title'];
    }
    
    if (!empty($values['content'])) {
      $wp_values['post_content'] = $values['content'];
    }
    
    if (!empty($values['author'])) {
      $author = $json_api->introspector->get_author_by_login($values['author']);
      $wp_values['post_author'] = $author->id;
    }
    
    if (isset($values['categories'])) {
      $categories = explode(',', $values['categories']);
      foreach ($categories as $category_slug) {
        $category_slug = trim($category_slug);
        $category = $json_api->introspector->get_category_by_slug($category_slug);
        if (empty($wp_values['post_category'])) {
          $wp_values['post_category'] = array($category->id);
        } else {
          array_push($wp_values['post_category'], $category->id);
        }
      }
    }
    
    if (isset($values['tags'])) {
      $tags = explode(',', $values['tags']);
      foreach ($tags as $tag_slug) {
        $tag_slug = trim($tag_slug);
        if (empty($wp_values['tags_input'])) {
          $wp_values['tags_input'] = array($tag_slug);
        } else {
          array_push($wp_values['tags_input'], $tag_slug);
        }
      }
    }
    
    if (isset($wp_values['ID'])) {
      $this->id = wp_update_post($wp_values);
    } else {
      $this->id = wp_insert_post($wp_values);
    }
    
    if (!empty($_FILES['attachment'])) {
      include_once ABSPATH . '/wp-admin/includes/file.php';
      include_once ABSPATH . '/wp-admin/includes/media.php';
      include_once ABSPATH . '/wp-admin/includes/image.php';
      $attachment_id = media_handle_upload('attachment', $this->id);
      $this->attachments[] = new JSON_API_Attachment($attachment_id);
      unset($_FILES['attachment']);
    }
    
    $wp_post = get_post($this->id);
    $this->import_wp_object($wp_post);
    
    return $this->id;
  }
  
  function import_wp_object($wp_post) {
    global $json_api, $post;
    $date_format = $json_api->query->date_format;
    $this->id = (int) $wp_post->ID;
    setup_postdata($wp_post);
    $this->set_value('type', $wp_post->post_type);
    $this->set_value('slug', $wp_post->post_name);
    $this->set_value('url', get_permalink($this->id));
    $this->set_value('status', $wp_post->post_status);
    $this->set_value('title', get_the_title($this->id));
    $this->set_value('title_plain', strip_tags(@$this->title));
    $this->set_content_value();
    $this->set_value('excerpt', get_the_excerpt());
    $this->set_value('date', get_the_time($date_format));
    $this->set_value('modified', date($date_format, strtotime($wp_post->post_modified)));
    $this->set_categories_value();
    $this->set_tags_value();
    $this->set_author_value($wp_post->post_author);
    $this->set_comments_value();
    $this->set_attachments_value();
    $this->set_value('comment_count', (int) $wp_post->comment_count);
    $this->set_value('comment_status', $wp_post->comment_status);
    $this->set_thumbnail_value();
    $this->set_custom_fields_value();
  }
  
  function set_value($key, $value) {
    global $json_api;
    if ($json_api->include_value($key)) {
      $this->$key = $value;
    } else {
      unset($this->$key);
    }
  }
    
  function set_content_value() {
    global $json_api;
    if ($json_api->include_value('content')) {
      $content = get_the_content($json_api->query->read_more);
      $content = apply_filters('the_content', $content);
      $content = str_replace(']]>', ']]&gt;', $content);
      $this->content = $content;
    } else {
      unset($this->content);
    }
  }
  
  function set_categories_value() {
    global $json_api;
    if ($json_api->include_value('categories')) {
      $this->categories = array();
      if ($wp_categories = get_the_category($this->id)) {
        foreach ($wp_categories as $wp_category) {
          $category = new JSON_API_Category($wp_category);
          if ($category->id == 1 && $category->slug == 'uncategorized') {
            // Skip the 'uncategorized' category
            continue;
          }
          $this->categories[] = $category;
        }
      }
    } else {
      unset($this->categories);
    }
  }
  
  function set_tags_value() {
    global $json_api;
    if ($json_api->include_value('tags')) {
      $this->tags = array();
      if ($wp_tags = get_the_tags($this->id)) {
        foreach ($wp_tags as $wp_tag) {
          $this->tags[] = new JSON_API_Tag($wp_tag);
        }
      }
    } else {
      unset($this->tags);
    }
  }
  
  function set_author_value($author_id) {
    global $json_api;
    if ($json_api->include_value('author')) {
      $this->author = new JSON_API_Author($author_id);
    } else {
      unset($this->author);
    }
  }
  
  function set_comments_value() {
    global $json_api;
    if ($json_api->include_value('comments')) {
      $this->comments = $json_api->introspector->get_comments($this->id);
    } else {
      unset($this->comments);
    }
  }
  
  function set_attachments_value() {
    global $json_api;
    if ($json_api->include_value('attachments')) {
      $this->attachments = $json_api->introspector->get_attachments($this->id);
    } else {
      unset($this->attachments);
    }
  }
  
  function set_thumbnail_value() {
    global $json_api;
    if (!$json_api->include_value('thumbnail') ||
        !function_exists('get_post_thumbnail_id')) {
      unset($this->thumbnail);
      return;
    }
    $attachment_id = get_post_thumbnail_id($this->id);
    if (!$attachment_id) {
      unset($this->thumbnail);
      return;
    }
    $thumbnail_size = $this->get_thumbnail_size();
    list($thumbnail) = wp_get_attachment_image_src($attachment_id, $thumbnail_size);
    $this->thumbnail = $thumbnail;
  }
  
  function set_custom_fields_value() {
    global $json_api;
    if ($json_api->include_value('custom_fields') &&
        $json_api->query->custom_fields) {
      $keys = explode(',', $json_api->query->custom_fields);
      $wp_custom_fields = get_post_custom($this->id);
      $this->custom_fields = new stdClass();
      foreach ($keys as $key) {
        if (isset($wp_custom_fields[$key])) {
          $this->custom_fields->$key = $wp_custom_fields[$key];
        }
      }
    } else {
      unset($this->custom_fields);
    }
  }
  
  function get_thumbnail_size() {
    global $json_api;
    if ($json_api->query->thumbnail_size) {
      return $json_api->query->thumbnail_size;
    } else if (function_exists('get_intermediate_image_sizes')) {
      $sizes = get_intermediate_image_sizes();
      if (in_array('post-thumbnail', $sizes)) {
        return 'post-thumbnail';
      }
    }
    return 'thumbnail';
  }
  
}

?>
