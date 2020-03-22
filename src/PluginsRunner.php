<?php
declare(strict_types = 1);

namespace Next\PluginRunner;

use PTS\Events\EventsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PluginsRunner
{
    protected EventsInterface $events;

    public function __construct(EventsInterface $events)
    {
        $this->events = $events;
    }

    /**
     * @param string[] $plugins
     * @param ContainerInterface $container
     */
	public function init(array $plugins, ContainerInterface $container): void
    {
		foreach ($plugins as $class => $env) {
		    /** @var Plugin $plugin */
		    $plugin = new $class($container);
			$plugin->init();
		}

        $this->events->emit('plugins.init');
    }
}
