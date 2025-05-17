<?php

namespace ChatAI\Contracts;

interface SettingsPageInterface {

	public function register_settings(): void;

	public function render(): void;
}
