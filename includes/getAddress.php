<?php

function getAddress($latitude, $longitude) {
    $apiKey = OPENCAGE_API_KEY;
    $url = "https://api.opencagedata.com/geocode/v1/json?q=$latitude+$longitude&key=$apiKey";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if ($data && isset($data['results'][0])) {
        return $data['results'][0]['formatted'];
    }
    return 'Address not found';
}

?>

