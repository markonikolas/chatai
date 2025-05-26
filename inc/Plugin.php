<?php

declare( strict_types=1 );

namespace ChatAi;

use ChatAi\Providers\Enqueuer;
use ChatAi\Providers\RestApiServiceProvider;
use ChatAi\Providers\SettingsPage;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Plugin {

	protected ContainerInterface $container;

	protected array $providers;

	public function __construct( ContainerInterface $container ) {
		$this->container = $container;

		$this->providers = [
			SettingsPage::class,
			Enqueuer::class
		];
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function boot(): void {
		foreach ( $this->providers as $providerClass ) {
			$provider = $this->container->get( $providerClass );

			if ( method_exists( $provider, 'register' ) ) {
				$provider->register();
			}
		}
	}
}
