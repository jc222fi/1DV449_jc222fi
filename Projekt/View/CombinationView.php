<?php

namespace view;

class CombinationView {
    private $cacheObj;
    private $tag;

    public function __construct(\Cache $cacheObj){
        $this->cacheObj = $cacheObj;
    }
    public function getProvidedTag(){
        if (isset($_GET['tag'])) {
            $this->tag = $_GET['tag'];
            return preg_replace('/\W/', '', $this->tag);
        } else {
            return null;
        }
    }
    public function errorMessage(){
        return "<p>Unfortunately an error has occured with one of the web services that are used to put together this website. Please try again later.</p>";
    }
    public function getOutput($tweets, $photoUrls, $cachedFaces, \model\KLAPI $klApi){
        $output = "";
        if($tweets != null && $photoUrls != null){
            $condition = $this->setCondition(count($tweets), count($photoUrls));
            $imagesWithFaces = Array();
            //var_dump($cachedFaces);
            for ($i = 0; $i < $condition; $i++) {
                $currentUrl = $photoUrls[$i];
                if($cachedFaces != null){
                    $faceDetection = $cachedFaces[$i];
                }
                else{
                    $res = $klApi->detect_faces(array($currentUrl));
                    $faceDet = get_object_vars($res);
                    $object = $faceDet["faces"][0];
                    $faceDetection = get_object_vars($object);
                }
                $output .= "<div class='tweet'>" . $this->createTweetOutput($tweets[$i]);
                $output .= "<div class='containerphoto'><img id='" . $faceDetection["face_id"] . "' src='" . $currentUrl . "' /></div></div>";

                //Fix for pirate mode since null can't be accessed in the array sent to javascript file
                if($faceDetection["face_id"] == null){
                    $faceDetection["face_id"] = "nothing";
                }
                array_push($imagesWithFaces, $faceDetection);
            }
            if ($cachedFaces == null) {
                //$this->cacheObj->setCache("faces" . $this->tag);
                $this->cacheObj->store("faces" . $this->tag, $imagesWithFaces, 3600000); //Cache face detection with same expiration time as flickr photos
            }
            $json = json_encode($imagesWithFaces);
            $_SESSION["faceDetection"] = $json;
        }
        return $output;
    }
    public function getPirateButton(){
        return "<input id='pirateButton' type='image' src='/php_kurs/project/Images/Pirate_button.jpg'>";
    }
    private function setCondition($tweetCount, $flickrCount){
        if($flickrCount > $tweetCount){
            $condition = $tweetCount;
        }
        else{
            $condition = $flickrCount;
        }
        return $condition;
    }
    private function createTweetOutput($tweet){
        $profilePic = "<img class='profilePhoto' src='" . $tweet['profileImageUrl'] . "' />";
        $name = "<h4 class='name'>" . $tweet['name'] . "</h4>";
        $screenName = "<h4> @" . $tweet['screenName'] . "</h4>";
        $text = "<p>" . $tweet['text'] . "</p>";
        return $profilePic . $name . $screenName . $text;
    }
}