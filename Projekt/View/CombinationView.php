<?php

namespace view;

class CombinationView {

    public function getProvidedTag(){
        if (isset($_GET['tag'])) {
            $tag = $_GET['tag'];
            return preg_replace('/\W/', '', $tag);
        } else {
            return null;
        }
    }
    public function errorMessage(){
        return "<p>Unfortunately an error has occured with one of the web services that are used to put together this website. Please try again later.</p>";
    }
    public function getOutput(\model\Twitter $twitter, \model\Flickr $flickr, \model\KLAPI $klApi){
        $output = "";
        if($twitter !== null && $flickr !== null){
            $condition = $this->setCondition($twitter->getTweetCount(), $flickr->getPhotoCount());
            $imagesWithFaces = Array();

            for ($i = 0; $i < $condition; $i++) {
                $currentUrl = $flickr->buildPhotoUrls($flickr->getPhotoArray($flickr->getResponse()))[$i];
                $res = $klApi->detect_faces(array($currentUrl));
                $faceDet = get_object_vars($res);
                $object = $faceDet["faces"][0];
                $faceDetection = get_object_vars($object);
                $output .= "<div class='tweet'>" . $this->createTweetOutput($twitter->getArrayOfTweets($twitter->getResponse())[$i]);
                $output .= "<div class='containerphoto'><img id='" . $faceDetection["face_id"] . "' src='" . $currentUrl . "' /></div></div>";

                //Fix for pirate mode since null can't be accessed in the array sent to javascript file
                if($faceDetection["face_id"] == null){
                    $faceDetection["face_id"] = "nothing";
                }
                array_push($imagesWithFaces, $faceDetection);
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