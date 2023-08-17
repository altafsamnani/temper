<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use Ahc\Env\Loader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class RegisterServices
{
    private static ContainerBuilder $container;

    public static function init(): ContainerBuilder
    {
        if (isset(self::$container)) {
            return self::$container;
        }
        self::bootstrapEnvVariables();
        self::$container = new ContainerBuilder();
        self::bootstrapServices();

        return self::$container;
    }

    private static function bootstrapEnvVariables(): void
    {
        $envLoader = new Loader();
        $envLoader->load(__DIR__ . '/../../../.env', true, Loader::ALL);
    }

    private static function bootstrapServices(): void
    {
        $loader = new YamlFileLoader(self::$container, new FileLocator(__DIR__.'/../../../config'));
        $loader->load('config.yml');
        self::$container->compile(true);
    }
}
