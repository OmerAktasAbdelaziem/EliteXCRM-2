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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'megfx' => [
        'host' => "https://api.meg-fx.com",
        'pass' => "Megfx1313",
    ],

    'ark' => [
        'companyName' => "Smart Trade Mental LLC",
        'secondPass'  => "Smart Trade website",
        'userName'    => "Smart Trade website",
        'host'        => "https://ark1api.arktrader.io",
        'pass'        => "Smart Trade website",
    ],

    'phoenix' => [
        'userName' => "admin@phoenixtrader.com",
        'host'     => "https://api-admin-trader.phoenix-trader.com",
        'pass'     => "hSnvJpvCQC",
        'affUser'  => "elite@gmail.com",
        'affPass'  => "elite@gmail.com",
    ],

    'app' => [
        'demo_parent' => 13589,
        'real_parent' => 13588,
        'userName'  => "Admin24",
        'host'      => "https://platform.phooenixs.com/phoenixfxbowcf/Backoffice.svc",
        'pass'      => "Admin24",
    ],

];
