<?php
//ini_set('display_errors', 'Off');

//Get provided url from teh form at index.php and get the data from that url
$originalUrl = $_POST['source'];
$data = curl_get_request($originalUrl);

$dom = new DOMDocument();

//If HTML is succesfully loaded, proceed. If not, kill app
if($dom->loadHTML($data)){
    //Search for links and get a list of the urls
    $newUrls = get_url_list($dom, $originalUrl);
    $dataSets = array();

    //Set up some patterns and replacements to be used later on in the process
    $availableDayInCommon = '';
    $weekDaysIndex[0] = '01';
    $weekDaysIndex[1] = '02';
    $weekDaysIndex[2] = '03';
    $weekDaysEnglish[0] = '/friday/i';
    $weekDaysEnglish[1] = '/saturday/i';
    $weekDaysEnglish[2] = '/sunday/i';

    //Loop through the list of urls
    foreach($newUrls as $url) {

        //Make a new GET request for each url in the list plus "/" to get to the next level and add to array with data
        $getRequest = curl_get_request($url . "/");
        array_push($dataSets, $getRequest);

        //If the current url is the first, url == calendar
        if ($url == ($newUrls[0])) {
            if ($dom->loadHTML($dataSets[0])) {

                //Get list of links at calendar page (each person's calendar)
                $calendars = get_url_list($dom, $url . "/");
                foreach ($calendars as $calendar) {

                    //Match every calendar with the person and search for the available day
                    if (preg_match('/paul/', $calendar)) {
                        $paul = curl_get_request($calendar);
                        if ($dom->loadHTML($paul)) {
                            $paulsAvailableDays = find_available_days($dom);
                        } else {
                            die("Something went wrong");
                        }
                    } else if (preg_match('/peter/', $calendar)) {
                        $peter = curl_get_request($calendar);
                        if ($dom->loadHTML($peter)) {
                            $petersAvailableDays = find_available_days($dom);
                        } else {
                            die("Something went wrong");
                        }
                    } else if (preg_match('/mary/', $calendar)) {
                        $mary = curl_get_request($calendar);
                        if ($dom->loadHTML($mary)) {
                            $marysAvailableDays = find_available_days($dom);
                        } else {
                            die("Something went wrong");
                        }
                    } else {
                        echo "Couldn't match calendars";
                    }
                }

                //Finally, check which day they have in common as available
                foreach ($paulsAvailableDays as $day) {
                    foreach ($petersAvailableDays as $petersDay) {
                        if ($day == $petersDay) {
                            foreach ($marysAvailableDays as $marysDay) {
                                if ($day == $marysDay) {
                                    $availableDayInCommon = $day;
                                }
                            }
                        }
                    }
                }

                //Convert format to be able to compare the day from calendar with the day from cinema (english vs swedish)
                $availableDayInCommon = preg_replace($weekDaysEnglish, $weekDaysIndex, $availableDayInCommon);
            } else {
                die("Something went wrong");
            }

            //If current url is the second in the list , url == cinema
        } else if ($url == ($newUrls[1])) {
            if ($dom->loadHTML($dataSets[1])) {

                //Search for day options and match with the available day from calendar
                $xpath = new DOMXPath($dom);
                $dayItems = $xpath->query('//select[@id = "day"]/option');
                foreach ($dayItems as $item) {
                    $itemValue = $item->getAttribute('value');
                    $itemText = $item->nodeValue;

                    //Save both value and name of day so we can output the day in text easier
                    if ($itemValue == $availableDayInCommon) {
                        $availableMovieDay["value"] = $itemValue;
                        $availableMovieDay["name"] = $itemText;
                    }
                }

                //Since the return values of the query for day and movie are json objects, we create an array to store
                //them in, loop through the movies to get the time table for the available day and status and save them
                //in our array
                $jsonObjects = array();
                $movieItems = $xpath->query('//select[@id = "movie"]/option');
                foreach ($movieItems as $item) {
                    $itemValue = $item->getAttribute('value');
                    $checkMovieUrl = $newUrls[1] . "/check?day=" . $availableMovieDay["value"] . "&movie=" . $itemValue;
                    $getRequest = curl_get_request($checkMovieUrl);
                    //Decode json objects so we can access the data
                    $jsonObject = json_decode($getRequest, true);
                    array_push($jsonObjects, $jsonObject);
                }

                //Check for the times that are not yet fully booked for every movie and save in the list for movie
                //options
                $movieOptions = array();
                $notFullyBooked = array();
                foreach ($jsonObjects as $objects) {
                    foreach ($objects as $objectValue) {
                        if ($objectValue["status"] == 1) {
                            $notFullyBooked["movie"] = $objectValue["movie"];
                            $notFullyBooked["time"] = $objectValue["time"];
                            array_push($movieOptions, $notFullyBooked);
                        }
                    }
                }

                //Change representational value of the movie to the actual title (preparation for output presentation)
                foreach ($movieOptions as &$option) {//<-----note the & to make sure it's a reference and not a copy
                    foreach ($movieItems as $item) {
                        if ($option["movie"] == $item->getAttribute('value')) {
                            $option["movie"] = $item->nodeValue;
                        }
                    }
                }
            }
        }
    }

    //Present results in a list, with the option to post a url to the scraper again
    $output =  scraper_output($movieOptions, $availableMovieDay);
    echo "<!DOCTYPE html>
      <html>
        <head>
          <meta charset='utf-8'>
          <title>Laboration 1</title>
        </head>
        <body>
            <h2>Laboration 1</h2>
            <form method='POST' action='Scraper.php'>
                <input type='text' name='source' value='$originalUrl'>
                <input type='submit' value='Submit'>
            </form>
            $output
        </body>
      </html>";
}
else {
    die("Something went wrong");
}
function scraper_output($movieOptions, $availableMovieDay){
    $ret = "<h2>Följande filmer hittades</h2>
            <div class='result'>
                <ul>";
    foreach($movieOptions as $option){
        $ret .= "<li>Filmen " . $option["movie"] . " klockan " . $option["time"] . " på " . $availableMovieDay["name"] . "</li>";
    }
    $ret .= "   </ul>
            </div>";

    return $ret;
}
function find_available_days(\DOMDocument $dom){
    //Make list of days and with status (ok or not)
    $days = $dom->getElementsByTagName('th');
    $status = $dom->getElementsByTagName('td');
    $availableDays = array();
    $count = 0;
    foreach($status as $item){
        //Since "OK" is not written in the same way in every calendar, use regular expression that is case insensitive
        if(preg_match('/ok/i', $item->nodeValue)){
            array_push($availableDays, $days[$count]->nodeValue);
        }
        $count++;
    }
    return $availableDays;
}
function get_url_list(\DOMDocument $dom, $url){
    //Find all links and return destination as a list
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
    //Retrieve data given the specified url
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}