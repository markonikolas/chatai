<?php

namespace ChatAI\Bootstrap;

use ChatAI\Contracts\ContainerInterface;
use ChatAI\Providers\SettingsPageProvider;

class Plugin {

	protected ContainerInterface $container;

	protected array $providers = [
		SettingsPageProvider::class,
	];

	public function __construct(ContainerInterface $container) {
		$this->container = $container;

		foreach ($this->providers as $provider) {
			$this->container->set($provider, $container->get($provider));
		}
	}

	public function boot(): void {
		foreach ($this->providers as $provider) {
			$this->container->get($provider)->initialize();
		}
	}
}
