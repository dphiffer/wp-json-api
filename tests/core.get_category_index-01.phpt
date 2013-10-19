--TEST--
core.get_category_index 
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_category_index&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);
$category = $response->categories[0];

echo "Response status: $response->status\n";
echo "Category count: $response->count\n";
echo "Category name: $category->title\n";

?>
--EXPECT--
Response status: ok
Category count: 63
Category name: aciform
