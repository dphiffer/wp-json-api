--TEST--
core.get_date_posts by day
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_date_posts&date=2008-09-05');
$response = $http->currentResponse();
echo $response['body'];

?>
--EXPECT--
{"status":"ok","count":1,"count_total":1,"pages":1,"posts":[{"id":358,"type":"post","slug":"readability-test","url":"http:\/\/wordpress.test\/2008\/09\/05\/readability-test\/","status":"publish","title":"Readability Test","title_plain":"Readability Test","content":"<p>All children, except one, grow up. They soon know that they will grow up, and the way Wendy knew was this. One day when she was two years old she was playing in a garden, and she plucked another flower and ran with it to her mother. I suppose she must have looked rather delightful, for Mrs. Darling put her hand to her heart and cried, &#8220;Oh, why can&#8217;t you remain like this for ever!&#8221; This was all that passed between them on the subject, but henceforth Wendy knew that she must grow up. You always know after you are two. Two is the beginning of the end.<\/p>\n<p> <a href=\"http:\/\/wordpress.test\/2008\/09\/05\/readability-test\/#more-358\" class=\"more-link\">Read more<\/a><\/p>\n","excerpt":"All children, except one, grow up. They soon know that they will grow up, and the way Wendy knew was this. One day when she was two years old she was playing in a garden, and she plucked another flower &hellip; <a href=\"http:\/\/wordpress.test\/2008\/09\/05\/readability-test\/\">Continue reading <span class=\"meta-nav\">&rarr;<\/span><\/a>","date":"2008-09-05 00:27:25","modified":"2008-09-05 00:27:25","categories":[{"id":9,"slug":"cat-a","title":"Cat A","description":"","parent":0,"post_count":2}],"tags":[{"id":53,"slug":"chattels","title":"chattels","description":"","post_count":2},{"id":82,"slug":"privation","title":"privation","description":"","post_count":2}],"author":{"id":3,"slug":"chip-bennett","name":"Chip Bennett","first_name":"","last_name":"","nickname":"Chip Bennett","url":"","description":""},"comments":[],"attachments":[],"comment_count":0,"comment_status":"closed"}]}
