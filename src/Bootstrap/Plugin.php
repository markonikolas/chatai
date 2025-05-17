<?php

namespace ChatAI\Bootstrap;

use ChatAI\Contracts\ContainerInterface;
use ChatAI\Contracts\ProviderInterface;
use ChatAI\Providers\SettingsPageProvider;
use LogicException;

class Plugin {

	protected ContainerInterface $container;

	protected array $providers;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;

		$this->providers = [
			SettingsPageProvider::class,
		];

		foreach ($this->providers as $provider_class) {
			if ( ! is_subclass_of( $provider_class, ProviderInterface::class ) ) {
				throw new LogicException(
					sprintf('%s must implement %s', $provider_class, ProviderInterface::class)
				);
			}

			if ( ! $this->container->has($provider_class) ) {
				$this->container->set( $provider_class, fn( $c ) => new $provider_class( $c ) );
			}
		}
	}

	public function boot(): void {
		foreach ( $this->providers as $provider_class ) {
			$provider = $this->container->get($provider_class);

			if (method_exists($provider, 'initialize')) {
				$provider->initialize();
			}
		}
	}
}
