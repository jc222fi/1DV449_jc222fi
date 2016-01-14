<?php

namespace controller;

//Set dependencies
require_once("/view/LayoutView.php");
require_once("/view/CombinationView.php");

require_once("/model/API/APIservice.php");

class TwickrtagsController {
    private $layoutView;
    private $combinationView;

    public function __construct(){
        $this->layoutView = new \view\LayoutView();
        $this->combinationView = new \view\CombinationView();
    }
    public function doApp(){
        $tag = $this->combinationView->getProvidedTag();
        if ($this->layoutView->userWantsToSearch()) {
            
            //Set up my models if the user clicks "combine"
            $apiService = new \model\APIService($tag);
            $flickr = $apiService->getFlickrObj();
            $twitter = $apiService->getTwitterObj();
            $keylemon = $apiService->getKeylemonObj();

            //If either of the API objects are not initialized, present error message
            if ($flickr == null || $twitter == null || $keylemon == null) {
                $this->layoutView->renderLayout($this->combinationView->errorMessage(), "");
            } else {
                $this->layoutView->renderLayout($this->combinationView->getOutput($twitter, $flickr, $keylemon), $this->combinationView->getPirateButton());
            }
        }
        else{
            $this->layoutView->renderLayout("", "");
        }
    }
}