<?php
require_once("flickr.php");
require_once("twitter.php");
require_once("klAPI.php");
session_start();


$tag = $_POST['tag'];
$formattedTag = preg_replace('/\W/', '', $tag);

$apiKey = "O2BMP4MhIx9mPQ5pif1TIdOQtNnDYm6eY9h553MaGAE8m2yXembaFP";
$klApi = new KLAPI("johnnypesola", "O2BMP4MhIx9mPQ5pif1TIdOQtNnDYm6eY9h553MaGAE8m2yXembaFP", "https://klws.keylemon.com");
$flickrObject = new Flickr($formattedTag);
$twitterObject = new Twitter($formattedTag);

$tweets = $twitterObject->getArrayOfTweets($twitterObject->getResponse());
$photoArray = $flickrObject->getPhotoArray($flickrObject->getResponse());
$photoUrls = $flickrObject->buildPhotoUrls($photoArray);

//mockup data
/*$photoUrls = array();
$photoUrls[0] = "http://farm8.staticflickr.com/7380/16589860812_61df83462d_n.jpg";
$photoUrls[1] = "http://farm3.staticflickr.com/2039/2592862315_9462f4a15f_n.jpg";
$photoUrls[2] =  "http://farm4.staticflickr.com/3149/2300277983_8f0685e398_n.jpg";
$photoUrls[3] =  "http://farm1.staticflickr.com/71/162370088_32123d11fe_n.jpg";
$photoUrls[4] =  "http://farm4.staticflickr.com/3308/4564994263_9ec5809cc7_n.jpg";
$photoUrls[5] =  "http://farm8.staticflickr.com/7200/14037132633_3e9ec74d79_n.jpg";
$photoUrls[6] =  "http://farm5.staticflickr.com/4016/4707678594_eaf58e61db_n.jpg";
$photoUrls[7] =  "http://farm7.staticflickr.com/6108/7004179599_5b9a891c32_n.jpg";
$photoUrls[8] =  "http://farm3.staticflickr.com/2733/4462437980_244349b252_n.jpg";
$photoUrls[9] =  "http://farm6.staticflickr.com/5045/5357850375_02455ca749_n.jpg";
$photoUrls[10] =  "http://farm4.staticflickr.com/3044/2897517971_6850c73598_n.jpg";
$photoUrls[11] =  "http://farm7.staticflickr.com/6138/5941221443_12da578da1_n.jpg";
$photoUrls[12] =  "http://farm8.staticflickr.com/7421/10773763535_018d0afe30_n.jpg";
$photoUrls[13] =  "http://farm3.staticflickr.com/2492/4345038042_9c04b974b7_n.jpg";
$photoUrls[14] =  "http://farm8.staticflickr.com/7002/6731496975_bd44e6a321_n.jpg";

$mockupFace["face_id"] = "33406d82-6954-4afb-af58-191e3fe40145";
$mockupFace["x"] = 77;
$mockupFace["y"] = 120;
$mockupFace["w"] = 91;
$mockupFace["h"] = 91;
$mockupFace["image_url"] = "/api/image/217c6872-3530-46b2-8b5b-29cb27c64ae2";
$mockupFace["checksum"] = "4136ea92bc23ccf9070460852198ed38";

$mockupFaces = array();
array_push($mockupFaces, $mockupFace);

$error = array();
$error["error_id"] = 13;
$error["message"] = "Image without face.";
$error["checksum"] = "5c396aecd3bc103f43be9e2d1ef45d66";
array_push($mockupFaces, $error);

$error["error_id"] = 13;
$error["message"] = "Image without face.";
$error["checksum"] = "ec15e25f109489ed26f485f8491f9e05";
array_push($mockupFaces, $error);

$mockupFace["face_id"] = "9ec67081-d930-4e13-b1d8-f656df6b8c16";
$mockupFace["x"] = 83;
$mockupFace["y"] = 52;
$mockupFace["w"] = 110;
$mockupFace["h"] = 110;
$mockupFace["image_url"] = "/api/image/a54e0af9-e8bd-45ea-a8d4-ecf56f7277de";
$mockupFace["checksum"] = "6b1cfebd6affcbfb3d2fac50d1bba869";
array_push($mockupFaces, $mockupFace);

$error["error_id"] = 13;
$error["message"] = "Image without face.";
$error["checksum"] = "4f84c981f97bc326544e20f789968cad";
array_push($mockupFaces, $error);

$error["error_id"] = 13;
$error["message"] = "Image without face.";
$error["checksum"] = "1d6758b1802de723934429815c5c346f";
array_push($mockupFaces, $error);

$error["error_id"] = 13;
$error["message"] = "Image without face.";
$error["checksum"] = "629385158f5b3d50318b10cec54bc4b8";
array_push($mockupFaces, $error);

$error["error_id"] = 13;
$error["message"] = "Image without face.";
$error["checksum"] = "c5ee211b9e469ee44d7e21fd1ad7a700";
array_push($mockupFaces, $error);

$mockupFace["face_id"] = "0dc1ec2b-42c7-4c14-89e2-ea58b1cbbe28";
$mockupFace["x"] = 128;
$mockupFace["y"] = 68;
$mockupFace["w"] = 95;
$mockupFace["h"] = 95;
$mockupFace["image_url"] = "/api/image/6e62df80-ed5b-4770-8a9e-ea237e7b730d";
$mockupFace["checksum"] = "504548d3118d30471f9745350534c228";
array_push($mockupFaces, $mockupFace);

$error["error_id"] = 13;
$error["message"] = "Image without face.";
$error["checksum"] = "e7fb555769c080e4d66c94b74a88e936";
array_push($mockupFaces, $error);

$mockupFace["face_id"] = "26c6ce1a-3aff-411a-a0cd-0b7b6face465";
$mockupFace["x"] = 128;
$mockupFace["y"] = 70;
$mockupFace["w"] = 63;
$mockupFace["h"] = 63;
$mockupFace["image_url"] = "/api/image/c79da000-b2db-4460-93b7-f02c340467a4";
$mockupFace["checksum"] = "6dce5c09208c8cc391644610909c2a7b";
array_push($mockupFaces, $mockupFace);

$error["error_id"] = 13;
$error["message"] = "Image without face.";
$error["checksum"] = "eccdd3e8c0abb8bbd46ddc201331286e";
array_push($mockupFaces, $error);

$error["error_id"] = 13;
$error["message"] = "Image without face.";
$error["checksum"] = "eccdd3e8c0abb8bbd46ddc201331286e";
array_push($mockupFaces, $error);

$error["error_id"] = 13;
$error["message"] = "Image without face.";
$error["checksum"] = "eccdd3e8c0abb8bbd46ddc201331286e";
array_push($mockupFaces, $error);

$error["error_id"] = 13;
$error["message"] = "Image without face.";
$error["checksum"] = "eccdd3e8c0abb8bbd46ddc201331286e";
array_push($mockupFaces, $error);*/
//end mockup data


$tweetCount = count($tweets);
$photoCount = count($photoUrls);
if($photoCount > $tweetCount){
    $condition = $tweetCount;
}
else{
    $condition = $photoCount;
}

$output = "";
$imagesWithFaces = Array();

for ($i = 0; $i < $condition; $i++) {
    $currentUrl = $photoUrls[$i];
    $res = $klApi->detect_faces(array($currentUrl));
    $faceDet = get_object_vars($res);
    $object = $faceDet["faces"][0];
    $faceDetection = get_object_vars($object);
    $output .= "<div class='tweet'>" . createTweetOutput($tweets[$i]);
    $output .= "<div class='containerphoto'><img id='" . ($faceDetection["face_id"] != null ? $faceDetection["face_id"] : "" ) . "' src='" . $photoUrls[$i] . "' /></div></div>";
    if($faceDetection["face_id"] == null){
        $faceDetection["face_id"] = "nothing";
    }
    array_push($imagesWithFaces, $faceDetection);
}
$json = json_encode($imagesWithFaces);
output($output);
$_SESSION["faceDetection"] = $json;
var_dump($_SESSION);

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
                <input id='pirateButton' type='image' src='../Images/Pirate_button.jpg'>
            </div>
            <div class='main'>" . $output . "</div>
        </div>
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
        <script src='../jQuery/PirateMode.js'></script>
    </body>
</html>";
}