--TEST--
core.get_author_posts by slug
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_author_posts&slug=themedemos&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);
$author = $response->author;
$post = $response->posts[0];

echo "Response status: $response->status\n";
echo "Post count: $response->count\n";
echo "First post title: $post->title\n";

?>
--EXPECT--
Response status: ok
Post count: 10
First post title: Markup: HTML Tags and Formatting
