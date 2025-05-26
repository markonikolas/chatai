<?php

namespace ChatAi\Config;

use ChatAi\Providers\SettingsPage;
use ChatAi\Services\Admin\Pages\ChatAiSettingsPage;

use function DI\autowire;
use function DI\get;

return [

	SettingsPage::class =>
		autowire()->constructor(
			[
				get( ChatAiSettingsPage::class )
			] )
];
