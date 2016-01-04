<?php
require_once("../vendor/autoload.php");
class Twitter{
    private $apiKey = 'tUHuy2zlHcUtmONJ1GQXe7PS3';
    private $apiSecret = '9z9AYlGPiCMScjJxn8UL7FLr6i6C61ZLQ5LKKJNWOJdFEvdwM7';
    private $response;

    public function __construct($tag){
        $client = new Freebird\Services\freebird\Client();
        $client->init_bearer_token($this->apiKey, $this->apiSecret);
        $requestToSend = 'search/tweets.json?q=%23' . $tag . '&src=typd';
        $this->response = json_decode($client->api_request($requestToSend, array('count' => 15)), true);
    }
    public function getResponse(){
        return $this->response;
    }
    public function getArrayOfTweets($response){
        $tweet = Array();
        $statuses = Array();
        for ($i = 0; $i < count($response['statuses']); $i++) {
            //$tweet['id'] = $response['statuses'][$i]['user']['id'];
            $tweet['screenName'] = $response['statuses'][$i]['user']['screen_name'];
            $tweet['name'] = $response['statuses'][$i]['user']['name'];
            $tweet['profileImageUrl'] = $response['statuses'][$i]['user']['profile_image_url_https'];
            //$tweet['createdAt'] = $response['statuses'][$i]['created_at'];
            $tweet['text'] = $response['statuses'][$i]['text'];
            $statuses['tweetNumber' . $i] = $tweet;
        }
        return $statuses;
    }
}