<?php

namespace model;

require_once("vendor/autoload.php");
class Twitter{
    private $apiKey = \Settings::TWITTER_KEY;
    private $apiSecret = \Settings::TWITTER_SECRET;
    private $response;

    public function __construct($tag){
        $client = new \Freebird\Services\freebird\Client();
        $client->init_bearer_token($this->apiKey, $this->apiSecret);
        $requestToSend = 'search/tweets.json?q=%23' . $tag . '&lang=en&result_type=recent&src=typd';
        $this->response = json_decode($client->api_request($requestToSend, array('count' => 15)), true);
    }
    public function getResponse(){
        return $this->response;
    }
    public function getArrayOfTweets($response){
        $tweet = Array();
        $statuses = Array();
        for ($i = 0; $i < count($response['statuses']); $i++) {
            $tweet['screenName'] = $response['statuses'][$i]['user']['screen_name'];
            $tweet['name'] = $response['statuses'][$i]['user']['name'];
            $tweet['profileImageUrl'] = $response['statuses'][$i]['user']['profile_image_url_https'];
            $tweet['text'] = $response['statuses'][$i]['text'];
            $statuses[$i] = $tweet;
        }
        return $statuses;
    }
}