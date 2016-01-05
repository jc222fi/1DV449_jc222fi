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

$tweetCount = count($tweets);
$photoCount = count($photoUrls);
if($photoCount > $tweetCount){
    $condition = $tweetCount;
}
else{
    $condition = $photoCount;
}

$output = "";

for ($i = 0; $i < $condition; $i++) {
    $output .= "<div class='tweet'>" . createTweetOutput($tweets[$i]);
    $output .= "<img src='" . $photoUrls[$i] . "' /></div>";
}
output($output);

function createTweetOutput($tweet){
    $profilePic = "<img class='profilePhoto' src='" . $tweet['profileImageUrl'] . "' />";
    $name = "<h4 class='name'>" . $tweet['name'] . "</h4>";
    $screenName = "<h4> @" . $tweet['screenName'] . "</h4>";
    $text = "<p>" . $tweet['text'] . "</p>";
    return $profilePic . $name . $screenName . $text;
}
function output($output){
    echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <link rel='stylesheet' href='../Style/Style.css' />
        <title>Project</title>
    </head>
        <div class='container'>
            <div class='header'><h1>twickrtags</h1></div>
            <div class='form'>
                <form method='post'>
                        <input type='text' name='tag'>
                        <input type='submit' value='Combine'>
                </form>
            </div>
            <div class='main'>" . $output . "</div>
        </div>
    </body>
</html>";
}