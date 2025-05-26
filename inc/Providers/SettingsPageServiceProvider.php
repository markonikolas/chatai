<?php

declare( strict_types=1 );

namespace ChatAi\Providers;

use ChatAi\Contracts\SettingsPageInterface;
use ChatAi\Contracts\Initializable;

class SettingsPageServiceProvider implements Initializable {
	protected array $settings_pages;

	public function __construct( array $settings_pages ) {
		$this->settings_pages = $settings_pages;
	}

	public function initialize(): void {
		foreach ( $this->settings_pages as $settings_page ) {
			if ( $settings_page instanceof SettingsPageInterface ) {
				$settings_page->initialize();
			}
		}
	}
}
