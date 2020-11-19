<?php

    require_once "connection.php";
    $weather = "";
    $error = "";
    
    // Checking if the cirtfield is present
    if (array_key_exists('city', $_GET)) {
        // Removing training spaces
        $city = str_replace(' ', '', $_GET['city']);
        // Using the header file returned by the server to check if the city exsist or not
        $file_headers = @get_headers("https://www.weather-forecast.com/locations/".$city."/forecasts/latest");
        // City not found
        if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $error = "That city could not be found.";
        // City found
        } else {
            $forecastPage = file_get_contents("https://www.weather-forecast.com/locations/".$city."/forecasts/latest");
            $pageArray = explode('Weather Today</h2> (1&ndash;3 days)</div><p class="b-forecast__table-description-content"><span class="phrase">', $forecastPage);
            $middlepageArray = explode('Weather (4&ndash;7 days)</h2></div><p class="b-forecast__table-description-content"><span class="phrase">', $pageArray[1]);
        if (sizeof($middlepageArray) > 1) {
                $secondPageArray = explode('.</span></p></td><td class="b-forecast__table-description-cell--js" colspan="9"><div class="b-forecast__table-description-title"><h2>', $middlepageArray[0]);
                if (sizeof($secondPageArray) > 1) {
                    $weather = $secondPageArray[0];  
                    $city = $_GET['city'];
                    // Inserting the data into the database
                    $sql = "INSERT INTO data (city, report) VALUES ('$city', '$weather')";
                    mysqli_query($conn, $sql);
                } else {                    
                    $error = "That city could not be found.";                    
                }
            } else {
                $error = "That city could not be found.";
            }
        }
    }

?>