<?php
return [
    'log_table_name' => 'my_phoenix_log',
    'migration_dirs' => [
        'migrations' => __DIR__ . '/app/Migrations',
    ],
    'environments' => [
        'local' => [
            'adapter' => 'mysql',
            'version' => '5.7.0', // optional - if not set it is requested from server
            'host' => 'mysql-db',
            'port' => 3306, // optional
            'username' => 'api',
            'password' => '123',
            'db_name' => 'api',
            'charset' => 'utf8mb4', // optional
            'collation' => 'utf8mb4_general_ci', // optional
        ],
    ],
    'default_environment ' => 'local',
];
