<?php
require_once("flickr.php");
require_once("twitter.php");

$tag = $_POST['tag'];
$formattedTag = preg_replace('/\W/', '', $tag);
$flickrObject = new Flickr($formattedTag);
$twitterObject = new Twitter($formattedTag);

$tweets = $twitterObject->getArrayOfTweets($twitterObject->getResponse());
$photoArray = $flickrObject->getPhotoArray($flickrObject->getResponse());
$photoUrls = $flickrObject->buildPhotoUrls($photoArray);

$flickrOutput ="";
foreach($photoUrls as $url){
    $flickrOutput .= createImageTag($url);
}
$twitterOutput = "";
foreach($tweets as $tweet){
    $twitterOutput .= createTweetOutput($tweet);
}
output($flickrOutput, $twitterOutput);

function createTweetOutput($tweet){
    $profilePic = createImageTag($tweet['profileImageUrl']);
    $name = "<h5 class='name'>" . $tweet['name'] . "</h5>";
    $screenName = "<h5>@" . $tweet['screenName'] . "</h5>";
    $text = "<p>" . $tweet['text'] . "</p>";
    return "<div class='tweet'>" . $profilePic . $name . $screenName . $text . "</div>";
}
function createImageTag($photoUrl){
    return "<img src='" . $photoUrl . "' />";
}
function output($flickrOutput, $twitterOutput){
    echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Project</title>
    </head>
    <body>
        <div class='form'>
            <form method='post'>
                    <input type='text' name='tag'>
                    <input type='submit' value='Submit'>
            </form>
        </div>
        <div class='flickr'>" . $flickrOutput . "</div>
        <div class='twitter'>" . $twitterOutput . "</div>
    </body>
</html>";
}