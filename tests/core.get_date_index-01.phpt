--TEST--
core.get_search_posts default
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_date_index');
$response = $http->currentResponse();
echo $response['body'];

?>
--EXPECT--
{"status":"ok","permalinks":["http:\/\/wordpress.test\/2008\/09\/","http:\/\/wordpress.test\/2008\/06\/","http:\/\/wordpress.test\/2008\/05\/","http:\/\/wordpress.test\/2008\/04\/","http:\/\/wordpress.test\/2008\/03\/"],"tree":{"2008":{"09":"3","06":"10","05":"5","04":"1","03":"3"}}}
