<?php
/*
 * Plugin Name:       ChatAI
 * Description:       Plugin boilerplate description.
 * Version:           0.0.1
 * Requires at least: 6.4
 * Requires PHP:      8.3
 * Author:            Marko Nikolaš
 * Author URI:        https://github.com/markonikolas
 * License:           GPL v2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       plugin-boilerplate
 * Domain Path:       /languages
 */

use ChatAI\ChatAI;

if (!defined('ABSPATH')) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

$plugin = new ChatAI();
$plugin->boot();
