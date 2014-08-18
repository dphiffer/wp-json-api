--TEST--
core.get_posts by meta key
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_posts&meta_key=enclosure&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);
$post = $response->posts[0];
$attachment = $post->attachments[0];

echo "Response status: $response->status\n";
echo "Post count: $response->count\n";
echo "Post title: $post->title\n";
echo "Attachment title: $attachment->title\n";

?>
--EXPECT--
Response status: ok
Post count: 1
Post title: Post Format: Audio
Attachment title: St. Louis Blues
