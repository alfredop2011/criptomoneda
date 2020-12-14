<?php

return [
    "POST" => [
        "/CCAnalyzeriDavinci/backend/" => [
            "controller" => "CoinController",
            "method" => "index",
        ],
        "/add_to_favorites" => [
            "controller" => "CoinController",
            "method" => "store",
        ],

        "/add_new_currency" => [
            "controller" => "CoinController",
            "method" => "add_new",
        ],
    ],

    "GET" => [
        "/CCAnalyzeriDavinci/backend/" => [
            "controller" => "CoinController",
            "method" => "index",
        ],
        "/load_coins" => [
            "controller" => "CoinController",
            "method" => "index",
        ],
        "/list_favorites" => [
            "controller" => "CoinController",
            "method" => "list",
        ],
        "/get_coin" => [
            "controller" => "CoinController",
            "method" => "show",
        ],
    ]
];
