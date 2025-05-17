<?php

namespace ChatAI\Providers;

use ChatAI\Admin\SettingsPage;
use ChatAI\Contracts\ProviderInterface;

class SettingsPageProvider implements ProviderInterface
{

    public function initialize(): void
    {
			$settings_page = new SettingsPage();
			$settings_page->initialize();
    }
}
