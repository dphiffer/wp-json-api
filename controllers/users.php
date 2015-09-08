<?php
/*
Controller name: Users
Controller description: Data manipulation methods for users
*/

class JSON_API_Users_Controller {

  /** API function to provide a silent login feature for WordPress.
    * Since this method alters the session it won't work unless the
    * user is actually running this script from their browser ... as
    * opposed to a server site GET or POST call.
    *
    * Accepts the following parameters via GET or POST:
    *
    * user_login (required) -- the username of the user logging in
    * user_password (required) -- the password of the user logging in
    *
    */
  public function login() {
    global $json_api;

    if(!isset($_REQUEST['user_login']))
      $json_api->error("You need to pass a value for 'user_login'.");

    if(!isset($_REQUEST['user_password']))
      $json_api->error("You need to pass a value for 'user_password'.");

    if(!function_exists('wp_signon'))
      require_once(ABSPATH . WPINC . '/user.php');

    $user = wp_signon( $_REQUEST, false );
    if ( is_wp_error( $user ) )
      $json_api->error( $user->get_error_message() );

    return array( "message" => "Successfully Logged In" );
  }

  /** API function provide a silent login feature for WordPress.
    * Since this method alters the session it won't work unless the
    * user is actually running this script from their browser ... as
    * opposed to a server site GET or POST call.
    *
    * Doesn't need any parameters via GET or POST to successfully run.
    *
    */
  public function logout() {
    if(!function_exists('wp_logout'))
      require_once(ABSPATH . WPINC . '/pluggable.php');

    wp_logout();

    return array( "message" => "Successfully Logged Out");
  }

  /** API function provide a way to programmatically check to see
    * if a user is currently logged in and returns the user id.
    *
    * Doesn't need any parameters via GET or POST to successfully run.
    *
    */
  public function is_user_logged_in() {
    if(!function_exists('is_user_logged_in'))
      require_once(ABSPATH . WPINC . '/pluggable.php');

    if ( is_user_logged_in() ) {
      global $current_user;
      get_currentuserinfo();

      return array( "user" => $current_user );
    } else {
      global $json_api; 
      $json_api->error(__("No WordPress Users are logged in."));
    }
  }

  /** API function to Create a User for WordPress
   *
   * Accepts the following parameters via GET or POST:
   *
   * nonce (required) -- the security nonce for this API function
   * user_login (required) -- the username of the new user
   * user_password (required) -- the password of the new user
   * user_email (required) -- the email of the new user
   * user_nicename (optional)
   * user_url (optional)
   * display_name (optional)
   * nickname (optional)
   * first_name (optional)
   * last_name (optional)
   * description (optional)
   * rich_editing (optional)
   * user_registered (optional)
   * role (optional)
   * jabber (optional)
   * aim (optional)
   * yim (optional)
   *
   */
  public function create_user() {
    global $json_api;

    $this->_verify_admin();

    $updating = (isset($_REQUEST['id']));

    // Only Require these if we're updating
    if(!$updating) {
      if(!isset($_REQUEST['user_login']))
        $json_api->error("You need to pass a value for 'user_login'.");

      if(!isset($_REQUEST['user_password']))
        $json_api->error("You need to pass a value for 'user_password'.");

      if(!isset($_REQUEST['user_email']))
        $json_api->error("You need to pass a value for 'user_email'.");

      if( email_exists( $_REQUEST[ 'user_email' ] ) )
        $json_api->error(__("This email address already exists"));

      $this->_verify_nonce('create_user');
    }
    else
      $this->_verify_nonce('update_user');

    nocache_headers();

    require_once(ABSPATH . WPINC . '/registration.php');
    
    if(isset($_REQUEST['user_password']))
      $password = $_REQUEST['user_password'];
    else
      $password = wp_generate_password( 12, false );

    $userdata = array( "user_pass" => $password,
                       "user_login" => $_REQUEST['user_login'],
                       "user_email" => $_REQUEST['user_email'] );

    if($updating and isset($_REQUEST['id']))
      $userdata['ID'] = $_REQUEST['id'];

    if(isset($_REQUEST['user_nicename']))
      $userdata['user_nicename'] = $_REQUEST['user_nicename'];

    if(isset($_REQUEST['user_url']))
      $userdata['user_url'] = $_REQUEST['user_url'];

    if(isset($_REQUEST['display_name']))
      $userdata['display_name'] = $_REQUEST['display_name'];

    if(isset($_REQUEST['nickname']))
      $userdata['nickname'] = $_REQUEST['nickname'];

    if(isset($_REQUEST['first_name']))
      $userdata['first_name'] = $_REQUEST['first_name'];

    if(isset($_REQUEST['last_name']))
      $userdata['last_name'] = $_REQUEST['last_name'];

    if(isset($_REQUEST['description']))
      $userdata['description'] = $_REQUEST['description'];

    if(isset($_REQUEST['rich_editing']))
      $userdata['rich_editing'] = $_REQUEST['rich_editing'];

    if(isset($_REQUEST['user_registered']))
      $userdata['user_registered'] = $_REQUEST['user_registered'];

    if(isset($_REQUEST['role']))
      $userdata['role'] = $_REQUEST['role'];

    if(isset($_REQUEST['jabber']))
      $userdata['jabber'] = $_REQUEST['jabber'];

    if(isset($_REQUEST['aim']))
      $userdata['aim'] = $_REQUEST['aim'];

    if(isset($_REQUEST['yim']))
      $userdata['yim'] = $_REQUEST['yim'];

    $user_id = wp_insert_user( $userdata );

    if($updating)
      $user_id = $_REQUEST['id'];

    if (empty($user_id))
      $json_api->error(__("Could not create user."));

    $user = get_userdata($user_id);

    return array( 'user' => $user );
  }

  /** API function to Add User Meta for WordPress
   *
   * Accepts the following parameters via GET or POST:
   *
   * nonce (required) -- the security nonce for this API function
   * id (required) -- id of the user you're adding meta to
   * key (required) -- key of the user meta you're adding
   * value (required) -- value of the user meta
   * unique (optional) -- delete other values for this user meta so this entry is unique
   *
   */
  public function add_user_meta() {
    global $json_api;

    $this->_verify_admin();
    $this->_verify_nonce('add_user_meta');

    nocache_headers();

    if(!isset($_REQUEST['id']))
      $json_api->error(__("The user's 'id' must be set."));

    if(!isset($_REQUEST['key']))
      $json_api->error(__("The 'key' must be set."));

    if(!isset($_REQUEST['value']))
      $json_api->error(__("The 'value' must be set."));

    if(!isset($_REQUEST['unique']))
      $unique = false;
    else
      $unique = ($_REQUEST['unique'] == 'true');

    if( add_user_meta( $_REQUEST['id'], $_REQUEST['key'], $_REQUEST['value'], $unique ) )
      return array( "message" => __("User meta was added successfully.") );
    else
      $json_api->error( __("User meta wasn't able to be added.") );
  }

  /** API function to Update a User for WordPress.
   *
   * Accepts the following parameters via GET or POST:
   *
   * nonce (required) -- the security nonce for this API function
   * id (required) -- id of the user you're updating a user
   * user_login (optional) -- the username of the new user
   * user_password (optional) -- the password of the new user
   * user_email (optional) -- the email of the new user
   * user_nicename (optional)
   * user_url (optional)
   * display_name (optional)
   * nickname (optional)
   * first_name (optional)
   * last_name (optional)
   * description (optional)
   * rich_editing (optional)
   * user_registered (optional)
   * role (optional)
   * jabber (optional)
   * aim (optional)
   * yim (optional)
   *
   */
  public function update_user() {
    global $json_api;

    if(!isset($_REQUEST['id']))
      $json_api->error(__("The user's 'id' must be set."));

    return $this->create_user();
  }

  /** API function to Update User Meta for WordPress.
   *
   * Accepts the following parameters via GET or POST:
   *
   * nonce (required) -- the security nonce for this API function
   * id (required) -- id of the user you're updating meta for
   * key (required) -- key of the user meta you're adding
   * value (required) -- value of the user meta
   * prev_value (optional) -- Previous value to replace
   *
   */
  public function update_user_meta() {
    global $json_api;

    $this->_verify_admin();
    $this->_verify_nonce('update_user_meta');

    nocache_headers();

    if(!isset($_REQUEST['id']))
      $json_api->error(__("The user's 'id' must be set."));

    if(!isset($_REQUEST['key']))
      $json_api->error(__("The 'key' must be set."));

    if(!isset($_REQUEST['value']))
      $json_api->error(__("The 'value' must be set."));

    if(!isset($_REQUEST['prev_value']))
      $prev_value = '';
    else
      $prev_value = $_REQUEST['prev_value'];

    if( update_user_meta( $_REQUEST['id'], $_REQUEST['key'], $_REQUEST['value'], $prev_value ) )
      return array( "message" => __("User meta was updated successfully.") );
    else
      $json_api->error( __("User meta wasn't able to be updated.") );
  }

  /** API function to Delete a User for WordPress.
   *
   * Accepts the following parameters via GET or POST:
   *
   * nonce (required) -- the security nonce for this API function
   * id (required) -- id of the user you're updating a user
   * reassign (optional) -- the id of the user to reassign posts to -- defaults to admin's id
   *
   */
  public function delete_user() {
    global $json_api;

    $this->_verify_admin();
    $this->_verify_nonce('delete_user');

    nocache_headers();

    if(!isset($_REQUEST['id']))
      $json_api->error(__("The user's 'id' must be set."));

    if(!isset($_REQUEST['reassign']))
    {
      $admin_email = get_option('admin_email');

      require_once(ABSPATH . WPINC . '/registration.php');
    
      $reassign = email_exists($admin_email);
    }
    else
      $reassign = $_REQUEST['reassign'];

    if(!function_exists('wp_delete_user'))
      require_once(ABSPATH . 'wp-admin/includes/user.php');

    if( wp_delete_user( (int)$_REQUEST['id'], (int)$reassign ) )
      return array( "message" => __("User deleted successfully.") );
    else
      $json_api->error( __("User wasn't able to be deleted.") );
  }

  /** API function to Delete a User Meta for WordPress
   *
   * Accepts the following parameters via GET or POST:
   *
   * nonce (required) -- the security nonce for this API function
   * id (required) -- id of the user you're deleting meta for
   * key (required) -- key of the user meta you're deleting
   * value (optional) -- meta value to delete
   *
   */
  public function delete_user_meta() {
    global $json_api;

    $this->_verify_admin();
    $this->_verify_nonce('delete_user_meta');

    nocache_headers();

    if(!isset($_REQUEST['id']))
      $json_api->error(__("The user's 'id' must be set."));

    if(!isset($_REQUEST['key']))
      $json_api->error(__("The 'key' must be set."));

    if(!isset($_REQUEST['value']))
      $value = '';
    else
      $value = $_REQUEST['value'];

    if( delete_user_meta( $_REQUEST['id'], $_REQUEST['key'], $value ) )
      return array( "message" => __("User meta was deleted successfully.") );
    else
      $json_api->error( __("User meta wasn't able to be deleted.") );
  }

  /** API function to Get Userdata for WordPress
   *
   * Accepts the following parameters via GET or POST:
   *
   * id (required) -- id of the user you're getting userdata for
   *
   */
  public function get_userdata() {
    global $json_api;

    $this->_verify_admin();

    if(!isset($_REQUEST['id']))
      $json_api->error(__("The user's 'id' must be set."));

    $userdata = get_userdata( $_REQUEST['id'] );

    if(!$userdata)
      $json_api->error(__("User was not found."));
    else
      return array( "user" => $userdata );
  }

  /** API function to Get User Meta for WordPress.
   *
   * Accepts the following parameters via GET or POST:
   *
   * id (required) -- id of the user you're getting user_meta from
   * key (required) -- key of the user_meta your retrieving
   * single (optional) -- return one value -- defaults to false
   *
   */
  public function get_user_meta() {
    global $json_api;

    $this->_verify_admin();

    if(!isset($_REQUEST['id']))
      $json_api->error(__("The user's 'id' must be set."));

    if(!isset($_REQUEST['key']))
      $json_api->error(__("The 'key' must be set."));

    $single = ( $_REQUEST['single'] == 'true' );

    $usermeta = get_user_meta( $_REQUEST['id'], $_REQUEST['key'], $single );

    if(!$usermeta)
      $json_api->error( "'" . $_REQUEST['key'] . "' " . __("was not found."));
    else
      return array( 'usermeta' => $usermeta );
  }

  /** API function to Get Users for WordPress.
    * Doesn't require any GET or POST parameters.
    */
  public function get_users() {
    global $json_api;

    $this->_verify_admin();

    $blogusers = get_users_of_blog();

    return $blogusers;
  }

  private function _verify_nonce($method)
  {
    global $json_api;

    if (!$json_api->query->nonce)
      $json_api->error(__("You must include a 'nonce' value to update users. Use the `get_nonce` Core API method."));

    $nonce_id = $json_api->get_nonce_id('users', $method);

    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id))
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
  }

  private function _verify_admin()
  {
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
