<?php
function getConversionRate($client_currency){
    // $currency_api = 'https://api.exchangerate-api.com/v4/latest/USD';
    $currency_api = 'https://api.exchangeratesapi.io/latest?base=USD';
    if(!empty($client_currency)){
        $response_json = file_get_contents($currency_api);
        if(false !== $response_json) {
            $response_object = json_decode($response_json);
            return $response_object->rates->$client_currency;
        }
    }
    return 0;
}

function format_currency_by_client_setting($client_currency, $conversion_rate, $amount) {
    if($conversion_rate != 0){
        return round(($amount * $conversion_rate), 2) . ' ' . $client_currency;
    }
    else{
        return format_currency($amount);
    }
}

function highlight_by_status($section, $status){
    $color = 'black';
    $sections = array();
    if($status == 'initial'){
        $color = 'blue';
        $sections = array('invoice_date', 'due_date', 'amount_due', 'balance');
    }
    if($status == 'overdue'){
        $color = 'red';
        $sections = array('due_date', 'amount_due', 'balance');
    }
    if($status == 'paid'){
        $color = 'green';
        $sections = array('amount_due', 'balance');
    }
    return (in_array($section, $sections) ? 'text-' . $color : '');
}

$client_currency = (empty($custom_fields['client']['Currency']) ? '' : $custom_fields['client']['Currency']);
$conversion_rate = getConversionRate($client_currency);

?>