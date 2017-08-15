<?php

use Bitmarshals\InstantUssd\Mapper;
use Bitmarshals\InstantUssd\UssdMenuConfig;
use InstantUssd\UssdEventListener;

return [
    "instant_ussd" => [
        "ussd_event_listener" => UssdEventListener::class,
        'db' => [
            'driver' => 'Mysqli', // Mysqli, Sqlsrv, Pdo_Sqlite, Pdo_Mysql, Pdo(= Other PDO Driver)
            'database' => 'your_db',
            'username' => 'mysql_user',
            'password' => 'mysql_pass',
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
                "menu_title" => "Welcome to InstantUssd",
                "is_skippable" => false,
                "menu_footer" => "Questions? Call +254712688559",
                "menu_items_ranking_type" => UssdMenuConfig::RANKING_TYPE_PREDETERMINED,
                "valid_values" => range(1, 2),
                "loopset_name" => "",
                // menu_items
                "menu_items" => [
                    [
                        "next_menu_id" => 'example_enter_full_name',
                        "description" => "Register"
                    ],
                    [
                        "next_menu_id" => 'example_my_account',
                        "description" => "My account"
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
