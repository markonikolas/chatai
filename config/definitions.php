<?php

namespace ChatAi\Config;

use ChatAi\Pages\ChatAiSettingsPage;
use ChatAi\Providers\SettingsPage;
use ChatAi\Repository\EmbeddingRepository;

use function DI\autowire;
use function DI\get;

global $wpdb;

return [
	SettingsPage::class =>
		autowire()->constructor(
			[
				get( ChatAiSettingsPage::class )
			] ),

	EmbeddingRepository::class => autowire()->constructor( $wpdb ),
];
