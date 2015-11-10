<?php
//ini_set('display_errors', 'Off');

$url = $_POST['source'];
$data = curl_get_request($url);

$dom = new DOMDocument();

if($dom->loadHTML($data)){
    $newUrls = get_url_list($dom, $url);
    $dataSets = array();
    $availableDayInCommon = '';
    $weekDaysIndex[0] = '01';
    $weekDaysIndex[1] = '02';
    $weekDaysIndex[2] = '03';
    $weekDaysEnglish[0] = '/friday/i';
    $weekDaysEnglish[1] = '/saturday/i';
    $weekDaysEnglish[2] = '/sunday/i';

    foreach($newUrls as $url){
        $getRequest = curl_get_request($url . "/");
        array_push($dataSets, $getRequest);
        echo $url . "<br />";
        if($url == ($newUrls[0])){
            if($dom->loadHTML($dataSets[0])){
                $calendars = get_url_list($dom, $url . "/");
                foreach($calendars as $calendar){
                    if (preg_match('/paul/', $calendar)) {
                        $paul = curl_get_request($calendar);
                        if($dom->loadHTML($paul)){
                            $paulsAvailableDays = find_available_days($dom);
                        }
                        else{
                            die("Something went wrong");
                        }
                    }
                    else if (preg_match('/peter/', $calendar)) {
                        $peter = curl_get_request($calendar);
                        if($dom->loadHTML($peter)){
                            $petersAvailableDays = find_available_days($dom);
                        }
                        else{
                            die("Something went wrong");
                        }
                    }
                    else if (preg_match('/mary/', $calendar)) {
                        $mary = curl_get_request($calendar);
                        if($dom->loadHTML($mary)){
                            $marysAvailableDays = find_available_days($dom);
                        }
                        else{
                            die("Something went wrong");
                        }
                    }
                    else{
                        echo "Couldn't match calendars";
                    }
                }
                foreach($paulsAvailableDays as $day){
                    foreach($petersAvailableDays as $petersDay){
                        if($day == $petersDay){
                            foreach($marysAvailableDays as $marysDay){
                                if($day == $marysDay){
                                    $availableDayInCommon = $day;
                                }
                            }
                        }
                    }
                }
                $availableDayInCommon = preg_replace($weekDaysEnglish, $weekDaysIndex, $availableDayInCommon);
                echo $availableDayInCommon . "<br />";
            }
            else{
                die("Something went wrong");
            }
        }
        else if($url == ($newUrls[1])){
            if($dom->loadHTML($dataSets[1])){
                $xpath = new DOMXPath($dom);
                $dayItems = $xpath->query('//select[@id = "day"]/option');
                foreach($dayItems as $item){
                    $itemValue = $item->getAttribute('value');
                    if($itemValue == $availableDayInCommon){
                        echo $availableMovieDay = $itemValue;
                    }
                }
                $moviesOnDay = array();
                $movieItems = $xpath->query('//select[@id = "movie"]/option');
                foreach($movieItems as $item){
                    $itemValue = $item->getAttribute('value');
                    $checkMovieUrl = $newUrls[1] . "/check?day=" . $availableMovieDay . "&movie=" .$itemValue;
                    $getRequest = curl_get_request($checkMovieUrl);
                    array_push($moviesOnDay, $getRequest);
                    $availableTimeForMovie = array();
                    foreach($moviesOnDay as $movie){
                        if("status" == 1){
                            echo "found";
                            array_push($availableTimeForMovie, $movie);
                        }
                    }
                }
                /*$moviesOnDay = array();
                foreach($checkMovieUrl as $movieUrl){
                    if($dom->loadHTML($getRequest)){
                        $times = $xpath->query('//div[@id = "message"]');
                        foreach ($times as $time) {
                            var_dump($time->nodeValue);
                        }
                    }
                }*/


                //$checkMovieUrl = $newUrls[1] . "check?day=" . $availableMovieDay . "movie=" .$itemValue;
                /*$items = $dom->getElementsByTagName('option');

                $translatedDays = array();
                foreach($items as $item){
                    $itemValue = $item->getAttribute('value');
                    echo $itemValue . "<br />";
                    if (preg_match('/fredag/i', $item->nodeValue) || preg_match('/l�rdag/i', $item->nodeValue) || preg_match('/s�ndag/i', $item->nodeValue)) {
                        //array_push($translatedDays, preg_replace($weekDaysSwedish, $weekDaysEnglish, $day->nodeValue));
                    }
                }
                var_dump($translatedDays);
            }
            else{
                die("Something went wrong");*/
            }
        }
    }
    /*
    $count = 0;
    foreach($dataSets as $dataSet){
        if($dom->loadHTML($dataSet)){
            $moreUrls = get_url_list($dom, $newUrls[$count]);
            $innerCount = 0;
            foreach($moreUrls as $url){
                $dataSets2[$innerCount] = curl_get_request($url);
                echo $url . "<br />";
                $innerCount++;
            }
            foreach($dataSets2 as $dataSet2){
                echo $dataSet2 . "<br />";
            }
        }
        else{
            die("Something went wrong");
        }
        $count++;
    }*/

    /*foreach($dataSets as $dataSet){
        if($dom->loadHTML($dataSet)){
            $links = $dom->getElementsByTagName('a');
            $count = 0;
            foreach($links as $link){
                $result = $link->getAttribute('href');
                $urlExtension = preg_replace('/\//', '', $result);
                $moreUrls[$count] = $newUrls[$count] . $urlExtension . "/";
                echo $moreUrls[$count] . "<br />";
            }
        }
        else{
            die("Something went wrong");
        }
    }
    foreach($newUrls as $newUrl){
        $data = curl_get_request($newUrl);
        if($dom->loadHTML($data)){
            $links = $dom->getElementsByTagName('a');
            $count = 0;
            foreach($links as $link){
                $urlExtension = $link->getAttribute('href');
                $newUrls[$count] = $url . $urlExtension;
                echo $link->getAttribute('href') . "<br />";
                $count++;
            }
        }
        else{
            die("Something went wrong");
        }
        echo $newUrl . "<br />";
    }*/


    /*$xpath = new DOMXPath($dom);
    $items = $xpath->query('//a');

    foreach($items as $item){
        echo $item->nodeVaule . "<br />";
    }*/
}
else {
    die("Something went wrong");
}
function find_available_days(\DOMDocument $dom){
    $days = $dom->getElementsByTagName('th');
    $status = $dom->getElementsByTagName('td');
    $availableDays = array();
    $count = 0;
    foreach($status as $item){
        if(preg_match('/ok/i', $item->nodeValue)){
            array_push($availableDays, $days[$count]->nodeValue);
        }
        $count++;
    }
    return $availableDays;
}
function get_url_list(\DOMDocument $dom, $url){
    $links = $dom->getElementsByTagName('a');
    $count = 0;
    $newUrls = array();
    foreach($links as $link){
        $result = $link->getAttribute('href');
        $urlExtension = preg_replace('/\//', '', $result);
        $newUrls[$count] = $url . $urlExtension;
        $count++;
    }
    return $newUrls;
}

function curl_get_request($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}