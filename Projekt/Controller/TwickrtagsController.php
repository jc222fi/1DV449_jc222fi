<?php

namespace controller;

//Set dependencies
require_once("view/LayoutView.php");
require_once("view/CombinationView.php");

require_once("model/APIservice.php");

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
            $this->cacheObj->setCache("faces");
            $cachedFaces = $this->cacheObj->retrieve("faces" . $tag); //Is set in the view, so needs a value (cached value or null if not cached)

            //Check for cached data
            $this->cacheObj->setCache("flickr"); //Change to file for cached flickr data
            if($this->cacheObj->isCached("flickr" . $tag)){
                $photoUrls = $this->cacheObj->retrieve("flickr" . $tag);
            }
            else{
                $flickr = $apiService->getFlickrObj();
                $photoUrls = $flickr->buildPhotoUrls($flickr->getPhotoArray($flickr->getResponse()));
                $this->cacheObj->store("flickr" . $tag, $photoUrls, 3600000); //Cache photos for 1 hour
            }
            $this->cacheObj->setCache("tweets"); //Change to file for cached twitter data
            if($this->cacheObj->isCached("tweets" . $tag)){
                $tweets = $this->cacheObj->retrieve("tweets" . $tag);
            }
            else{
                $twitter = $apiService->getTwitterObj();
                $tweets = $twitter->getArrayOfTweets($twitter->getResponse());
                $this->cacheObj->store("tweets" . $tag, $tweets, 1800000); //Cache tweets for 30 min
            }
            //The actual request for keylemon is made in the view, therefore the keylemon object will always be retrieved here
            $keylemon = $apiService->getKeylemonObj();

            //If either of the API objects are not initialized, present error message
            if ($photoUrls == null || $tweets == null || $keylemon == null) {
                $this->layoutView->renderLayout($this->combinationView->errorMessage(), "");
            }
            else {
                $this->cacheObj->setCache("faces"); //Access correct cache file for faces that will be dealt with in the view
                $this->layoutView->renderLayout($this->combinationView->getOutput($tweets, $photoUrls, $cachedFaces, $keylemon), $this->combinationView->getPirateButton());
            }
        }
        else{
            $this->layoutView->renderLayout("", "");

            //This block of code was meant to be the starting page showing results from the cache if there is any data cached, but it only gives me a blank page :-(
            /*$this->cacheObj->setCache("faces");
            $faces = $this->cacheObj->retrieveAll();
            reset($faces);
            $firstFaces = key($faces);
            $this->cacheObj->setCache("flickr");
            $photoUrls = $this->cacheObj->retrieveAll();
            reset($photoUrls);
            $firstPhotos = key($photoUrls);
            $this->cacheObj->setCache("tweets");
            $tweets = $this->cacheObj->retrieveAll();
            reset($tweets);
            $firstTweets = key($tweets);
            if($photoUrls == null || $tweets == null || $faces == null){
                $this->layoutView->renderLayout("", "");
            }
            else{
                $this->layoutView->renderLayout($this->combinationView->getOutput($tweets[$firstFaces], $photoUrls[$firstPhotos], $faces[$firstTweets]), $this->combinationView->getPirateButton());
            }*/
        }
    }
}