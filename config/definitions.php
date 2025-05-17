<?php

namespace ChatAi\Config;

use ChatAi\Admin\ChatAISettingsPage;
use ChatAi\Providers\SettingsPageServiceProvider;

use function DI\autowire;
use function DI\get;

return [

	SettingsPageServiceProvider::class =>
		autowire()->constructor(
			[
				get( ChatAISettingsPage::class )
			] ),

];
