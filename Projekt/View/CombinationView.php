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
            return preg_replace('/\W/', '', strtolower($this->tag));
        } else {
            return null;
        }
    }
    public function errorMessage(){
        return "<p>Unfortunately an error has occured with one of the web services that are used to put together this website. Please try again later.</p>";
    }
    public function getOutput(Array $tweets, Array $photoUrls, $cachedFaces, \model\KLAPI $klApi = null){
        $output = "";
        //Check for content, if there are no tweets och photos to process we return an empty string
        if($tweets != null && $photoUrls != null){
            //Set the condition for the loop to be based on the response with the most results
            $condition = $this->setCondition(count($tweets), count($photoUrls));
            $imagesWithFaces = Array();
            for ($i = 0; $i < $condition; $i++) {
                $currentUrl = $photoUrls[$i];
                //If there is cached info related to the searched tag, use the cached data
                if($cachedFaces != null){
                    $faceDetection = $cachedFaces[$i];
                }
                else{
                    $res = $klApi->detect_faces(array($currentUrl));
                    $faceDet = get_object_vars($res);               //The response is returned as an stdClass and not an
                    if (count($faceDet["faces"]) !== 0) {
                        $object = $faceDet["faces"][0];                 //array, so we need to convert it to an array to be
                        $faceDetection = get_object_vars($object);
                    } else {
                        $faceDetection["face_id"] = null;
                    }      //able to work with it
                }
                $output .= "<div class='tweet'>" . $this->createTweetOutput($tweets[$i]);
                $output .= "<div class='containerphoto'><img id='" . $faceDetection["face_id"] . "' src='" . $currentUrl . "' /></div></div>";

                //Fix for pirate mode since null can't be accessed in the array sent to javascript file
                if($faceDetection["face_id"] == null){
                    $faceDetection["face_id"] = "nothing";
                }
                array_push($imagesWithFaces, $faceDetection);
            }
            //Cache data if it is not already cached
            if ($cachedFaces == null) {
                $this->cacheObj->store("faces" . $this->tag, $imagesWithFaces, 3600000); //Cache face detection with same expiration time as flickr photos
            }
            //Send json to javascript file to handle pirate mode
            $json = json_encode($imagesWithFaces);
            $_SESSION["faceDetection"] = $json;
        }
        return $output;
    }
    public function getPirateButton(){
        return "<input id='pirateButton' type='image' src='Images/Pirate_button.jpg' alt='Pirate mode'>";
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