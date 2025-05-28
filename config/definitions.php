<?php

namespace ChatAi\Config;

use ChatAi\Pages\ChatAiSettingsPage;
use ChatAi\Providers\SettingsPage;
use ChatAi\Providers\RestApiServiceProvider;
use ChatAi\Services\ChatGptService;

use function DI\autowire;
use function DI\get;

return [

	SettingsPage::class =>
		autowire()->constructor(
			[
				get( ChatAiSettingsPage::class )
			] ),

	RestApiServiceProvider::class =>
		autowire()->constructor(
			get( ChatGptService::class )
		)
];
