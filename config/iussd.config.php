<?php

use Bitmarshals\InstantUssd\Mapper;
use Bitmarshals\InstantUssd\UssdMenuConfig;
use InstantUssd\UssdEventListener;

return [
    "instant_ussd" => [
        "ussd_event_listener" => UssdEventListener::class,
        'db' => [
            'driver' => 'Mysqli', // Mysqli, Sqlsrv, Pdo_Sqlite, Pdo_Mysql, Pdo(= Other PDO Driver)
            'database' => '',
            'username' => '',
            'password' => '',
            //'hostname' => "",
            //'port' => "",
            //'charset' => "",
            'options' => [
                // see - http://bit.ly/2byorhL
                'buffer_results' => true,
            ]
        ],
        'ussd_menus' => [
            "home_instant_ussd" => [
                "title" => "Welcome to InstantUssd",
                "body" => "",
                "footer" => "Questions? Call +254712688559",
                "menu_items_ranking_type" => UssdMenuConfig::RANKING_TYPE_PREDETERMINED,
                "valid_keys" => range(1, 3),
                // menu_items
                "menu_items" => [
                    [
                        "next_screen" => 'iussd.register.self',
                        "description" => "Register Self"
                    ],
                    [
                        "next_screen" => 'iussd.register.client.count',
                        "description" => "Register Clients"
                    ],
                    [
                        "next_screen" => 'iussd.my_account',
                        "description" => "My Account"
                    ]
                ]
            ],
            "iussd.register.self" => [
                "title" => "Register Yourself",
                "body" => "Enter your First Name and Last Name",
                "footer" => "",
                "menu_items_ranking_type" => UssdMenuConfig::RANKING_TYPE_NONE,
                "valid_keys" => [],
                // menu_items
                "menu_items" => [
                    [
                        "next_screen" => 'iussd.final',
                        "description" => ""
                    ]
                ]
            ],
            "iussd.register.client.count" => [
                "title" => "Register Clients",
                "body" => "How many clients would you like to register?",
                "footer" => "",
                "menu_items_ranking_type" => UssdMenuConfig::RANKING_TYPE_NONE,
                "valid_keys" => [],
                // target_loopset - indicates the set of menu this
                "target_loopset" => "register_clients",
                // menu_items
                "menu_items" => [
                    [
                        "next_screen" => 'iussd.register.client',
                        "description" => ""
                    ]
                ]
            ], "iussd.register.client" => [
                "title" => "Register Client",
                "body" => "Enter client's First Name and Last Name",
                "footer" => "",
                "menu_items_ranking_type" => UssdMenuConfig::RANKING_TYPE_NONE,
                "valid_keys" => [],
                // loopset_name - a name that's shared by all screens within a 
                // looping set
                "loopset_name" => "register_clients",
                // is_loop_end - indicates that this is the last screen within a
                // loopset. We may proceed to next or go back to the first
                // screen in the set
                "is_loop_end" => true,
                // loop_start - the screen to begin looping from
                // can be itself or a previous one
                "loop_start" => "iussd.register.client",
                // menu_items
                "menu_items" => [
                    [
                        "next_screen" => 'iussd.final',
                        "description" => ""
                    ]
                ]
            ],
            "iussd.my_account" => [
                "title" => "My Account",
                "body" => "",
                "footer" => "",
                "menu_items_ranking_type" => UssdMenuConfig::RANKING_TYPE_PREDETERMINED,
                "valid_keys" => range(1, 2),
                // menu_items
                "menu_items" => [
                    [
                        "next_screen" => 'iussd.final',
                        "description" => "Registered Clients"
                    ],
                    [
                        "next_screen" => 'iussd.final',
                        "description" => "Commission Received"
                    ]
                ]
            ],
            "iussd.final" => [
                "title" => "Final Step",
                "body" => "That's InstantUssd in a nutshell. You may "
                . "delete or edit the configs and listeners to suit your use case.",
                "footer" => "",
                "menu_items_ranking_type" => UssdMenuConfig::RANKING_TYPE_NONE,
                "valid_keys" => [],
                // menu_items
                "menu_items" => [
                    [
                        // empty next_screen will force an automatic exit
                        // if user tries to send back a value
                        "next_screen" => ""
                    ]
                ]
            ]
        ],
        "service_manager" => [
            "factories" => [
                "dbAdapter" => Zend\Db\Adapter\AdapterServiceFactory::class,
                Mapper\SkippableUssdMenuMapper::class => Mapper\SkippableUssdMenuMapperFactory::class,
                Mapper\UssdLoopMapper::class => Mapper\UssdLoopMapperFactory::class,
                Mapper\UssdMenusServedMapper::class => Mapper\UssdMenusServedMapperFactory::class
            ]
        ]
    ]
];
