<?php

namespace ChatAi\Config;

use ChatAi\Admin\ChatAiSettingsPage;
use ChatAi\Providers\RestApiServiceProvider;
use ChatAi\Providers\SettingsPageServiceProvider;

use ChatAi\Services\ChatGptService;

use function DI\autowire;
use function DI\get;

return [

	SettingsPageServiceProvider::class =>
		autowire()->constructor(
			[
				get( ChatAiSettingsPage::class )
			] ),

	RestApiServiceProvider::class =>
		autowire()->constructor(
			get( ChatGptService::class )
		)
];
