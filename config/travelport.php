<?php

return [
    'auth'      => [
        'user'          => env('TRAVELPORT_USER'),
        'pass'          => env('TRAVELPORT_PASS'),
        'credentials'   => env('TRAVELPORT_USER').':'.env('TRAVELPORT_PASS')
    ],
    'branch'    => env('TRAVELPORT_BRANCH'),
    'url'       => env('TRAVELPORT_URL'),
    'provider'  => '1G'
];