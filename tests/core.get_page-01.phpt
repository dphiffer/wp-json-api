--TEST--
core.get_page default
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_page&slug=about');
$response = $http->currentResponse();
echo $response['body'];

?>
--EXPECT--
{"status":"ok","page":{"id":686,"type":"page","slug":"about","url":"http:\/\/wordpress.test\/about\/","status":"publish","title":"About The Tests","title_plain":"About The Tests","content":"<p>This site is using the standard WordPress Theme Unit Test Data for content. The Theme Unit Test is a series of posts and pages that match up with a checklist on the WordPress codex. You can use the data and checklist together to test your theme.<\/p>\n<h2>WordPress Theme Development Resources<\/h2>\n<ol>\n<li>See <a href=\"http:\/\/codex.wordpress.org\/Theme_Development\">Theme Development<\/a> for <a href=\"http:\/\/codex.wordpress.org\/Theme_Development#Code_Standards\">code standards<\/a>, examples of best practices, and <a href=\"http:\/\/codex.wordpress.org\/Theme_Development#Resources_and_References\">resources for Theme development<\/a>.<\/li>\n<li>See <a href=\"http:\/\/codex.wordpress.org\/Theme_Unit_Test\">Theme Unit Test<\/a> for a robust test suite for your Theme and get the latest version of the test data you see here.<\/li>\n<li>See <a href=\"http:\/\/codex.wordpress.org\/Theme_Review\">Theme Review<\/a> for a guide to submitting your Theme to the <a href=\"http:\/\/wordpress.org\/extend\/themes\/\">Themes Directory<\/a>.<\/li>\n<\/ol>\n","excerpt":"This site is using the standard WordPress Theme Unit Test Data for content. The Theme Unit Test is a series of posts and pages that match up with a checklist on the WordPress codex. You can use the data and &hellip; <a href=\"http:\/\/wordpress.test\/about\/\">Continue reading <span class=\"meta-nav\">&rarr;<\/span><\/a>","date":"2010-07-25 19:40:01","modified":"2010-07-25 19:40:01","categories":[],"tags":[],"author":{"id":3,"slug":"chip-bennett","name":"Chip Bennett","first_name":"","last_name":"","nickname":"Chip Bennett","url":"","description":""},"comments":[],"attachments":[],"comment_count":0,"comment_status":"closed"}}
