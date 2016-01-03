<?php
require_once("../vendor/autoload.php");
$tag = $_POST['tag'];
$formattedTag = preg_replace('/\W/', '', $tag);

$requestToSend = 'search/tweets.json?q=%23' . $formattedTag . '&src=typd';

$client = new Freebird\Services\freebird\Client();

$client->init_bearer_token('tUHuy2zlHcUtmONJ1GQXe7PS3', '9z9AYlGPiCMScjJxn8UL7FLr6i6C61ZLQ5LKKJNWOJdFEvdwM7');

$response = $client->api_request($requestToSend, array('count' => 5));

$jsonArray = json_decode($response, true);
//var_dump($jsonArray['statuses']);
$tweet = Array();
$statuses = Array();
for($i = 0; $i < count($jsonArray['statuses']); $i++){
    $tweet['id'] = $jsonArray['statuses'][$i]['user']['id'];
    $tweet['screenName'] = $jsonArray['statuses'][$i]['user']['screen_name'];
    $tweet['name'] = $jsonArray['statuses'][$i]['user']['name'];
    $tweet['profileImageUrl'] = $jsonArray['statuses'][$i]['user']['profile_image_url_https'];
    $tweet['createdAt'] = $jsonArray['statuses'][$i]['created_at'];
    $tweet['text'] = $jsonArray['statuses'][$i]['text'];
    $statuses['tweetNumber' . $i] = $tweet;
}


var_dump($statuses);