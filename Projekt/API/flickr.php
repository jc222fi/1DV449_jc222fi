<?php

class Flickr{
    private $apiKey = "63cf15c4b8e5c4aa80fa166305f7eaad";
    private $apiUrl;

    public function __construct($tag){
        $this->apiUrl = "https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=" . $this->apiKey . "&tags=" . $tag . "&sort=interestingness-desc&format=json&nojsoncallback=1";
    }
    public function getResponse(){
        return json_decode(file_get_contents($this->apiUrl), true);
    }
    public function getPhotoArray($response){
        return $response["photos"]["photo"];
    }
    public function buildPhotoUrls($photoArray){
        $photoUrls = Array();
        foreach ($photoArray as $photo) {
            $farm = $photo["farm"];
            $server = $photo["server"];
            $id = $photo["id"];
            $secret = $photo["secret"];
            $photoUrl = 'http://farm' . $farm . '.staticflickr.com/' . $server . '/' . $id . '_' . $secret . '_n.jpg';
            array_push($photoUrls, $photoUrl);
        }
        return $photoUrls;
    }
}