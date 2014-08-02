--TEST--
core.get_recent_posts count argument
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_recent_posts&count=3&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);
$post = $response->posts[3];

echo "Response status: $response->status\n";
echo "Post count: $response->count\n";
echo "Post title: $post->title\n";

?>
--EXPECT--
Response status: ok
Post count: 4
Post title: Markup: Text Alignment
