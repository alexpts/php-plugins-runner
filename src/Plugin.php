<?php
declare(strict_types = 1);

namespace Next\PluginRunner;

use Psr\Container\ContainerInterface;
use PTS\Events\EventsInterface;
use Symfony\Component\DependencyInjection\Container;

abstract class Plugin
{
    /** @var ContainerInterface|Container */
    protected $container;
    /** @var EventsInterface */
    protected $events;

    /** @var string|null */
    protected $dir;

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
            $reflector = new \ReflectionClass($this);
            $filename = $reflector->getFileName();
            $this->dir = \dirname($filename);
        }

        return $this->dir;
    }
}
