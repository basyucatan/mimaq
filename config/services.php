<?php

return [
'goAduanas' => [
    'url' => env('GO_ADUANAS_URL'),
    'token' => env('GO_ADUANAS_TOKEN'),
    'timeout' => (int) env('GO_ADUANAS_TIMEOUT', 30),
    'ambiente' => env('GO_ADUANAS_AMBIENTE', 'QA'),
    'panel' => [
        'servidor' => env('GO_ADUANAS_PANEL_SERVIDOR'),
        'usuario' => env('GO_ADUANAS_PANEL_USUARIO'),
        'password' => env('GO_ADUANAS_PANEL_PASSWORD'),
    ],
],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
