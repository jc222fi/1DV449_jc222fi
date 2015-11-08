<?php

$url = $_POST['source'];
$data = curl_get_request($url);

$dom = new DOMDocument();

if($dom->loadHTML($data)){
    $newUrls = get_list($dom, $url);
    var_dump($newUrls);
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
    }*/
    /*foreach($newUrls as $newUrl){
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
function get_list(\DOMDocument $dom, $url){
    $links = $dom->getElementsByTagName('a');
    $count = 0;
    foreach($links as $link){
        $result = $link->getAttribute('href');
        $urlExtension = preg_replace('/\//', '', $result);
        $newUrls[$count] = $url . $urlExtension . "/";
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