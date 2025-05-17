<?php

namespace ChatAI\Providers;

use ChatAI\Admin\SettingsPage;
use ChatAI\Contracts\Initializable;

class SettingsPageProvider implements Initializable
{

    public function initialize(): void
    {
			$settings_page = new SettingsPage();
			$settings_page->initialize();
    }
}
