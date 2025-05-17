<?php

namespace ChatAi\Contracts;

interface SettingsPageInterface {

	public function register_settings(): void;

	public function render(): void;
}
