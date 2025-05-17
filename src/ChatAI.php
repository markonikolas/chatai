<?php

namespace ChatAI;

use ChatAI\Contracts\Initializable;
use ChatAI\Providers\SettingsPageProvider;

class ChatAI {
	protected array $providers = [];

	public function __construct() {
		$this->providers = [
			new SettingsPageProvider(),
		];
	}

	public function boot(): void {
		foreach ( $this->providers as $provider ) {
			if ( $provider instanceof Initializable ) {
				$provider->initialize();
			}
		}
	}
}
