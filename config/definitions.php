<?php

namespace ChatAi\Config;

use ChatAi\Admin\ChatAiSettingsPage;
use ChatAi\Providers\SettingsPageServiceProvider;

use function DI\autowire;
use function DI\get;

return [

	SettingsPageServiceProvider::class =>
		autowire()->constructor(
			[
				get( ChatAiSettingsPage::class )
			] )
];
