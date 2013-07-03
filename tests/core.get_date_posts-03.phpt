--TEST--
core.get_date_posts by year
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_date_posts&date=2012&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);
$post = $response->posts[0];

echo "Response status: $response->status\n";
echo "Post count: $response->count\n";
echo "Post title: $post->title\n";

?>
--EXPECT--
Response status: ok
Post count: 10
Post title: Template: Featured Image (Vertical)
