--TEST--
core.info controller detail
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.info&controller=core&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);

echo "Response status: $response->status\n";
echo "Name: $response->name\n";
echo "Description: $response->description\n";
echo "Methods:\n";
var_dump($response->methods);

?>
--EXPECT--
Response status: ok
Name: Core
Description: Basic introspection methods
Methods:
array(16) {
  [0]=>
  string(4) "info"
  [1]=>
  string(16) "get_recent_posts"
  [2]=>
  string(9) "get_posts"
  [3]=>
  string(8) "get_post"
  [4]=>
  string(8) "get_page"
  [5]=>
  string(14) "get_date_posts"
  [6]=>
  string(18) "get_category_posts"
  [7]=>
  string(13) "get_tag_posts"
  [8]=>
  string(16) "get_author_posts"
  [9]=>
  string(18) "get_search_results"
  [10]=>
  string(14) "get_date_index"
  [11]=>
  string(18) "get_category_index"
  [12]=>
  string(13) "get_tag_index"
  [13]=>
  string(16) "get_author_index"
  [14]=>
  string(14) "get_page_index"
  [15]=>
  string(9) "get_nonce"
}
