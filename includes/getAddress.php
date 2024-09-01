
<?php

function getAddress($latitude, $longitude) {
    $apiKey = 'ZWUFEvqrALs4WtVDTw4yjUGGjkFPTGGE';
    $url = "https://api.tomtom.com/search/2/reverseGeocode/$latitude,$longitude.json?key=$apiKey";
    
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if ($data && isset($data['addresses'][0])) {
        return $data['addresses'][0]['address']['freeformAddress'];
    }
    return 'Address not found';
}


?>
