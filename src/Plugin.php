<?php
declare(strict_types=1);

namespace Next\PluginRunner;

use Psr\Container\ContainerInterface;
use PTS\Events\EventEmitterInterface;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Container;
use function dirname;

abstract class Plugin
{
    protected ContainerInterface|Container $container;
    protected EventEmitterInterface $events;

    /** @var string|null */
    protected ?string $dir = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->events = $container->get('events');
    }

    public function init(): void
    {

    }

    protected function getDir(): string
    {
        if (!$this->dir) {
            $reflector = new ReflectionClass($this);
            $filename = $reflector->getFileName();
            $this->dir = dirname($filename);
        }

        return $this->dir;
    }
}
