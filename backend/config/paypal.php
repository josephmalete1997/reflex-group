<?php
return [
    'client_id' => getenv('PAYPAL_CLIENT_ID') ?: '',
    'currency' => getenv('PAYPAL_CURRENCY') ?: 'ZAR',
];
