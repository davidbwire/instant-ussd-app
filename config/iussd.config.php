<?php

use Bitmarshals\InstantUssd\Mapper;
use InstantUssd\UssdEventManager;

return [
    "instant_ussd" => [
        "ussd_event_manager" => UssdEventManager::class,
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
        // you can add ussd_menus here or on an separate file
        // SEPARATE FILE recommended
        // 'ussd_menus' => [],
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
