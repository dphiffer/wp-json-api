<?php
/*
Controller name: Core
Controller description: Basic introspection methods
*/

class JSON_API_Core_Controller {
  
  public function info() {
    global $json_api;
    $php = '';
    if (!empty($json_api->query->controller)) {
      return $json_api->controller_info($json_api->query->controller);
    } else {
      $dir = json_api_dir();
      if (file_exists("$dir/json-api.php")) {
        $php = file_get_contents("$dir/json-api.php");
      } else {
        // Check one directory up, in case json-api.php was moved
        $dir = dirname($dir);
        if (file_exists("$dir/json-api.php")) {
          $php = file_get_contents("$dir/json-api.php");
        }
      }
      if (preg_match('/^\s*Version:\s*(.+)$/m', $php, $matches)) {
        $version = $matches[1];
      } else {
        $version = '(Unknown)';
      }
      $active_controllers = explode(',', get_option('json_api_controllers', 'core'));
      $controllers = array_intersect($json_api->get_controllers(), $active_controllers);
      return array(
        'json_api_version' => $version,
        'controllers' => array_values($controllers)
      );
    }
  }

  public function get_parents() {
    global $json_api;
    extract($json_api->query->get(array('id', 'slug', 'page_id', 'page_slug', 'children')));
    // Parent
    if ($id || $page_id) {
      if (!$id) {
        $id = $page_id;
      }
      $self = get_page($id);
      $parentId = $self->post_parent;
    } else if ($slug || $page_slug) {
      if (!$slug) {
        $slug = $page_slug;
      }
      $self = $json_api->introspector->get_posts(array(
        'pagename' => $slug
      ));
      if(count($self) == 0) {
        $json_api->error("Parent not found");
      }
      $parentId = $self[0]->parent;
      wp_reset_query();
    } else {
      $json_api->error("Include 'id' or 'slug' var in your request.");
    }

    $parent = get_page($parentId);
    $sibs = get_pages(array(
      'sort_column' => 'menu_order',
      'sort_order' => 'ASC',
      'child_of' => $parent->post_parent,
      'parent' => $parent->post_parent,
    ));

    if (count($sibs) >= 1) {
      return $this->posts_result($json_api->introspector->wrap_posts($sibs));
    } else {
      $json_api->error("Children not found.");
    }
  }

  /**
   * Gets sibblings of a post
   */
  public function get_siblings() {
    global $json_api;
    extract($json_api->query->get(array('id', 'slug', 'page_id', 'page_slug', 'children')));
    if ($id || $page_id) {
      if (!$id) {
        $id = $page_id;
      }
      $child = get_page($id);
    } else if ($slug || $page_slug) {
      if (!$slug) {
        $slug = $page_slug;
      }
      $child = $json_api->introspector->get_posts(array(
        'pagename' => $slug
      ));
      wp_reset_query();
    } else {
      $json_api->error("Include 'id' or 'slug' var in your request.");
    }

    if(count($child) >= 1) {
      $id = isset($child->post_parent) ? $child->post_parent : $child[0]->parent;
      $children = get_pages(array(
        'sort_column' => 'menu_order',
        'sort_order' => 'ASC',
        'child_of' => $id,
        'parent' => $id,
      ));
      // Immediate children only
      foreach($children as $key => &$child) {
        if($child->post_parent != $id) {
          unset($children[$key]);
        } else {
          $child->url = get_page_link($child->ID);
        }
      }
      usort($children, function($el1, $el2) {
        return strnatcmp($el1->menu_order, $el2->menu_order);
      });
    } else {
      $json_api->error("Parent not found.");
      return;
    }
    if (count($children) >= 1) {
      return $this->posts_result($json_api->introspector->wrap_posts($children));
    } else {
      $json_api->error("Children not found.");
    }
  }

  /**
   * Gets immediate children of a post
   */
  public function get_children() {
    global $json_api;
    extract($json_api->query->get(array('id', 'slug', 'post_id', 'post_slug')));

    // Find parent based on slug
    if($slug || $post_slug) {
      if (!$slug) {
        $slug = $post_slug; // parent id
      }
      $parent = $json_api->introspector->get_posts(array(
        'pagename' => $slug
      ));
      if(count($parent) == 1) {
        $id = $post_id = $parent[0]->id;
      } else {
        $json_api->error("Slug not found.");
        return;
      }
    }

    // Find child
    if($id || $post_id) {
      if (!$id) {
        $id = $post_id; // parent id
      }
      $children = get_pages(array(
        'sort_column' => 'menu_order',
        'sort_order' => 'ASC',
        'child_of' => $id,
        'parent' => $id,
      ));
      // Immediate children only
      foreach($children as $key => &$child) {
        if($child->post_parent != $id) {
          unset($children[$key]);
        } else {
          $child->url = get_page_link($child->ID);
        }
      }
      usort($children, function($el1, $el2) {
        return strnatcmp($el1->menu_order, $el2->menu_order);
      });
    } else {
      $json_api->error("Include 'id' or 'slug' var in your request.");
      return;
    }
    if (count($children) >= 1) {
      return $this->posts_result($json_api->introspector->wrap_posts($children));
    } else {
      $json_api->error("Not found.");
    }
  }

  /**
   * Published draft
   */
  public function get_published_draft() {
    global $json_api, $wp_the_query;
    extract($json_api->query->get(array('id', 'slug', 'post_id', 'post_slug')));

    // Find parent based on slug
    if($slug || $post_slug) {
      if (!$slug) {
        $slug = $post_slug; // parent id
      }
      $posts = $json_api->introspector->get_posts(array(
        'pagename' => $slug
      ));
      if(count($posts) == 1) {
        $id = $post_id = $posts[0]->id;
      } else {
        $json_api->error("Slug not found.");
        return;
      }
    }

    // Find child
    if($id || $post_id) {
      if (!$id) {
        $id = $post_id; // parent id
      }
      $post = get_posts(array(
        'post_type' => 'revision',
        'post_parent' => $id,
        'post_status' => array('inherit', 'draft', 'auto-draft'),
      ));
    } else {
      $json_api->error("Include 'id' or 'slug' var in your request.");
      return;
    }
    if (count($post) >= 1) {
      $post = array_slice($post, 0, 1);
      return $this->posts_result($json_api->introspector->wrap_posts($post));
    } else {
      $json_api->error("Not found.");
    }
  }

  /**
   * Get a non-published draft
   */
  public function get_draft() {
    global $json_api;
    extract($json_api->query->get(array('id', 'post_id')));
    if($id || $post_id) {
      if (!$id) {
        $id = $post_id; // parent id
      }
      $posts = $json_api->introspector->get_posts(array(
        'post_status' => 'draft',
        'post_type' => array('page', 'post'),
        'p' => $id,
      ));
    } else {
      $json_api->error("Include 'id' var in your request.");
    }
    if (count($posts) == 1) {
      return $this->posts_result($posts);
    } else {
      $json_api->error("Not found.");
    }
  }

  public function get_recent_drafts() {
    global $json_api;
    $posts = $json_api->introspector->get_posts(array(
      'post_status' => 'draft',
      'post_type' => array('page', 'post'),
    ));
    if (count($posts) == 1) {
      return $this->posts_result($posts);
    } else {
      $json_api->error("Not found.");
    }
  }
  
  public function get_recent_posts() {
    global $json_api;
    $posts = $json_api->introspector->get_posts();
    return $this->posts_result($posts);
  }
  
  public function get_post() {
    global $json_api, $post;
    extract($json_api->query->get(array('id', 'slug', 'post_id', 'post_slug')));
    if ($id || $post_id) {
      if (!$id) {
        $id = $post_id;
      }
      $posts = $json_api->introspector->get_posts(array(
        'p' => $id
      ), true);
    } else if ($slug || $post_slug) {
      if (!$slug) {
        $slug = $post_slug;
      }
      $posts = $json_api->introspector->get_posts(array(
        'name' => $slug
      ), true);
    } else {
      $json_api->error("Include 'id' or 'slug' var in your request.");
    }
    if (count($posts) == 1) {
      $post = $posts[0];
      $previous = get_adjacent_post(false, '', true);
      $next = get_adjacent_post(false, '', false);
      $post = new JSON_API_Post($post);
      $response = array(
        'post' => $post
      );
      if ($previous) {
        $response['previous_url'] = get_permalink($previous->ID);
      }
      if ($next) {
        $response['next_url'] = get_permalink($next->ID);
      }
      return $response;
    } else {
      $json_api->error("Not found.");
    }
  }

  public function get_page() {
    global $json_api;
    extract($json_api->query->get(array('id', 'slug', 'page_id', 'page_slug', 'children')));
    if ($id || $page_id) {
      if (!$id) {
        $id = $page_id;
      }
      $posts = $json_api->introspector->get_posts(array(
        'page_id' => $id
      ));
    } else if ($slug || $page_slug) {
      if (!$slug) {
        $slug = $page_slug;
      }
      $posts = $json_api->introspector->get_posts(array(
        'pagename' => $slug
      ));
    } else {
      $json_api->error("Include 'id' or 'slug' var in your request.");
    }
    
    // Workaround for https://core.trac.wordpress.org/ticket/12647
    if (empty($posts)) {
      $url = $_SERVER['REQUEST_URI'];
      $parsed_url = parse_url($url);
      $path = $parsed_url['path'];
      if (preg_match('#^http://[^/]+(/.+)$#', get_bloginfo('url'), $matches)) {
        $blog_root = $matches[1];
        $path = preg_replace("#^$blog_root#", '', $path);
      }
      if (substr($path, 0, 1) == '/') {
        $path = substr($path, 1);
      }
      $posts = $json_api->introspector->get_posts(array('pagename' => $path));
    }
    
    if (count($posts) == 1) {
      if (!empty($children)) {
        $json_api->introspector->attach_child_posts($posts[0]);
      }
      return array(
        'page' => $posts[0]
      );
    } else {
      $json_api->error("Not found.");
    }
  }
  
  public function get_date_posts() {
    global $json_api;
    if ($json_api->query->date) {
      $date = preg_replace('/\D/', '', $json_api->query->date);
      if (!preg_match('/^\d{4}(\d{2})?(\d{2})?$/', $date)) {
        $json_api->error("Specify a date var in one of 'YYYY' or 'YYYY-MM' or 'YYYY-MM-DD' formats.");
      }
      $request = array('year' => substr($date, 0, 4));
      if (strlen($date) > 4) {
        $request['monthnum'] = (int) substr($date, 4, 2);
      }
      if (strlen($date) > 6) {
        $request['day'] = (int) substr($date, 6, 2);
      }
      $posts = $json_api->introspector->get_posts($request);
    } else {
      $json_api->error("Include 'date' var in your request.");
    }
    return $this->posts_result($posts);
  }
  
  public function get_category_posts() {
    global $json_api;
    $category = $json_api->introspector->get_current_category();
    if (!$category) {
      $json_api->error("Not found.");
    }
    $posts = $json_api->introspector->get_posts(array(
      'cat' => $category->id
    ));
    return $this->posts_object_result($posts, $category);
  }
  
  public function get_tag_posts() {
    global $json_api;
    $tag = $json_api->introspector->get_current_tag();
    if (!$tag) {
      $json_api->error("Not found.");
    }
    $posts = $json_api->introspector->get_posts(array(
      'tag' => $tag->slug
    ));
    return $this->posts_object_result($posts, $tag);
  }
  
  public function get_author_posts() {
    global $json_api;
    $author = $json_api->introspector->get_current_author();
    if (!$author) {
      $json_api->error("Not found.");
    }
    $posts = $json_api->introspector->get_posts(array(
      'author' => $author->id
    ));
    return $this->posts_object_result($posts, $author);
  }
  
  public function get_search_results() {
    global $json_api;
    if ($json_api->query->search) {
      $posts = $json_api->introspector->get_posts(array(
        's' => $json_api->query->search
      ));
    } else {
      $json_api->error("Include 'search' var in your request.");
    }
    return $this->posts_result($posts);
  }
  
  public function get_date_index() {
    global $json_api;
    $permalinks = $json_api->introspector->get_date_archive_permalinks();
    $tree = $json_api->introspector->get_date_archive_tree($permalinks);
    return array(
      'permalinks' => $permalinks,
      'tree' => $tree
    );
  }
  
  public function get_category_index() {
    global $json_api;
    $categories = $json_api->introspector->get_categories();
    return array(
      'count' => count($categories),
      'categories' => $categories
    );
  }
  
  public function get_tag_index() {
    global $json_api;
    $tags = $json_api->introspector->get_tags();
    return array(
      'count' => count($tags),
      'tags' => $tags
    );
  }
  
  public function get_author_index() {
    global $json_api;
    $authors = $json_api->introspector->get_authors();
    return array(
      'count' => count($authors),
      'authors' => array_values($authors)
    );
  }
  
  public function get_page_index() {
    global $json_api;
    $pages = array();
    // Thanks to blinder for the fix!
    $numberposts = empty($json_api->query->count) ? -1 : $json_api->query->count;
    $wp_posts = get_posts(array(
      'post_type' => 'page',
      'post_parent' => 0,
      'order' => 'ASC',
      'orderby' => 'menu_order',
      'numberposts' => $numberposts
    ));
    foreach ($wp_posts as $wp_post) {
      $pages[] = new JSON_API_Post($wp_post);
    }
    foreach ($pages as $page) {
      $json_api->introspector->attach_child_posts($page);
    }
    return array(
      'pages' => $pages
    );
  }
  
  public function get_nonce() {
    global $json_api;
    extract($json_api->query->get(array('controller', 'method')));
    if ($controller && $method) {
      $controller = strtolower($controller);
      if (!in_array($controller, $json_api->get_controllers())) {
        $json_api->error("Unknown controller '$controller'.");
      }
      require_once $json_api->controller_path($controller);
      if (!method_exists($json_api->controller_class($controller), $method)) {
        $json_api->error("Unknown method '$method'.");
      }
      $nonce_id = $json_api->get_nonce_id($controller, $method);
      return array(
        'controller' => $controller,
        'method' => $method,
        'nonce' => wp_create_nonce($nonce_id)
      );
    } else {
      $json_api->error("Include 'controller' and 'method' vars in your request.");
    }
  }
  
  protected function get_object_posts($object, $id_var, $slug_var) {
    global $json_api;
    $object_id = "{$type}_id";
    $object_slug = "{$type}_slug";
    extract($json_api->query->get(array('id', 'slug', $object_id, $object_slug)));
    if ($id || $$object_id) {
      if (!$id) {
        $id = $$object_id;
      }
      $posts = $json_api->introspector->get_posts(array(
        $id_var => $id
      ));
    } else if ($slug || $$object_slug) {
      if (!$slug) {
        $slug = $$object_slug;
      }
      $posts = $json_api->introspector->get_posts(array(
        $slug_var => $slug
      ));
    } else {
      $json_api->error("No $type specified. Include 'id' or 'slug' var in your request.");
    }
    return $posts;
  }
  
  protected function posts_result($posts) {
    global $wp_query;
    return array(
      'count' => count($posts),
      'count_total' => (int) $wp_query->found_posts,
      'pages' => $wp_query->max_num_pages,
      'posts' => $posts
    );
  }
  
  protected function posts_object_result($posts, $object) {
    global $wp_query;
    // Convert something like "JSON_API_Category" into "category"
    $object_key = strtolower(substr(get_class($object), 9));
    return array(
      'count' => count($posts),
      'pages' => (int) $wp_query->max_num_pages,
      $object_key => $object,
      'posts' => $posts
    );
  }
  
}

?>
