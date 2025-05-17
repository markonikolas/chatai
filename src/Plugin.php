<?php

namespace ChatAI;

use ChatAI\Providers\SettingsPageProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Plugin {

	protected ContainerInterface $container;

	protected array $providers;

	public function __construct( ContainerInterface $container ) {
		$this->container = $container;

		$this->providers = [
			SettingsPageProvider::class,
		];
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function boot(): void {
		foreach ( $this->providers as $providerClass ) {
			$provider = $this->container->get( $providerClass );

			if ( method_exists( $provider, 'initialize' ) ) {
				$provider->initialize();
			}
		}
	}
}
