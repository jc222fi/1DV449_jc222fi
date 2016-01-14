<?php

namespace model;

class Flickr{
    private $apiKey = \Settings::FLICKR_KEY;
    private $apiEntryPoint = \Settings::FLICKR_ENTRY_POINT;
    private $apiUrl;

    public function __construct($tag){
        $this->apiUrl = $this->apiEntryPoint . $this->apiKey . "&tags=" . $tag . "&sort=interestingness-desc&format=json&nojsoncallback=1";
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
    public function getPhotoCount(){
        return count($this->getPhotoArray($this->getResponse()));
    }
}