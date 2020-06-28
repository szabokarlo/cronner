<?php

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'settings' => [
            'logger'              => [
                'name'  => 'CRONNER',
                'path'  => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
            'db' => [
                'driver'    => 'mysql',
                'host'      => 'docker-mysql',
                'port'      => '3306',
                'database'  => 'imported',
                'username'  => 'root',
                'password'  => 'securedPassw0rd',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
        ]
    ]);
};
