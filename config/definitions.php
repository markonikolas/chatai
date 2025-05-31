<?php

namespace ChatAi\Config;

use ChatAi\Contracts\Storable;
use ChatAi\Pages\ChatAiSettingsPage;
use ChatAi\Providers\SettingsPage;

use function DI\autowire;
use function DI\get;

global $wpdb;

return [
	SettingsPage::class =>
		autowire()->constructor(
			[
				get( ChatAiSettingsPage::class )
			] ),

	Storable::class => autowire()->constructor( $wpdb ),
];
