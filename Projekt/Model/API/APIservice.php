<?php

namespace model;

require_once("flickr.php");
require_once("twitter.php");
require_once("klAPI.php");
class APIService {
    private $flickrObj;
    private $twitterObj;
    private $keylemonObj;

    public function __construct($tag){
        $this->flickrObj = new \model\Flickr($tag);
        $this->twitterObj = new \model\Twitter($tag);
        $this->keylemonObj = new KLAPI(\Settings::KEYLEMON_USERNAME, \Settings::KEYLEMON_KEY, \Settings::KEYLEMON_ENTRY_POINT);
    }
    public function getFlickrObj(){
        return $this->flickrObj;
    }
    public function getTwitterObj(){
        return $this->twitterObj;
    }
    public function getKeylemonObj(){
        return $this->keylemonObj;
    }
}