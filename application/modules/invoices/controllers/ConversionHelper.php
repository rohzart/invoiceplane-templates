<?php

function getConversionRate($client_currency){
    $currency_api = 'https://open.er-api.com/v6/latest/USD';
    if(!empty($client_currency)){
        $response_json = file_get_contents($currency_api);
        if(false !== $response_json) {
            $response_object = json_decode($response_json);
            return $response_object->rates->$client_currency;
        }
    }
    return 0;
}

?>