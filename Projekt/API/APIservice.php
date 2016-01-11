<?php
require_once("flickr.php");
require_once("twitter.php");
require_once("klAPI.php");

$tag = $_POST['tag'];
$formattedTag = preg_replace('/\W/', '', $tag);

$apiKey = "O2BMP4MhIx9mPQ5pif1TIdOQtNnDYm6eY9h553MaGAE8m2yXembaFP";
$klApi = new KLAPI("johnnypesola", "O2BMP4MhIx9mPQ5pif1TIdOQtNnDYm6eY9h553MaGAE8m2yXembaFP", "https://klws.keylemon.com");
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
$imagesUrlFaceDetection = Array();

for ($i = 0; $i < $condition; $i++) {
    $output .= "<div class='tweet'>" . createTweetOutput($tweets[$i]);
    $output .= "<div class='containerphoto'><img src='" . $photoUrls[$i] . "' /><img class='pirate hidden' src='../Images/sprite_sheet.png' ></div>";
    array_push($imagesUrlFaceDetection, $photoUrls[$i]);
}
output($output);
$faceDetection = $klApi->detect_faces($imagesUrlFaceDetection);
var_dump($faceDetection);

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
            <div class='header'><h1></h1></div>
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