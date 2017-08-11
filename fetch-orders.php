<?php
    
    function prepareProducts($response) {
        $products = $response;
        foreach ($products as $index => $product) {
            $products[$index] = array_merge([
                                            'buyUrl' => '#',
                                            'infoUrl' => '#'
                                            ], $product);
        }
        return $products;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.veeqo.com/orders?created_at_min=2017-01-19%2011%3A10%3A01&updated_at_min=2017-01-19%2011%3A10%3A01");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                               "Content-Type: application/json",
                                               "x-api-key: PLACE API KEY HERE"
                                               
                                               ));
    $response = curl_exec($ch);
    $responseSize = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
    $time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
    $err = curl_error($ch);
    curl_close($ch);
    $response = json_decode($response, true);
    $results = [
    'id' => [],
    'error' => false,
    'time' => $time,
    'responseSize' => $responseSize
    ];
    if ($err) {
        $results['error'] = "cURL Error #:" . $err ;
    } elseif(isset($response['error_messages'])) {
        $results['error'] = "API error: " . $response['error_messages'];
    } else {
        $results['id'] = prepareProducts($response);
    }
    
    echo '<table border="1">';
    foreach ($response as $res){
        $dates = substr($res['created_at'], 0, 10);
        
        // Order for specified date
        if($dates == '2017-01-20'){
            echo '<tr>';
            echo '<td>Sale ID: </td>';
            echo '<td>' . $res['id'] . '</td>';
            echo '<td>Sale Total: </td>';
            echo '<td>' . $res['total_price'] . '</td>';
            echo '<td>Running SubTotal: </td>';
            $rTotal += $res['total_price'];
            echo '<td>' . $rTotal  . '</td>';
            echo '</tr>';
        }
    }
    echo '<tr><td colspan=6>'.$rTotal.'</td></tr>';
    echo '</table>';
