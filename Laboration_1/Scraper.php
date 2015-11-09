<?php
//ini_set('display_errors', 'Off');

$url = $_POST['source'];
$data = curl_get_request($url);

$dom = new DOMDocument();

if($dom->loadHTML($data)){
    $newUrls = get_url_list($dom, $url);
    foreach($newUrls as $url){
        $dataSets = array();
        $url = $url . "/";
        array_push($dataSets, curl_get_request($url));
        echo $url . "<br />";
        if($url == ($newUrls[0] . "/")){
            if($dom->loadHTML($dataSets[0])){
                $calendars = get_url_list($dom, $url);
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
                $availableDaysInCommon = array();
                foreach($paulsAvailableDays as $day){
                    foreach($petersAvailableDays as $petersDay){
                        if($day == $petersDay){
                            foreach($marysAvailableDays as $marysDay){
                                if($day == $marysDay){
                                    array_push($availableDaysInCommon, $day);
                                }
                            }
                        }
                    }
                }
            }
            else{
                die("Something went wrong");
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