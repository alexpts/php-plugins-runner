<?php
declare(strict_types = 1);

namespace Next\PluginRunner;

use Exception;
use Psr\Container\ContainerInterface;
use PTS\Events\EventsInterface;
use PTS\SymfonyDiLoader\FactoryContainer;
use ReflectionException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AppCreator
{
    /**
     * @param string $projectDir
     * @param ExtensionInterface[] $extensions
     *
     * @return ContainerInterface
     * @throws ReflectionException
     * @throws Exception
     */
    public static function create(string $projectDir, array $extensions = []): ContainerInterface
    {
        $pluginsList = require $projectDir . '/plugins.php';
        $cacheContainerFile = $projectDir . '/runtime/cache/di.cache.php';

        $factory = new FactoryContainer(YamlFileLoader::class, new FileLocator);
        $loader = new LoaderContainerFromPlugins($pluginsList, $cacheContainerFile, $factory);

        self::applyExtension($loader, $extensions);
        $container = $loader->getContainer();
        $events = $container->get(EventsInterface::class);

        $runnerPlugins = new PluginsRunner($events);
        $runnerPlugins->init($pluginsList, $container);

        return $container;
    }

    protected static function applyExtension(LoaderContainerFromPlugins $loader, array $extensions): void
    {
        foreach ($extensions as $extension) {
            $loader->addExtension($extension);
        }
    }
}
