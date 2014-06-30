<?php

class JSON_API_Comment {
  
  var $id;      // Integer
  var $name;    // String
  var $url;     // String
  var $date;    // String
  var $content; // String
  var $parent;  // Integer
  var $author;  // Object (only if the user was registered & logged in)
  var $avatar;	//String;
  
  function JSON_API_Comment($wp_comment = null) {
    if ($wp_comment) {
      $this->import_wp_object($wp_comment);
    }
  }
  
  function import_wp_object($wp_comment) {
    global $json_api;
    
    $date_format = $json_api->query->date_format;
    $content = apply_filters('comment_text', $wp_comment->comment_content);
    
    $this->id = (int) $wp_comment->comment_ID;
    $this->name = $wp_comment->comment_author;
    $this->url = $wp_comment->comment_author_url;
    $this->date = date($date_format, strtotime($wp_comment->comment_date));
    $this->content = trim($content);
    $this->parent = (int) $wp_comment->comment_parent;
    //$this->raw = $wp_comment;
    $this->avatar = $this->get_avatar_url( $wp_comment->comment_author_email, 80);
    
    if (!empty($wp_comment->user_id)) {
      $this->author = new JSON_API_Author($wp_comment->user_id);
    } else {
      unset($this->author);
    }
  }
  
  function handle_submission() {
    global $comment, $wpdb;
    add_action('comment_id_not_found', array(&$this, 'comment_id_not_found'));
    add_action('comment_closed', array(&$this, 'comment_closed'));
    add_action('comment_on_draft', array(&$this, 'comment_on_draft'));
    add_filter('comment_post_redirect', array(&$this, 'comment_post_redirect'));
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST['comment_post_ID'] = $_REQUEST['post_id'];
    $_POST['author'] = $_REQUEST['name'];
    $_POST['email'] = $_REQUEST['email'];
    $_POST['url'] = empty($_REQUEST['url']) ? '' : $_REQUEST['url'];
    $_POST['comment'] = $_REQUEST['content'];
    $_POST['parent'] = $_REQUEST['parent'];
    include ABSPATH . 'wp-comments-post.php';
  }
  
  function comment_id_not_found() {
    global $json_api;
    $json_api->error("Post ID '{$_REQUEST['post_id']}' not found.");
  }
  
  function comment_closed() {
    global $json_api;
    $json_api->error("Post is closed for comments.");
  }
  
  function comment_on_draft() {
    global $json_api;
    $json_api->error("You cannot comment on unpublished posts.");
  }
  
  function comment_post_redirect() {
    global $comment, $json_api;
    $status = ($comment->comment_approved) ? 'ok' : 'pending';
    $new_comment = new JSON_API_Comment($comment);
    $json_api->response->respond($new_comment, $status);
  }
  
  function get_avatar_url($id_or_email, $size = 96, $default = 'mystery')
  {
  	if ( !is_numeric($size) )
  		$size = '96';
  	
  	$email = '';
  	if ( is_numeric($id_or_email) ) {
  		$id = (int) $id_or_email;
  		$user = get_userdata($id);
  		if ( $user )
  			$email = $user->user_email;
  	} elseif ( is_object($id_or_email) ) {
  		// No avatar for pingbacks or trackbacks
  		$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
  		if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
  			return false;
  	
  		if ( !empty($id_or_email->user_id) ) {
  			$id = (int) $id_or_email->user_id;
  			$user = get_userdata($id);
  			if ( $user)
  				$email = $user->user_email;
  		} elseif ( !empty($id_or_email->comment_author_email) ) {
  			$email = $id_or_email->comment_author_email;
  		}
  	} else {
  		$email = $id_or_email;
  	}
  	
  	if ( empty($default) ) {
  		$avatar_default = get_option('avatar_default');
  		if ( empty($avatar_default) )
  			$default = 'mystery';
  		else
  			$default = $avatar_default;
  	}
  	
  	if ( !empty($email) )
  		$email_hash = md5( strtolower( $email ) );
  	
  	if ( is_ssl() ) {
  		$host = 'https://secure.gravatar.com';
  	} else {
  		if ( !empty($email) )
  			$host = sprintf( "http://%d.gravatar.com", ( hexdec( $email_hash[0] ) % 2 ) );
  		else
  			$host = 'http://0.gravatar.com';
  	}
  	  	
  	if ( !empty($email) ) {
  		$out = "$host/avatar/";
  		$out .= $email_hash;
  		$out .= '?s='.$size;
  		$out .= '&d=mm';
  	
  		$rating = get_option('avatar_rating');
  		if ( !empty( $rating ) )
  			$out .= "&amp;r={$rating}";
  		
  	}
  	
  	return $out;
  }
}

?>
