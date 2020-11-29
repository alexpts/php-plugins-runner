<?php
declare(strict_types=1);

namespace Next\PluginRunner;

use Exception;
use Psr\Container\ContainerInterface;
use PTS\Events\EventEmitterInterface;
use PTS\SymfonyDiLoader\LoaderContainer;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class AppCreator
{
    /**
     * @param string $projectDir
     * @param ExtensionInterface[] $extensions
     *
     * @return ContainerInterface
     * @throws Exception
     */
    public static function create(string $projectDir, array $extensions = []): ContainerInterface
    {
        $pluginsList = require $projectDir . '/plugins.php';
        $cacheContainerFile = $projectDir . '/runtime/cache/di.cache.php';

        $loader = new LoaderContainer;
        $configFinder = new ConfigFinder;
        self::applyExtension($loader, $extensions);

        $container = $loader->getContainer($configFinder->findConfigs($pluginsList), $cacheContainerFile);
        $events = $container->get(EventEmitterInterface::class);

        $runnerPlugins = new PluginsRunner($events);
        $runnerPlugins->init($pluginsList, $container);

        return $container;
    }

    protected static function applyExtension(LoaderContainer $loader, array $extensions): void
    {
        foreach ($extensions as $extension) {
            $loader->addExtension($extension);
        }
    }
}
