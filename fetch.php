<?php

if (isset($_POST['submit'])) {
    $keycheck = $_POST['key'];
    $url = "https://kichhoat24h.com/user-api/check-key?keys=" . urlencode($keycheck) . "&token=VLbuvBpvUW1XZUJaay8vbDBiMGc3Yzg3Q1AvSWlCeGkrQmF3MGZsMUdkRVNieTFKaz0&mode=0";
    
    // Set up stream context options to handle CORS
    $options = [
        'http' => [
            'method' => 'GET',
            'header' => "Origin: *" // Replace frontend domain
        ]
    ];

    $context = stream_context_create($options);
    
    try {
        $data = file_get_contents($url, false, $context);
        
        if ($data !== false) {

          echo $data;
        
        } else {
            echo "Unable to fetch data from the URL.";
        }
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
}

?>
