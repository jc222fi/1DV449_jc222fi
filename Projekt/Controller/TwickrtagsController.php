<?php

namespace controller;

//Set dependencies
require_once("/view/LayoutView.php");
require_once("/view/CombinationView.php");

require_once("/model/API/APIservice.php");

class TwickrtagsController {
    private $layoutView;
    private $combinationView;
    private $cacheObj;

    public function __construct(){
        $this->cacheObj = new \Cache();
        $this->layoutView = new \view\LayoutView();
        $this->combinationView = new \view\CombinationView($this->cacheObj);
    }
    public function doApp(){
        $tag = $this->combinationView->getProvidedTag();
        if ($this->layoutView->userWantsToSearch()) {
            //Set up my models if the user clicks "combine"
            $apiService = new \model\APIService($tag);
            //Check for cached data
            $cachedPhotos = $this->cacheObj->retrieve("flickr" . $tag);
            $cachedTweets = $this->cacheObj->retrieve("tweets" . $tag);
            $cachedFaces = null; //$this->cacheObj->retrieve("faces" . $tag);
            var_dump($cachedPhotos);
            var_dump($cachedTweets);
            var_dump($cachedFaces);

            if($cachedPhotos != null){
                $photoUrls = $cachedPhotos;
            }
            else{
                $flickr = $apiService->getFlickrObj();
                $photoUrls = $flickr->buildPhotoUrls($flickr->getPhotoArray($flickr->getResponse()));
            }
            if($cachedTweets != null){
                $tweets = $cachedTweets;
            }
            else{
                $twitter = $apiService->getTwitterObj();
                $tweets = $twitter->getArrayOfTweets($twitter->getResponse());
            }

            //The actual request for keylemon is made in the view, therefore this check will be made there
            $keylemon = $apiService->getKeylemonObj();

            //If either of the API objects are not initialized, present error message
            if ($photoUrls == null || $tweets == null || $keylemon == null) {
                $this->layoutView->renderLayout($this->combinationView->errorMessage(), "");
            }
            else {
                if ($cachedPhotos == null) {
                    //$this->cacheObj->setCache("flickr" . $tag);
                    $this->cacheObj->store("flickr" . $tag, $photoUrls, 3600000); //Cache photos for 1 hour
                }
                if ($cachedTweets == null) {
                    //$this->cacheObj->setCache("tweets" . $tag);
                    $this->cacheObj->store("tweets" . $tag, $tweets, 1800000); //Cache tweets for 30 min
                }
                $this->layoutView->renderLayout($this->combinationView->getOutput($tweets, $photoUrls, $cachedFaces, $keylemon), $this->combinationView->getPirateButton());
            }
        }
        else{
            $this->layoutView->renderLayout("", "");
        }
    }
}