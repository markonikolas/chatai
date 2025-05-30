<?php
/*
 * Plugin Name:       ChatAI
 * Description:       An AI Chat bot that answers questions based on your sites content.
 * Version:           0.0.1
 * Requires at least: 6.4
 * Requires PHP:      8.3
 * Author:            Marko Nikolaš
 * Author URI:        https://github.com/markonikolas
 * License:           GPL v2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       chatai
 * Domain Path:       /languages
 */

use ChatAi\Plugin;
use DI\ContainerBuilder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const __CHATAI_PLUGIN_FILE__ = __FILE__;

try {
	require_once __DIR__ . '/vendor/autoload.php';

	$definitions = require_once __DIR__ . '/config/definitions.php';

	$builder = new ContainerBuilder();
	$builder->useAutowiring( true );
	$builder->addDefinitions( $definitions );

	$container = $builder->build();
	$plugin    = $container->get( Plugin::class );

	$plugin->register_lifecycle_hooks();

	add_action( 'plugins_loaded', function () use ( $plugin ) {
		$plugin->boot();
	} );
} catch ( NotFoundExceptionInterface $e ) {
	error_log( 'Service not found: ' . $e->getMessage() );
} catch ( ContainerExceptionInterface $e ) {
	error_log( 'Container error: ' . $e->getMessage() );
} catch ( Exception $e ) {
	error_log( 'Generic error: ' . $e->getMessage() );
}
