--TEST--
core.get_post default
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_post&slug=markup-html-tags-and-formatting&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);

echo "Response status: $response->status\n";
echo "post title: {$response->post->title}\n";

?>
--EXPECT--
Response status: ok
post title: Markup: HTML Tags and Formatting
