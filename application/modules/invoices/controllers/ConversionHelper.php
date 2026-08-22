<?php

/**
 * Fetches the USD -> $client_currency conversion rate.
 *
 * Rates are cached on disk for CONVERSION_CACHE_TTL seconds to avoid hitting
 * the API on every invoice view. On any failure the function returns null so
 * callers can fall back to USD explicitly rather than silently mis-converting.
 */
define('CONVERSION_API_URL', 'https://open.er-api.com/v6/latest/USD');
define('CONVERSION_CACHE_TTL', 60 * 60); // 1 hour
define('CONVERSION_HTTP_TIMEOUT', 5); // seconds

function getConversionRate($client_currency)
{
    if (empty($client_currency)) {
        return null;
    }

    $client_currency = strtoupper(trim($client_currency));

    $cached = conversion_cache_get($client_currency);
    if ($cached !== null) {
        return $cached;
    }

    $context = stream_context_create(array(
        'http' => array(
            'method'  => 'GET',
            'timeout' => CONVERSION_HTTP_TIMEOUT,
            'header'  => "Accept: application/json\r\n",
        ),
    ));

    $response_json = @file_get_contents(CONVERSION_API_URL, false, $context);
    if ($response_json === false) {
        return null;
    }

    $response = json_decode($response_json);
    if (!is_object($response) || empty($response->rates) || !isset($response->rates->$client_currency)) {
        // Unknown currency code or malformed response.
        return null;
    }

    $rate = (float) $response->rates->$client_currency;
    conversion_cache_set($client_currency, $rate);

    return $rate;
}

function conversion_cache_path($client_currency)
{
    return sys_get_temp_dir() . '/ip_conversion_rate_' . preg_replace('/[^A-Z]/', '', $client_currency) . '.json';
}

function conversion_cache_get($client_currency)
{
    $cache_file = conversion_cache_path($client_currency);
    if (!is_readable($cache_file)) {
        return null;
    }
    $payload = json_decode((string) file_get_contents($cache_file));
    if (!is_object($payload) || !isset($payload->fetched_at, $payload->rate)) {
        return null;
    }
    if ((time() - (int) $payload->fetched_at) > CONVERSION_CACHE_TTL) {
        return null;
    }
    return (float) $payload->rate;
}

function conversion_cache_set($client_currency, $rate)
{
    @file_put_contents(
        conversion_cache_path($client_currency),
        json_encode(array('rate' => $rate, 'fetched_at' => time()))
    );
}
