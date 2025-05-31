<?php

declare( strict_types=1 );

namespace ChatAi;

use ChatAi\Providers\CronProvider;
use ChatAi\Providers\Enqueuer;
use ChatAi\Providers\RestApiServiceProvider;
use ChatAi\Providers\SettingsPage;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final readonly class Plugin {

	private array $providers;

	public function __construct(
		private ContainerInterface $container,
		private CronProvider $cronProvider,
	) {
		$this->providers = [
			SettingsPage::class,
			Enqueuer::class,
			RestApiServiceProvider::class,
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

	public function register_lifecycle_hooks(): void {
		register_activation_hook( __CHATAI_PLUGIN_FILE__, [ $this->cronProvider, 'registerEvents' ] );
		register_deactivation_hook( __CHATAI_PLUGIN_FILE__, [ $this->cronProvider, 'unregisterEvents' ] );
		register_uninstall_hook( __CHATAI_PLUGIN_FILE__, [ cronProvider::class, 'uninstall' ] );
	}
}
