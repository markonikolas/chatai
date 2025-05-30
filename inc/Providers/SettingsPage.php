<?php

declare( strict_types=1 );

namespace ChatAi\Providers;

use ChatAi\Contracts\Registrable;
use ChatAi\Contracts\Renderable;

class SettingsPage implements Registrable {
	public function __construct( protected array $settings_pages ) { }

	public function register(): void {
		foreach ( $this->settings_pages as $settings_page ) {
			if ( $settings_page instanceof Renderable ) {
				$settings_page->register();
			}
		}
	}
}
