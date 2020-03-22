<?php
declare(strict_types=1);

namespace Next\PluginRunner;

use PTS\SymfonyDiLoader\FactoryContainer;
use PTS\SymfonyDiLoader\LoaderContainer;
use ReflectionClass;
use ReflectionException;
use function dirname;

class LoaderContainerFromPlugins extends LoaderContainer
{
	/**
	 * @param string[] $classPlugins
	 * @param string $cacheFile
	 * @param FactoryContainer $factory
	 *
	 * @throws ReflectionException
	 */
	public function __construct(array $classPlugins, string $cacheFile, FactoryContainer $factory)
	{
		$configFiles = [];

		foreach ($classPlugins as $classPlugin => $envs) {
		    $dir = $this->getPluginDir($classPlugin);
			$configFiles[] = $this->findDiConfigs($dir);
		}

		$configFiles = array_merge(...$configFiles);

		parent::__construct($configFiles, $cacheFile, $factory);
	}

    protected function getPluginDir(string $classPlugin): string
    {
        $reflector = new ReflectionClass($classPlugin);
        $filename = $reflector->getFileName();
        return dirname($filename);
    }

    protected function findDiConfigs(string $dir): array
    {
        return (array)glob($dir . '/config/di/*.yml');
    }
}
