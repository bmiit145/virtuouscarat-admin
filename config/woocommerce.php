<?php

return [
'url' => env('WOOCOMMERCE_URL'),
'consumer_key' => env('WOOCOMMERCE_CONSUMER_KEY'),
'consumer_secret' => env('WOOCOMMERCE_CONSUMER_SECRET'),
'options' => [
'wp_api' => true, // Enable the WP REST API integration
'version' => 'wc/v3', // WooCommerce API version
'verify_ssl' => false, // Disable SSL verification
'timeout' => 600, // Request timeout
],
];
