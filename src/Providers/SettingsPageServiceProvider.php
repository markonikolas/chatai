<?php

namespace ChatAI\Providers;

use ChatAI\Contracts\SettingsPageInterface;
use ChatAI\Contracts\ProviderInterface;

class SettingsPageServiceProvider implements ProviderInterface {
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
