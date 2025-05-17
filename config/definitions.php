<?php

namespace ChatAI\Config;

use ChatAI\Admin\ChatAISettingsPage;
use ChatAI\Providers\SettingsPageProvider;

use function DI\autowire;
use function DI\get;

return [

	SettingsPageProvider::class =>
		autowire()->constructor(
			[
				get( ChatAISettingsPage::class )
			] ),

];
