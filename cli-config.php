<?php
require_once __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;

$paths         = [ 'test/Generator/fixtures'];
$config        = Setup::createAnnotationMetadataConfiguration($paths, true, null, null, false);
$entityManager = EntityManager::create([driver => 'pdo_sqlite', 'memory' => true], $config);

return ConsoleRunner::createHelperSet($entityManager);
