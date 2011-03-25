--TEST--
core.info default
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.info');
$response = $http->currentResponse();
echo $response['body'];

?>
--EXPECT--
{"status":"ok","json_api_version":"1.0.7","controllers":["core","posts","respond"]}
