<?php

namespace view;

class LayoutView {
    private static $search = "tag";

    public function renderLayout($output, $pirateButton){
        echo "<!DOCTYPE html>
            <html>
                <head>
                    <meta charset='utf-8'>
                    <link href='//fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
                    <link rel='stylesheet' href='/Johanna/webbteknik/twickrtags/Style/Style.css' />
                    <title>Project</title>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'><h1></h1></div>
                        <p class='introduction'>Welcome! If you have found your way here it means you are just as intrigued by #hashtags as we are. This is the site that helps you search for entries with the same #hashtag from Twitter and Flickr simultaneously. The result will be presented to you in the form of new posts, with a little symbol to the far right as to indicate that the search has been made successfully on both services. Since we are rather playful here at twickrtags we also added a little treat for you to play with the results of your search, hit the pirate button to find out!</p>
                        " . $pirateButton . "
                        <div class='form'>
                            <form method='get'>
                                    <input class='search-field' type='text' name='" . self::$search . "'>
                                    <input type='submit' value='Combine' >
                            </form>
                        </div>
                        <div class='main'>
                        " . $output . "
                        </div>
                    </div>
                    <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
                    <script src='/Johanna/webbteknik/twickrtags/jQuery/PirateMode.js'></script>
                </body>
            </html>";
    }
    public function userWantsToSearch(){
        return isset($_GET[self::$search]);
    }
}