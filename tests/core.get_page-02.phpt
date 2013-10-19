--TEST--
core.get_page children argument
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_page&slug=level-1&children=1&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);
$page = $response->page;
$child = $page->children[0];
$grandchild = $child->children[0];

echo "Response status: $response->status\n";
echo "Page title: $page->title\n";
echo "Child title: $child->title\n";
echo "Grandchild title: $grandchild->title\n";

?>
--EXPECT--
Response status: ok
Page title: Level 1
Child title: Level 2
Grandchild title: Level 3
