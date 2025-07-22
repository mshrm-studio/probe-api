<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'lil_nouns' => [
        'contract' => [
            'token_address' => env('LIL_NOUNS_TOKEN_CONTRACT_ADDRESS')
        ]
    ],

    'nouns' => [
        'contract' => [
            'auction_house_address' => env('NOUNS_AUCTION_HOUSE_CONTRACT_ADDRESS'),
            'token_address' => env('NOUNS_TOKEN_CONTRACT_ADDRESS')
        ],
        'subgraph_id' => env('NOUNS_SUBGRAPH_ID')
    ],

    'infura' => [
        'url' => env('INFURA_URL')
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

    'subgraph' => [
        'api_key' => env('SUBGRAPH_API_KEY'),
    ],
];
