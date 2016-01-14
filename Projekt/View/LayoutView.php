<?php

namespace view;

class LayoutView {
    private static $search = "tag";

    public function renderLayout($output, $pirateButton){
        echo "<!DOCTYPE html>
            <html>
                <head>
                    <meta charset='utf-8'>
                    <link rel='stylesheet' href='/php_kurs/project/Style/Style.css' />
                    <title>Project</title>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'><h1></h1></div>
                        <div class='form'>
                            <form method='get'>
                                    <input class='search-field' type='text' name='" . self::$search . "'>
                                    <input type='submit' value='Combine' >
                            </form>
                            " . $pirateButton . "
                        </div>
                        <div class='main'>" . $output . "</div>
                    </div>
                    <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
                    <script src='/php_kurs/project/jQuery/PirateMode.js'></script>
                </body>
            </html>";
    }
    public function userWantsToSearch(){
        return isset($_GET[self::$search]);
    }
}