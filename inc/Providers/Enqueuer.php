<?php

declare( strict_types=1 );

namespace ChatAi\Providers;

use ChatAi\Contracts\Registrable;

class Enqueuer implements Registrable {

	public function enqueue(): void {
		$asset_file = require_once dirname( __DIR__, 2 ) . '/build/index.asset.php';
		$file       = plugin_dir_url( dirname( __DIR__ ) ) . '/build/index.js';

		wp_enqueue_script(
			'chatai-main',
			$file,
			$asset_file['dependencies'],
			$asset_file['version']
		);
	}

	public function register(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'wp_footer', function () {
			echo '<div id="chatai-input"></div>';
		} );
	}
}
