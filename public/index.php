<?php declare(strict_types=1);

use DI\Bridge\Slim\App;
use DI\ContainerBuilder;

if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

$app = new class() extends App {
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions(
            __DIR__ . '/../config/settings.php',
            __DIR__ . '/../config/definitions.php'
        );
    }
};

require __DIR__ . '/../config/middleware.php';
require __DIR__ . '/../config/routes.php';

$app->run();
