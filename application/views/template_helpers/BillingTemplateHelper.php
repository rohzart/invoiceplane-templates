<?php

function conversion_api_attribution($conversion_rate)
{
    if (!($conversion_rate == 1 || $conversion_rate == 0 || $conversion_rate === null)) {
        return '<a href="https://www.exchangerate-api.com">Rates By Exchange Rate API</a>';
    }
    return '';
}

function format_currency_by_client_setting($client_currency, $conversion_rate, $amount)
{
    // Fall back to USD (InvoicePlane's configured currency) when there is no
    // usable conversion rate. Amounts are not pre-rounded here; rounding is
    // left to number_format so per-line rounding drift cannot accumulate.
    if ($conversion_rate !== null && $conversion_rate != 0 && $client_currency !== '') {
        return number_format(($amount * $conversion_rate), 2) . ' ' . $client_currency;
    }

    return format_currency($amount);
}

function highlight_by_status($section, $status)
{
    $color = 'black';
    $sections = array();
    if ($status == 'initial') {
        $color = 'blue';
        $sections = array('invoice_date', 'due_date', 'amount_due', 'balance');
    }
    if ($status == 'overdue') {
        $color = 'red';
        $sections = array('due_date', 'amount_due', 'balance');
    }
    if ($status == 'paid') {
        $color = 'green';
        $sections = array('amount_due', 'balance');
    }
    return (in_array($section, $sections) ? 'text-' . $color : '');
}

function print_array_safely($delimiter, array $params)
{
    $array = array();
    foreach ($params as $param) {
        if ($param) {
            array_push($array, $param);
        }
    }
    echo implode($delimiter, $array);
}

function print_array_key_value_safely($delimiter, $key_value_delimiter, array $params)
{
    $array = array();
    foreach ($params as $label => $value) {
        if ($value) {
            array_push($array, $label . $key_value_delimiter . $value);
        }
    }
    echo implode($delimiter, $array);
}

/**
 * Builds a safe PayPal.Me payment link from the stored custom field.
 * Returns null when the field is missing or not an https:// paypal.me URL.
 */
function build_paypal_payment_link($paypal_me_field, $amount_string)
{
    $url = trim((string) $paypal_me_field);
    if ($url === '' || stripos($url, 'https://paypal.me/') !== 0) {
        return null;
    }
    return htmlsc($url . str_replace(array(' ', ','), '', $amount_string));
}

$client_currency = (empty($custom_fields['client']['Currency']) ? '' : $custom_fields['client']['Currency']);
$conversion_rate = isset($custom_fields['invoice']['Conversion Rate']) ? $custom_fields['invoice']['Conversion Rate'] : null;

?>
