--TEST--
core.get_page default
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_page&slug=about&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);

echo "Response status: $response->status\n";
echo "Page title: {$response->page->title}\n";


?>
--EXPECT--
Response status: ok
Page title: About The Tests
