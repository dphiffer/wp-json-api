<?php

/*
Controller name: Pronto Multisite
Controller description: Data manipulation methods for Pronto Multisite
*/

class JSON_API_Pronto_Multisite_Controller {

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * u (optional) -- Username (if not logged in)
    * p (optional) -- Password (if not logged in)
    *
    * Returns all domain mappings or error
    */
  public function get_domain_mappings() {
    global $json_api;
    global $wpdb;

    $this->_verify_admin();

    extract($_REQUEST);

    if(isset($default))
      $default = (strtolower($default) == 'false');
    else
      $default = false;

    $sql = "select domain from wp_domain_mapping";
    $result = $wpdb->get_results($sql);
  
    $domains = array();
    foreach ($result as $domain)
        array_push($domains, $domain->domain);

    return array(
      "message" => "all domain mappings",
      "domains" => $domains
    );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * nonce (required) -- the security nonce for this API function
    * domain (required) to retreive blog_id
    *
    * Returns blog_id or error
    */

  public function get_blog_id_by_domain_mapping() {
    global $json_api;
    global $wpdb;

    extract($_REQUEST);

    if(!isset($domain))
      $json_api->error(__("You must send the 'domain' parameter."));

    $sql = "select blog_id from wp_domain_mapping where domain= '".$domain."'";
    $blog_id = $wpdb->get_var($sql);

    return array(
      "message" => "blog id by domain name",
      "blog_id" => $blog_id
    );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * nonce (required) -- the security nonce for this API function
    * blog_id (required) to retreive archived status
    *
    * Returns archived status  or error
    */
  public function is_site_archived() {
    global $json_api;
    global $wpdb;

    $this->_verify_nonce('is_site_archived');

    extract($_REQUEST);

    if(!isset($blog_id))
      $json_api->error(__("You must send the 'blog_id' parameter."));

    $sql = "select * from wp_blogs where blog_id={$blog_id} and archived='1'";
    $result = $wpdb->get_var($sql);
    $is_archived = ($result !== null ? true : false);

    return array(
      "message" => "check if site is achived by using blog_id",
      "is_archived" => $is_archived
    );
  }

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * nonce (required) -- the security nonce for this API function
    *
    * Returns archived sites (list of blog_id)  or error
    */
  public function get_archived_sites() {
    global $json_api;
    global $wpdb;

    $this->_verify_nonce('get_archived_sites');

    extract($_REQUEST);

    $sql = "select blog_id from wp_blogs where archived='1'";
    $result = $wpdb->get_results($sql);
    $blog_ids = array();
    foreach ($result as $row)
        array_push($blog_ids, $row->blog_id);

    return array(
      "message" => "all archived sites",
      "blog_ids" => $blog_ids
    ); 
 }

  private function _verify_nonce($method) {
    global $json_api;

    if (!$json_api->query->nonce)
      $json_api->error(__("You must include a 'nonce' value to update users. Use the `get_nonce` Core API method."));

    $nonce_id = $json_api->get_nonce_id('pronto_multisite', $method);

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
