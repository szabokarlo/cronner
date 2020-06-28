<?php

use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('UTC');

$containerBuilder = new ContainerBuilder();

$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);

$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

$pdo = $container->get(PDO::class);

$file = __DIR__ . '/../database/loadfile.csv';

$enclose        = '"';
$fieldSeparator = ";";
$lineSeparator  = "\n";

$sql = "LOAD DATA LOCAL INFILE '$file'
   INTO TABLE product
   FIELDS TERMINATED BY " . $pdo->quote($fieldSeparator) . "
   ENCLOSED BY " . $pdo->quote($enclose) . "
   LINES TERMINATED BY " . $pdo->quote($lineSeparator) . "
   IGNORE 1 LINES
";

$start = microtime(true);

$result = $pdo->exec($sql);

$end  = microtime(true);
$time = round(($end - $start) * 1000);

$container->get(LoggerInterface::class)
    ->info('Load file is inserted ' . $result . ' records within: ' . $time . ' msec');
