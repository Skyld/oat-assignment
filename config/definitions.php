<?php declare(strict_types=1);

use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
use League\Fractal\Manager;
use Psr\Container\ContainerInterface;
use Skyld\OatAssignment\Domain\User\Mapper\UserMapper;
use Skyld\OatAssignment\ErrorHandler\ErrorHandler;
use Skyld\OatAssignment\Repository\UserCsvRepository;
use Skyld\OatAssignment\Repository\UserJsonRepository;
use Skyld\OatAssignment\Repository\UserRepositoryInterface;
use Skyld\OatAssignment\Serializer\Serializer;

return [
    /**
     * You can change the repository implementation here if you desire to switch between data sources
     */
    UserRepositoryInterface::class => function (ContainerInterface $container): UserRepositoryInterface {
        return $container->get(UserJsonRepository::class);
        //return $container->get(UserCsvRepository::class);
    },

    'errorHandler' => function (ContainerInterface $container): ErrorHandler {
        return new ErrorHandler((bool)$container->get('settings.displayErrorDetails', false));
    },

    Manager::class => function (): Manager {
        return (new Manager())
            ->setSerializer(new Serializer());
    },

    'adapter.json' => function (ContainerInterface $container): AdapterInterface {
        $resourcePath = $container->get('assignment.resourcePaths')['json'];

        return new Local(pathinfo($resourcePath)['dirname']);
    },

    'adapter.csv' => function (ContainerInterface $container): AdapterInterface {
        $resourcePath = $container->get('assignment.resourcePaths')['csv'];

        return new Local(pathinfo($resourcePath)['dirname']);
    },

    UserJsonRepository::class => function (ContainerInterface $container): UserJsonRepository {
        $resourcePath = $container->get('assignment.resourcePaths')['json'];

        return new UserJsonRepository(
            new Filesystem($container->get('adapter.json')),
            $container->get(UserMapper::class),
            pathinfo($resourcePath)['basename']
        );
    },

    UserCsvRepository::class => function (ContainerInterface $container): UserCsvRepository {
        $resourcePath = $container->get('assignment.resourcePaths')['csv'];

        return new UserCsvRepository(
            new Filesystem($container->get('adapter.csv')),
            $container->get(UserMapper::class),
            pathinfo($resourcePath)['basename']
        );
    },
];
