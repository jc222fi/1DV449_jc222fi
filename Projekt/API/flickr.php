<?php
$tag = $_POST['tag'];
$apiUrl = "https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=63cf15c4b8e5c4aa80fa166305f7eaad&tags=" . $tag . "&format=json&nojsoncallback=1";
$response = json_decode(file_get_contents($apiUrl),true);
$photoArray = $response["photos"]["photo"];

//var_dump($photoArray);

$photos = "";
foreach ($photoArray as $photo) {
    $farm = $photo["farm"];
    $server = $photo["server"];
    $id = $photo["id"];
    $secret = $photo["secret"];
    $photoUrl = 'http://farm'.$farm.'.staticflickr.com/'.$server.'/'.$id.'_'.$secret.'_n.jpg';
    //var_dump($photoUrl);
    $photos .= "<img src='".$photoUrl."' />";
}
printResponse($photos);


//$dom = new DOMDocument();
//var_dump($jsonObject);
function printResponse($element){
    echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Project</title>
    </head>
    <body>
        $element
    </body>
</html>";
}
function curlGetRequest($url){
    //Retrieve data given the specified url
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}