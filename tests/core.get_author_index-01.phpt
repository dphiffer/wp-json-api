--TEST--
core.get_author_index
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_author_index');
$response = $http->currentResponse();
echo $response['body'];

?>
--EXPECT--
{"status":"ok","count":2,"authors":[{"id":3,"slug":"chip-bennett","name":"Chip Bennett","first_name":"","last_name":"","nickname":"Chip Bennett","url":"","description":""},{"id":4,"slug":"ian-stewart","name":"Ian Stewart","first_name":"","last_name":"","nickname":"Ian Stewart","url":"","description":""}]}
