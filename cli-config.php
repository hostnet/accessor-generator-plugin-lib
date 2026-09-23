<?php
require_once __DIR__ . '/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

$paths      = ['test/Generator/fixtures'];
$config     = ORMSetup::createAttributeMetadataConfiguration($paths, true);
$connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true], $config);

return ConsoleRunner::createHelperSet(new EntityManager($connection, $config));
