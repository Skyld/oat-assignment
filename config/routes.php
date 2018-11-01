<?php declare(strict_types=1);

use DI\Bridge\Slim\App;
use Skyld\OatAssignment\Action\GetUserAction;
use Skyld\OatAssignment\Action\ListUsersAction;

$app->group('/users', function (App $app) {
    $app->get('', ListUsersAction::class);
    $app->get('/{id:[\w]+}', GetUserAction::class);
});
