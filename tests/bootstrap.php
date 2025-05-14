<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package Plugin Boilerplate
 */

namespace PluginBoilerplate\Tests;

use function Yoast\WPTestUtils\WPIntegration\get_path_to_wp_test_dir;
use function Yoast\WPTestUtils\WPIntegration\bootstrap_it;

require_once dirname(__DIR__) . '/vendor/yoast/wp-test-utils/src/WPIntegration/bootstrap-functions.php';

$_tests_dir = get_path_to_wp_test_dir();

// Get access to tests_add_filter() function.
require_once $_tests_dir . 'includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin(): void {
	require __DIR__ . '/../plugin.php';
}
tests_add_filter('muplugins_loaded', __NAMESPACE__ . '\_manually_load_plugin');

if ($_tests_dir === '/usr/src/vendor/wordpress') {
	define('WP_TESTS_CONFIG_FILE_PATH', __DIR__ . '/../tests/integration/wp-tests-config.php');
}

/*
 * Bootstrap WordPress. This will also load the Composer autoload file, the PHPUnit Polyfills
 * and the custom autoloader for the TestCase and the mock object classes.
 */
bootstrap_it();
