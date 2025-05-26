<?php

namespace ChatAi\Config;

use ChatAi\Pages\ChatAiSettingsPage;
use ChatAi\Providers\SettingsPage;

use function DI\autowire;
use function DI\get;

return [

	SettingsPage::class =>
		autowire()->constructor(
			[
				get( ChatAiSettingsPage::class )
			] )
];
