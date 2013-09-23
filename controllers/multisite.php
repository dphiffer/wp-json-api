<?php
/*
Controller name: Multisite
Controller description: Introspection and Data manipulation methods for multisite blogs. (https://github.com/Achillefs/wp-json-api/blob/master/controllers/multisite.php)
*/

class JSON_API_Multisite_Controller {

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * blog_id (required) if not set then current site id is used
    * key (required) key of option to retrieve
    * default (optional) default value to return if option isn't found (defaults to false)
    *
    * Returns option or error
    */
  public function get_blog_option() {
    global $json_api;

    $this->_verify_admin();

    extract($_REQUEST);

    if(!isset($blog_id))
      $json_api->error(__("You must send the 'blog_id' parameter."));

    if(!isset($key))
      $json_api->error(__("You must send the 'key' parameter."));

    if(isset($default))
      $default = (strtolower($default) == 'false');
    else
      $default = false;

    return array( "option" => get_blog_option($blog_id, $key, $default) );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * nonce (required) -- the security nonce for this API function
    * blog_id (required) if not set then current site id is used
    * key (required) key of the blog option to add
    * value (required) value of the blog option
    *
    * Returns Success message or error
    */
  public function add_blog_option() {
    global $json_api;

    $this->_verify_admin();
    $this->_verify_nonce('add_blog_option');

    extract($_REQUEST);

    if(!isset($blog_id))
      $json_api->error(__("You must send the 'blog_id' parameter."));

    if(!isset($key))
      $json_api->error(__("You must send the 'key' parameter."));

    if(!isset($value))
      $json_api->error(__("You must send the 'value' parameter."));

    add_blog_option( $blog_id, $key, $value );

    return array( "message" => __("You successfully added a blog option.") );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * nonce (required) -- the security nonce for this API function
    * blog_id (required) if not set then current site id is used
    * key (required) key of the blog option to update
    * value (required) value of the blog option
    *
    * Returns Success message or error
    */
  public function update_blog_option() {
    global $json_api;

    $this->_verify_admin();
    $this->_verify_nonce('update_blog_option');

    extract($_REQUEST);

    if(!isset($blog_id))
      $json_api->error(__("You must send the 'blog_id' parameter."));

    if(!isset($key))
      $json_api->error(__("You must send the 'key' parameter."));

    if(!isset($value))
      $json_api->error(__("You must send the 'value' parameter."));

    update_blog_option( $blog_id, $key, $value );

    return array( "message" => __("You successfully updated your blog option.") );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * nonce (required) -- the security nonce for this API function
    * blog_id (required) if not set then current site id is used
    * key (required) key of the blog option to delete
    *
    * Returns Success message or error
    */
  public function delete_blog_option() {
    global $json_api;

    $this->_verify_admin();
    $this->_verify_nonce('delete_blog_option');

    extract($_REQUEST);

    if(!isset($blog_id))
      $json_api->error(__("You must send the 'blog_id' parameter."));

    if(!isset($key))
      $json_api->error(__("You must send the 'key' parameter."));

    delete_blog_option( $blog_id, $key );

    return array( "message" => __("You successfully deleted your blog option.") );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * blog_id (required) blog id to retrieve address for
    *
    * Returns blogaddress or error
    */
  public function get_blogaddress_by_id() {
    global $json_api;

    extract( $_REQUEST );

    if(!isset($blog_id))
      $json_api->error(__("You must send the 'blog_id' parameter."));

    $blogaddress = get_blogaddress_by_id( $blog_id );

    return array( "blogaddress" => $blogaddress );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * name (required) blog name retrieve address for
    *
    * Returns blogaddress or error
    */
  public function get_blogaddress_by_name() {
    global $json_api;

    extract( $_REQUEST );

    if(!isset($blogname))
      $json_api->error(__("You must send the 'blogname' parameter."));

    $blogaddress = get_blogaddress_by_name( $blogname );

    return array( "blogaddress" => $blogaddress );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * name (required) blog name retrieve id for
    *
    * Returns blog_id or error
    */
  public function get_id_from_blogname() {
    global $json_api;

    $this->_verify_admin();

    extract( $_REQUEST );

    if(!isset($blogname))
      $json_api->error(__("You must send the 'blogname' parameter."));

    $blog_id = get_id_from_blogname( $blogname );

    return array( "blog_id" => $blog_id );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * blog_id (required) id of the blog to get
    *
    * Returns JSON array of blog details in the "blog" array index
    */
  public function get_blog_details() {
    global $json_api;

    $this->_verify_admin();

    extract( $_REQUEST );

    if(!isset($blog_id))
      $json_api->error(__("You must send the 'blog_id' parameter."));

    return array( "blog" => get_blog_details( $blog_id ) );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * nonce (required) -- the security nonce for this API function
    * domain (required) full domain of the new blog
    * path (optional) path of the new blog - defaults to '/'
    * title (required) title of the new blog
    * user_id (required) id of an existing user to be the admin of the new blog
    * meta (optional) an array of meta data (defaults to '')
    *
    * Returns JSON array of blog details in the "blog" array index
    */
  public function wpmu_create_blog() {
    global $json_api;

    $this->_verify_admin();
    $this->_verify_nonce('wpmu_create_blog');

    extract( $_REQUEST );

    if(!isset($domain))
      $json_api->error(__("You must send the 'domain' parameter."));

    if(!isset($title))
      $json_api->error(__("You must send the 'title' parameter."));

    if(!isset($user_id))
      $json_api->error(__("You must send the 'user_id' parameter."));

    if(!isset($path))
      $path = '/';

    if(!isset($meta))
      $meta = '';

    $blog_id = wpmu_create_blog( $domain, $path, $title, $user_id, $meta );

    if ( is_wp_error( $blog_id ) )
      $json_api->error( $blog_id->get_error_message() );
    else
      return array( "blog" => get_blog_details( $blog_id ) );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * nonce (required) -- the security nonce for this API function
    * blog_id (required) Blog ID of the blog we're going to delete
    * drop (optional) Drop tables for this blog (defaults to false)
    *
    * Returns message of success or error
    */
  public function wpmu_delete_blog() {
    global $json_api;

    $this->_verify_admin();
    $this->_verify_nonce('wpmu_delete_blog');

    extract( $_REQUEST );

    if(!isset($blog_id))
      $json_api->error(__("You must send the 'blog_id' parameter."));

    if(!isset($drop))
      $drop = false;
    else
      $drop = ($drop=='true');

    if( !function_exists('wpmu_delete_blog') )
      require_once( ABSPATH . 'wp-admin/includes/ms.php' );

    wpmu_delete_blog( $blog_id, $drop ); 

    return array( "message" => __( "The Blog was Successfully Deleted." ) );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * nonce (required) -- the security nonce for this API function
    * blog_id (required) blog we'll be adding the user to
    * user_id (required) user we'll be adding to the blog
    * role (optional) role of the user on the new blog (defaults to 'subscriber')
    *
    * Returns Success message or error
    */
  public function add_user_to_blog() {
    global $json_api;

    $this->_verify_admin();
    $this->_verify_nonce('add_user_to_blog');

    extract($_REQUEST);

    if(!isset($blog_id))
      $json_api->error(__("You must send the 'blog_id' parameter."));

    if(!isset($user_id))
      $json_api->error(__("You must send the 'user_id' parameter."));

    if(!isset($role))
      $role = 'subscriber';
      
    if( $returnval = add_user_to_blog( $blog_id, $user_id, $role ) )
      return array( "message" => __("User was successfully added to the blog.") );
    else
    {
      if(is_wp_error($returnval))
        $json_api->error( $returnval->get_error_message() );
      else
        $json_api->error( __("There was an error adding this user to your blog") );
    }
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * nonce (required) -- the security nonce for this API function
    * user_id (required) user we'll be removing from the blog
    * blog_id (required) blog we'll be removing the user from
    * reassign (optional) user we'll be reassigning posts to (defaults to '')
    *
    */
  public function remove_user_from_blog() {
    global $json_api;

    $this->_verify_admin();
    $this->_verify_nonce('remove_user_from_blog');

    extract($_REQUEST);

    if(!isset($blog_id))
      $json_api->error(__("You must send the 'blog_id' parameter."));

    if(!isset($user_id))
      $json_api->error(__("You must send the 'user_id' parameter."));

    if(!isset($reassign))
      $reassign = '';
      
    $returnval = remove_user_from_blog( $user_id, $blog_id, $reassign );

    if( is_wp_error($returnval) )
      $json_api->error( $returnval->get_error_message() );
    else
      return array( "message" => __( "User was successfully removed from the blog." ) );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * domain (required) full domain of site we're checking on
    * path (required) path of the site we're checking on
    * site_id (optional) site id of the blog (defaults to 1)
    *
    */
  public function domain_exists() {
    global $json_api;

    $this->_verify_admin();

    extract($_REQUEST);

    if(!isset($domain))
      $json_api->error(__("You must send the 'domain' parameter."));

    if(!isset($path))
      $json_api->error(__("You must send the 'path' parameter."));

    if(!isset($site_id))
      $site_id = 1;

    if( domain_exists( $domain, $path, $site_id ) )
      return array( "message" => __("The Domain Exists.") );
    else
      return array( "message" => __("The Domain Does Not Exist.") );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * user_id (required) User we want the active blog for
    *
    * Return blog or error
    */
  public function get_active_blog_for_user() {
    global $json_api;

    $this->_verify_admin();

    extract($_REQUEST);

    if(!isset($user_id))
      $json_api->error(__("You must send the 'user_id' parameter."));

    if( $blog = get_active_blog_for_user( $user_id ) )
      return array( "blog" => $blog );
    else
      $json_api->error(__("Active blog was not found."));
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * user_id (required) User we want to list the blogs for
    * all (optional) Get all blogs (defaults to false)
    *
    * Return blogs or error
    */
  public function get_blogs_of_user() {
    global $json_api;

    $this->_verify_admin();

    extract( $_REQUEST );

    if( !isset( $user_id ) )
      $json_api->error(__("You must send the 'user_id' parameter."));

    if(!isset($all))
      $all = false;
    else
      $all = ($all=='true');

    if( $blogs = get_blogs_of_user( $user_id ) )
      return array( "blogs" => $blogs );
    else
      $json_api->error(__("No blogs were found for this user."));
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    * domain (required) full domain we want the blog id for
    * path (optional) path we want the blog id for (defaults to '/')
    *
    */
  public function get_blog_id_from_url() {
    global $json_api;

    $this->_verify_admin();

    extract($_REQUEST);

    if(!isset($domain))
      $json_api->error(__("You must send the 'domain' parameter."));

    if(!isset($path))
      $path = '/';

    if( $blog_id = get_blog_id_from_url( $domain, $path ) )
      return array( "blog_id" => $blog_id );
    else
      $json_api->error(__("No blogs were found for this user."));
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    *
    */
  public function is_subdomain_install() {
    global $json_api;

    $this->_verify_admin();

    extract($_REQUEST);

    if( is_subdomain_install() )
      return array( "message" => __("This is a subdomain install.") );
    else
      return array( "message" => __("This is not a subdomain install.") );
  }

  private function _verify_nonce($method) {
    global $json_api;

    if (!$json_api->query->nonce)
      $json_api->error(__("You must include a 'nonce' value to update users. Use the `get_nonce` Core API method."));

    $nonce_id = $json_api->get_nonce_id('multisite', $method);

    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id))
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
  }

  private function _verify_admin() {
    global $json_api;

    extract($_REQUEST);
    
    if (!current_user_can('administrator'))
    {
      if( isset($u) and isset($p) ) {
        if( !user_pass_ok($u, $p) )
          $json_api->error(__("Your username or password was incorrect."));
      }
      else
        $json_api->error(__("You must either provide the 'u' and 'p' parameters or login as an administrator."));
    }
  }
}
?>
