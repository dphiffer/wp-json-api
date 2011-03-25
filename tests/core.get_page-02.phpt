--TEST--
core.get_page children argument
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_page&slug=level-1&children=1');
$response = $http->currentResponse();
echo $response['body'];

?>
--EXPECT--
{"status":"ok","page":{"id":174,"type":"page","slug":"level-1","url":"http:\/\/wordpress.test\/level-1\/","status":"publish","title":"Level 1","title_plain":"Level 1","content":"<p>Level 1 of the reverse hierarchy test.  This is to make sure the importer correctly assigns parents and children even when the children come first in the export file.<\/p>\n","excerpt":"Level 1 of the reverse hierarchy test. This is to make sure the importer correctly assigns parents and children even when the children come first in the export file.","date":"2007-12-11 16:25:40","modified":"2007-12-11 16:25:40","categories":[],"tags":[],"author":{"id":3,"slug":"chip-bennett","name":"Chip Bennett","first_name":"","last_name":"","nickname":"Chip Bennett","url":"","description":""},"comments":[],"attachments":[],"comment_count":0,"comment_status":"closed","children":[{"id":173,"type":"page","slug":"level-2","url":"http:\/\/wordpress.test\/level-1\/level-2\/","status":"publish","title":"Level 2","title_plain":"Level 2","content":"<p>Level 2 of the reverse hierarchy test.<\/p>\n","excerpt":"Level 2 of the reverse hierarchy test.","date":"2007-12-11 16:25:40","modified":"2007-12-11 16:23:33","categories":[],"tags":[],"author":{"id":3,"slug":"chip-bennett","name":"Chip Bennett","first_name":"","last_name":"","nickname":"Chip Bennett","url":"","description":""},"comments":[],"attachments":[],"comment_count":0,"comment_status":"closed","children":[{"id":172,"type":"page","slug":"level-3","url":"http:\/\/wordpress.test\/level-1\/level-2\/level-3\/","status":"publish","title":"Level 3","title_plain":"Level 3","content":"<p>Level 3 of the reverse hierarchy test.<\/p>\n","excerpt":"Level 3 of the reverse hierarchy test.","date":"2007-12-11 16:25:40","modified":"2007-12-11 16:23:16","categories":[],"tags":[],"author":{"id":3,"slug":"chip-bennett","name":"Chip Bennett","first_name":"","last_name":"","nickname":"Chip Bennett","url":"","description":""},"comments":[],"attachments":[],"comment_count":0,"comment_status":"closed","children":[]}]}]}}
