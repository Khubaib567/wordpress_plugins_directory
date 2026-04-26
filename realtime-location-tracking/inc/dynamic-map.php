<?php

function get_airports_callback() {

    check_ajax_referer('my_ajax_map', 'nonce');

    $query = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';


    $api_url = 'https://www.treasureonlineapi.co.uk/CabTreasureWebApi/Home/GetLocationDataNew';

    $payload = [
        "getPlacesRequest" => [
            "defaultClientId" => 4999,
            "keyword"         => "$query",
            "placeType"       => "address",
            "lat"             => 0.0,
            "lng"             => 0.0,
            "apiKey"          => "",
            "fetchString"     => "",
            "radiusInMiles"   => -1.0,
            "hashKey"         => "49994321orue",
            "locationType"    => "address"
        ],
        "Token" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjQ5OTkiLCJuYmYiOjE3NzYwNzYxMzcsImV4cCI6MTc3ODY2ODEzNywiaWF0IjoxNzc2MDc2MTM3fQ.oqDQH4CVQOJCJv9EVpNzZ9QyOCGYCnva623MCOmtabI"
    ];

    $response = wp_remote_post($api_url, [
        'method'  => 'POST',
        'timeout' => 15,
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json'
        ],
        'body' => wp_json_encode($payload)
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error([
            'message' => $response->get_error_message()
        ]);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    $locations = $data['Data']['searchLocations'];

    $results = [];

    // ⚠️ Adjust based on actual API response structure
    if (!empty($locations)) {
        foreach ($locations as $item) {
            $results[] = [
                'name' => $item['FullLocationName'] ?? 'Unknown',
                'lat'  => $item['Latitude'] ?? 0,
                'lng'  => $item['Longitude'] ?? 0,
            ];
        }
    }

    wp_send_json_success($results);
}

add_action('wp_ajax_get_airports', 'get_airports_callback');
add_action('wp_ajax_nopriv_get_airports', 'get_airports_callback');