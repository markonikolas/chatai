<?php

namespace ChatAI\Config;

use ChatAI\Admin\ChatAISettingsPage;
use ChatAI\Providers\SettingsPageServiceProvider;

use function DI\autowire;
use function DI\get;

return [

	SettingsPageServiceProvider::class =>
		autowire()->constructor(
			[
				get( ChatAISettingsPage::class )
			] ),

];
