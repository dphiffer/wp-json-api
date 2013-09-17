--TEST--
core.get_tag_index
--FILE--
<?php

require_once 'HTTP/Client.php';
$http = new HTTP_Client();
$http->get('http://wordpress.test/?json=core.get_tag_index&dev=1');
$response = $http->currentResponse();
$response = json_decode($response['body']);

echo "Response status: $response->status\n";
echo "Tag count: $response->count\n";
echo "Tags:\n";
var_dump($response->tags);

?>
--EXPECT--
Response status: ok
Tag count: 60
Tags:
array(60) {
  [0]=>
  object(stdClass)#5 (5) {
    ["id"]=>
    int(66)
    ["slug"]=>
    string(4) "8bit"
    ["title"]=>
    string(4) "8BIT"
    ["description"]=>
    string(22) "Tags posts about 8BIT."
    ["post_count"]=>
    int(1)
  }
  [1]=>
  object(stdClass)#6 (5) {
    ["id"]=>
    int(67)
    ["slug"]=>
    string(11) "alignment-2"
    ["title"]=>
    string(9) "alignment"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(3)
  }
  [2]=>
  object(stdClass)#4 (5) {
    ["id"]=>
    int(68)
    ["slug"]=>
    string(8) "articles"
    ["title"]=>
    string(8) "Articles"
    ["description"]=>
    string(26) "Tags posts about Articles."
    ["post_count"]=>
    int(1)
  }
  [3]=>
  object(stdClass)#7 (5) {
    ["id"]=>
    int(69)
    ["slug"]=>
    string(5) "aside"
    ["title"]=>
    string(5) "aside"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [4]=>
  object(stdClass)#8 (5) {
    ["id"]=>
    int(70)
    ["slug"]=>
    string(5) "audio"
    ["title"]=>
    string(5) "audio"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [5]=>
  object(stdClass)#9 (5) {
    ["id"]=>
    int(71)
    ["slug"]=>
    string(10) "captions-2"
    ["title"]=>
    string(8) "captions"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(2)
  }
  [6]=>
  object(stdClass)#10 (5) {
    ["id"]=>
    int(72)
    ["slug"]=>
    string(10) "categories"
    ["title"]=>
    string(10) "categories"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(2)
  }
  [7]=>
  object(stdClass)#11 (5) {
    ["id"]=>
    int(73)
    ["slug"]=>
    string(4) "chat"
    ["title"]=>
    string(4) "chat"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(2)
  }
  [8]=>
  object(stdClass)#12 (5) {
    ["id"]=>
    int(77)
    ["slug"]=>
    string(5) "codex"
    ["title"]=>
    string(5) "Codex"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(3)
  }
  [9]=>
  object(stdClass)#13 (5) {
    ["id"]=>
    int(78)
    ["slug"]=>
    string(10) "comments-2"
    ["title"]=>
    string(8) "comments"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(4)
  }
  [10]=>
  object(stdClass)#14 (5) {
    ["id"]=>
    int(79)
    ["slug"]=>
    string(9) "content-2"
    ["title"]=>
    string(7) "content"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(12)
  }
  [11]=>
  object(stdClass)#15 (5) {
    ["id"]=>
    int(81)
    ["slug"]=>
    string(3) "css"
    ["title"]=>
    string(3) "css"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(7)
  }
  [12]=>
  object(stdClass)#16 (5) {
    ["id"]=>
    int(85)
    ["slug"]=>
    string(6) "dowork"
    ["title"]=>
    string(6) "dowork"
    ["description"]=>
    string(25) "Tags posts about #dowork."
    ["post_count"]=>
    int(1)
  }
  [13]=>
  object(stdClass)#17 (5) {
    ["id"]=>
    int(86)
    ["slug"]=>
    string(9) "edge-case"
    ["title"]=>
    string(9) "edge case"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(8)
  }
  [14]=>
  object(stdClass)#18 (5) {
    ["id"]=>
    int(87)
    ["slug"]=>
    string(8) "embeds-2"
    ["title"]=>
    string(6) "embeds"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(4)
  }
  [15]=>
  object(stdClass)#19 (5) {
    ["id"]=>
    int(91)
    ["slug"]=>
    string(9) "excerpt-2"
    ["title"]=>
    string(7) "excerpt"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(3)
  }
  [16]=>
  object(stdClass)#20 (5) {
    ["id"]=>
    int(92)
    ["slug"]=>
    string(4) "fail"
    ["title"]=>
    string(4) "Fail"
    ["description"]=>
    string(22) "Tags posts about fail."
    ["post_count"]=>
    int(1)
  }
  [17]=>
  object(stdClass)#21 (5) {
    ["id"]=>
    int(93)
    ["slug"]=>
    string(14) "featured-image"
    ["title"]=>
    string(14) "featured image"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(3)
  }
  [18]=>
  object(stdClass)#22 (5) {
    ["id"]=>
    int(96)
    ["slug"]=>
    string(12) "formatting-2"
    ["title"]=>
    string(10) "formatting"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [19]=>
  object(stdClass)#23 (5) {
    ["id"]=>
    int(97)
    ["slug"]=>
    string(3) "ftw"
    ["title"]=>
    string(3) "FTW"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [20]=>
  object(stdClass)#24 (5) {
    ["id"]=>
    int(98)
    ["slug"]=>
    string(3) "fun"
    ["title"]=>
    string(3) "Fun"
    ["description"]=>
    string(21) "Tags posts about fun."
    ["post_count"]=>
    int(1)
  }
  [21]=>
  object(stdClass)#25 (5) {
    ["id"]=>
    int(99)
    ["slug"]=>
    string(7) "gallery"
    ["title"]=>
    string(7) "gallery"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(3)
  }
  [22]=>
  object(stdClass)#26 (5) {
    ["id"]=>
    int(105)
    ["slug"]=>
    string(4) "html"
    ["title"]=>
    string(4) "html"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(5)
  }
  [23]=>
  object(stdClass)#27 (5) {
    ["id"]=>
    int(106)
    ["slug"]=>
    string(5) "image"
    ["title"]=>
    string(5) "image"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(7)
  }
  [24]=>
  object(stdClass)#28 (5) {
    ["id"]=>
    int(109)
    ["slug"]=>
    string(9) "jetpack-2"
    ["title"]=>
    string(7) "jetpack"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(3)
  }
  [25]=>
  object(stdClass)#29 (5) {
    ["id"]=>
    int(111)
    ["slug"]=>
    string(6) "layout"
    ["title"]=>
    string(6) "layout"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(4)
  }
  [26]=>
  object(stdClass)#30 (5) {
    ["id"]=>
    int(112)
    ["slug"]=>
    string(4) "link"
    ["title"]=>
    string(4) "link"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(2)
  }
  [27]=>
  object(stdClass)#31 (5) {
    ["id"]=>
    int(113)
    ["slug"]=>
    string(7) "lists-2"
    ["title"]=>
    string(5) "lists"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [28]=>
  object(stdClass)#32 (5) {
    ["id"]=>
    int(115)
    ["slug"]=>
    string(4) "love"
    ["title"]=>
    string(4) "Love"
    ["description"]=>
    string(22) "Tags posts about love."
    ["post_count"]=>
    int(1)
  }
  [29]=>
  object(stdClass)#33 (5) {
    ["id"]=>
    int(116)
    ["slug"]=>
    string(8) "markup-2"
    ["title"]=>
    string(6) "markup"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(6)
  }
  [30]=>
  object(stdClass)#34 (5) {
    ["id"]=>
    int(117)
    ["slug"]=>
    string(5) "media"
    ["title"]=>
    string(5) "media"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [31]=>
  object(stdClass)#35 (5) {
    ["id"]=>
    int(122)
    ["slug"]=>
    string(10) "mothership"
    ["title"]=>
    string(10) "Mothership"
    ["description"]=>
    string(29) "Tags posts about motherships."
    ["post_count"]=>
    int(1)
  }
  [32]=>
  object(stdClass)#36 (5) {
    ["id"]=>
    int(123)
    ["slug"]=>
    string(8) "mustread"
    ["title"]=>
    string(9) "Must Read"
    ["description"]=>
    string(40) "Tags posts about articles you must read."
    ["post_count"]=>
    int(1)
  }
  [33]=>
  object(stdClass)#37 (5) {
    ["id"]=>
    int(124)
    ["slug"]=>
    string(8) "nailedit"
    ["title"]=>
    string(9) "Nailed It"
    ["description"]=>
    string(32) "Tags posts about that nailed it."
    ["post_count"]=>
    int(1)
  }
  [34]=>
  object(stdClass)#38 (5) {
    ["id"]=>
    int(126)
    ["slug"]=>
    string(10) "pagination"
    ["title"]=>
    string(10) "pagination"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [35]=>
  object(stdClass)#39 (5) {
    ["id"]=>
    int(128)
    ["slug"]=>
    string(10) "password-2"
    ["title"]=>
    string(8) "password"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [36]=>
  object(stdClass)#40 (5) {
    ["id"]=>
    int(129)
    ["slug"]=>
    string(8) "pictures"
    ["title"]=>
    string(8) "Pictures"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [37]=>
  object(stdClass)#41 (5) {
    ["id"]=>
    int(130)
    ["slug"]=>
    string(11) "pingbacks-2"
    ["title"]=>
    string(9) "pingbacks"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [38]=>
  object(stdClass)#42 (5) {
    ["id"]=>
    int(133)
    ["slug"]=>
    string(4) "post"
    ["title"]=>
    string(4) "post"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [39]=>
  object(stdClass)#43 (5) {
    ["id"]=>
    int(38)
    ["slug"]=>
    string(12) "post-formats"
    ["title"]=>
    string(12) "Post Formats"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(16)
  }
  [40]=>
  object(stdClass)#44 (5) {
    ["id"]=>
    int(139)
    ["slug"]=>
    string(5) "quote"
    ["title"]=>
    string(5) "quote"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(2)
  }
  [41]=>
  object(stdClass)#45 (5) {
    ["id"]=>
    int(141)
    ["slug"]=>
    string(9) "read-more"
    ["title"]=>
    string(9) "read more"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [42]=>
  object(stdClass)#46 (5) {
    ["id"]=>
    int(142)
    ["slug"]=>
    string(11) "readability"
    ["title"]=>
    string(11) "readability"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [43]=>
  object(stdClass)#47 (5) {
    ["id"]=>
    int(145)
    ["slug"]=>
    string(9) "shortcode"
    ["title"]=>
    string(9) "shortcode"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(6)
  }
  [44]=>
  object(stdClass)#48 (5) {
    ["id"]=>
    int(147)
    ["slug"]=>
    string(10) "standard-2"
    ["title"]=>
    string(8) "standard"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(2)
  }
  [45]=>
  object(stdClass)#49 (5) {
    ["id"]=>
    int(148)
    ["slug"]=>
    string(6) "status"
    ["title"]=>
    string(6) "status"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [46]=>
  object(stdClass)#50 (5) {
    ["id"]=>
    int(149)
    ["slug"]=>
    string(8) "sticky-2"
    ["title"]=>
    string(6) "sticky"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [47]=>
  object(stdClass)#51 (5) {
    ["id"]=>
    int(150)
    ["slug"]=>
    string(7) "success"
    ["title"]=>
    string(7) "Success"
    ["description"]=>
    string(25) "Tags posts about success."
    ["post_count"]=>
    int(1)
  }
  [48]=>
  object(stdClass)#52 (5) {
    ["id"]=>
    int(151)
    ["slug"]=>
    string(7) "swagger"
    ["title"]=>
    string(7) "Swagger"
    ["description"]=>
    string(25) "Tags posts about swagger."
    ["post_count"]=>
    int(1)
  }
  [49]=>
  object(stdClass)#53 (5) {
    ["id"]=>
    int(158)
    ["slug"]=>
    string(4) "tags"
    ["title"]=>
    string(4) "Tags"
    ["description"]=>
    string(33) "Tags posts about tags. #inception"
    ["post_count"]=>
    int(1)
  }
  [50]=>
  object(stdClass)#54 (5) {
    ["id"]=>
    int(159)
    ["slug"]=>
    string(8) "template"
    ["title"]=>
    string(8) "template"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(12)
  }
  [51]=>
  object(stdClass)#55 (5) {
    ["id"]=>
    int(163)
    ["slug"]=>
    string(5) "tiled"
    ["title"]=>
    string(5) "tiled"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [52]=>
  object(stdClass)#56 (5) {
    ["id"]=>
    int(164)
    ["slug"]=>
    string(5) "title"
    ["title"]=>
    string(5) "title"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(5)
  }
  [53]=>
  object(stdClass)#57 (5) {
    ["id"]=>
    int(165)
    ["slug"]=>
    string(12) "trackbacks-2"
    ["title"]=>
    string(10) "trackbacks"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(1)
  }
  [54]=>
  object(stdClass)#58 (5) {
    ["id"]=>
    int(166)
    ["slug"]=>
    string(9) "twitter-2"
    ["title"]=>
    string(7) "twitter"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(2)
  }
  [55]=>
  object(stdClass)#59 (5) {
    ["id"]=>
    int(168)
    ["slug"]=>
    string(6) "unseen"
    ["title"]=>
    string(6) "Unseen"
    ["description"]=>
    string(46) "Tags posts about things that cannot be unseen."
    ["post_count"]=>
    int(1)
  }
  [56]=>
  object(stdClass)#60 (5) {
    ["id"]=>
    int(169)
    ["slug"]=>
    string(5) "video"
    ["title"]=>
    string(5) "video"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(3)
  }
  [57]=>
  object(stdClass)#61 (5) {
    ["id"]=>
    int(170)
    ["slug"]=>
    string(10) "videopress"
    ["title"]=>
    string(10) "videopress"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(2)
  }
  [58]=>
  object(stdClass)#62 (5) {
    ["id"]=>
    int(172)
    ["slug"]=>
    string(9) "wordpress"
    ["title"]=>
    string(9) "WordPress"
    ["description"]=>
    string(27) "Tags posts about WordPress."
    ["post_count"]=>
    int(1)
  }
  [59]=>
  object(stdClass)#63 (5) {
    ["id"]=>
    int(173)
    ["slug"]=>
    string(12) "wordpress-tv"
    ["title"]=>
    string(12) "wordpress.tv"
    ["description"]=>
    string(0) ""
    ["post_count"]=>
    int(2)
  }
}
