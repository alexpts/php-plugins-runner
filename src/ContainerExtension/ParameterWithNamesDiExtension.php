<?php
declare(strict_types=1);

namespace Next\PluginRunner\ContainerExtension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class ParameterWithNamesDiExtension extends Extension
{

    /** @var string */
    protected $paramName;
    /** @var string */
    protected $alias;

    public function __construct(string $paramName, string $alias = null)
    {
        $this->paramName = $paramName;
        $this->alias = $alias ?? $paramName;
    }

    /**
     * Loads a specific configuration.
     *
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $values = $this->getValues($configs);
        $container->setParameter($this->paramName, $values);
    }

    protected function getValues(array $configs): array
    {
        return array_merge(...$configs);
    }

    /**
     * @inheritdoc
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getNamespace()
    {
        return false;
    }
}
