<?php

declare( strict_types=1 );

namespace ChatAi;

use ChatAi\Controllers\EmbeddingController;
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
		private EmbeddingController $embeddingController
	) {
		$this->providers = [
			SettingsPage::class,
			Enqueuer::class,
			RestApiServiceProvider::class,
			CronProvider::class,
		];
	}

	/**
	 * Needs to be called statically to trigger
	 * the register_uninstall_hook.
	 *
	 * @see register_uninstall_hook()
	 */
	public static function uninstall(): void {
		global $wpdb;

		$table_name = $wpdb->prefix . 'posts';

		$column_names = [
			'embedding',
			'clean_text',
		];

		foreach ( $column_names as $column_name ) {
			$exists = $wpdb->get_results( "SHOW COLUMNS FROM `$table_name` LIKE '$column_name'" );

			if ( ! empty( $exists ) ) {
				$wpdb->query( "ALTER TABLE `$table_name` DROP COLUMN `$column_name`" );
			}
		}
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
		register_activation_hook( __CHATAI_PLUGIN_FILE__, [ $this, 'init_cron' ] );
		register_uninstall_hook( __CHATAI_PLUGIN_FILE__, [ self::class, 'uninstall' ] );
		register_deactivation_hook( __CHATAI_PLUGIN_FILE__, [ $this->embeddingController, 'unregister_cron' ] );
	}

	public function init_cron(): void {
		$this->embeddingController->register_cron();
		$this->embeddingController->create_column();
	}
}
