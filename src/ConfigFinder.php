<?php
declare(strict_types=1);

namespace Next\PluginRunner;

use PTS\SymfonyDiLoader\LoaderContainer;
use ReflectionClass;
use function dirname;

class ConfigFinder extends LoaderContainer
{
    public function findConfigs(array $plugins): array
    {
        $configFiles = [];

        foreach ($plugins as $classPlugin => $envs) {
            $dir = $this->getPluginDir($classPlugin);
            $configFiles[] = $this->findDiConfigs($dir);
        }

        return array_merge(...$configFiles);
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
