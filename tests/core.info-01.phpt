--TEST--
core.info default
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.info&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);

echo "Response status: $response->status\n";
echo "Controllers:\n";
var_dump($response->controllers);

?>
--EXPECT--
Response status: ok
Controllers:
array(4) {
  [0]=>
  string(4) "core"
  [1]=>
  string(5) "posts"
  [2]=>
  string(7) "respond"
  [3]=>
  string(7) "widgets"
}
