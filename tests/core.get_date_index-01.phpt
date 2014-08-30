--TEST--
core.get_search_posts default
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_date_index&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);
$count = count($response->permalinks);

echo "Response status: $response->status\n";
echo "Permalink count: $count\n";
echo "Tree:\n";
var_dump($response->tree);


?>
--EXPECT--
Response status: ok
Permalink count: 20
Tree:
object(stdClass)#5 (5) {
  ["2013"]=>
  object(stdClass)#6 (1) {
    ["01"]=>
    string(1) "5"
  }
  ["2012"]=>
  object(stdClass)#4 (2) {
    ["03"]=>
    string(1) "5"
    ["01"]=>
    string(1) "6"
  }
  ["2011"]=>
  object(stdClass)#7 (1) {
    ["03"]=>
    string(1) "1"
  }
  ["2010"]=>
  object(stdClass)#8 (10) {
    ["10"]=>
    string(1) "1"
    ["09"]=>
    string(1) "2"
    ["08"]=>
    string(1) "3"
    ["07"]=>
    string(1) "1"
    ["06"]=>
    string(1) "3"
    ["05"]=>
    string(1) "1"
    ["04"]=>
    string(1) "1"
    ["03"]=>
    string(1) "1"
    ["02"]=>
    string(1) "1"
    ["01"]=>
    string(1) "1"
  }
  ["2009"]=>
  object(stdClass)#9 (6) {
    ["10"]=>
    string(1) "1"
    ["09"]=>
    string(1) "1"
    ["08"]=>
    string(1) "1"
    ["07"]=>
    string(1) "1"
    ["06"]=>
    string(1) "1"
    ["05"]=>
    string(1) "1"
  }
}
