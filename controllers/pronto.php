<?php

/*
Controller name: Pronto
Controller description: Data manipulation methods for Pronto sites
*/

class JSON_API_Pronto_Controller {

  /** RESTful endpoint for this multisite function.
    *
    * Get $_REQUEST options for this endpoint:
    *
    * nonce (required) -- the security nonce for this API function
    * domain (required) to retreive blog_id
    *
    * Returns blog_id or error
    */

  public function get_be_tracking_table() {
    global $json_api;
    global $wpdb;
    global $blog_id;

    extract($_REQUEST);

    $sql = "select * from feed_fetcher_pulled_post where blog_id= '".$blog_id."'";
    $result = $wpdb->get_results($sql);
  
    $be_links = array();
    foreach ($result as $record)
        array_push($be_links, array("post_id"=>$record->post_id, 
		"be_link"=>$record->blog_engine_link));
    return array(
      "message" => "all BE links",
      "be_links" => $be_links
    );
  }

  public function save_be_tracking_table(){
    global $json_api;
    global $wpdb;
    global $blog_id;

    extract($_POST);

    if(!isset($json_data))
      $json_api->error(__("You must send the 'post_id' and 'be_link' parameter."));

    $data = json_decode(json_decode( '"'.$json_data.'"' ));
    foreach ($data->be_links as $record){
        $insert_post = "insert ignore into feed_fetcher_pulled_post(blog_id, blog_engine_link, post_id) values ({$blog_id},'{$record->be_link}',{$record->post_id})";
    	$insert_result = $wpdb->get_var($wpdb->prepare($insert_post));
    }
    return array(
      "message" => "BE links to save",
      "response" => $data
    );
  }

    public function get_be_tracking_count() {
    global $json_api;
    global $wpdb;
    global $blog_id;

    extract($_REQUEST);

    $sql = "select count(*) from feed_fetcher_pulled_post where blog_id={$blog_id}";
    $pulled_post_count = $wpdb->get_var($sql);
    return array(
      "message" => "blog engine count",
      "blog_id" => $blog_id,
      "be_link_count" => $pulled_post_count
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

