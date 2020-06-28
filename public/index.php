<?php

use Cronner\Import\Service\ImportService;
use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('UTC');

$containerBuilder = new ContainerBuilder();

$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);

$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

$service = $container->get(ImportService::class);
$service->execute();
