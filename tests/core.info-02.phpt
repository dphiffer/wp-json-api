--TEST--
core.info controller detail
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.info&controller=core');
$response = $http->currentResponse();
echo $response['body'];

?>
--EXPECT--
{"status":"ok","name":"Core","description":"Basic introspection methods","methods":["info","get_recent_posts","get_post","get_page","get_date_posts","get_category_posts","get_tag_posts","get_author_posts","get_search_results","get_date_index","get_category_index","get_tag_index","get_author_index","get_page_index","get_nonce"]}
