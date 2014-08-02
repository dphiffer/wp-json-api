--TEST--
core.get_posts by meta value
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_posts&meta_key=_wp_old_slug&meta_value=excerpt&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);
$post = $response->posts[0];

echo "Response status: $response->status\n";
echo "Post count: $response->count\n";
echo "Post title: $post->title\n";
echo "Post slug: $post->slug\n";

?>
--EXPECT--
Response status: ok
Post count: 1
Post title: Template: Excerpt (Defined)
Post slug: template-excerpt-defined
