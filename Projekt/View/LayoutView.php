<?php

namespace view;

class LayoutView {
    private static $search = "tag";

    public function renderLayout($output, $pirateButton){
        echo "<!DOCTYPE html>
            <html>
                <head>
                    <meta charset='utf-8'>
                    <link rel='apple-touch-icon' sizes='57x57' href='Images/apple-icon-57x57.png'>
                    <link rel='apple-touch-icon' sizes='60x60' href='Images/apple-icon-60x60.png'>
                    <link rel='apple-touch-icon' sizes='72x72' href='Images/apple-icon-72x72.png'>
                    <link rel='apple-touch-icon' sizes='76x76' href='Images/apple-icon-76x76.png'>
                    <link rel='apple-touch-icon' sizes='114x114' href='Images/apple-icon-114x114.png'>
                    <link rel='apple-touch-icon' sizes='120x120' href='Images/apple-icon-120x120.png'>
                    <link rel='apple-touch-icon' sizes='144x144' href='Images/apple-icon-144x144.png'>
                    <link rel='apple-touch-icon' sizes='152x152' href='Images/apple-icon-152x152.png'>
                    <link rel='apple-touch-icon' sizes='180x180' href='Images/apple-icon-180x180.png'>
                    <link rel='icon' type='image/png' sizes='192x192'  href='Images/android-icon-192x192.png'>
                    <link rel='icon' type='image/png' sizes='32x32' href='Images/favicon-32x32.png'>
                    <link rel='icon' type='image/png' sizes='96x96' href='Images/favicon-96x96.png'>
                    <link rel='icon' type='image/png' sizes='16x16' href='Images/favicon-16x16.png'>
                    <link rel='manifest' href='Images/manifest.json'>
                    <meta name='msapplication-TileColor' content='#ffffff'>
                    <meta name='msapplication-TileImage' content='Images/ms-icon-144x144.png'>
                    <meta name='theme-color' content='#ffffff'>
                    <link href='//fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
                    <link rel='stylesheet' href='Style/Style.css' />
                    <script src='javascript/Offline.js'></script>
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
                                    <input id='combine' type='submit' value='Combine' >
                            </form>
                        </div>
                        <div class='main'>
                        " . $output . "
                        </div>
                    </div>
                    <script>
                        var run = function(){
                            if(Offline.state === 'up'){
                                Offline.check();
                                $('#combine').removeAttr('disabled');
                                $('#combine').attr('value', 'Combine');
                            }
                            else{
                                $('#combine').attr('disabled', 'disabled');
                                $('#combine').attr('value', 'Lost connection');
                            }
                        };
                        setInterval(run, 5000);
                    </script>
                    <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
                    <script src='javascript/PirateMode.min.js'></script>
                </body>
            </html>";
    }
    public function userWantsToSearch(){
        return isset($_GET[self::$search]);
    }
}