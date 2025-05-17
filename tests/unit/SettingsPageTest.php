<?php
/**
 * Class SettingsPageTest
 *
 * @package Plugin Boilerplate
 */

namespace Tests\Unit;

use ChatAi\Admin\ChatAiSettingsPage;
use PHPUnit\Framework\TestCase;

class SettingsPageTest extends TestCase {
	protected ChatAiSettingsPage $settings_page;

	protected function setUp(): void {
		parent::setUp();

		$this->settings_page = new ChatAiSettingsPage();
	}

	public function test_initialize_does_not_throw(): void {
		$this->settings_page->initialize();
		$this->assertTrue( true );
	}

	public function test_api_key_input_callback_outputs_field(): void {
		$expected = 'testingabc123';
		update_option( 'chatai_api_key', $expected );

		ob_start();
		$this->settings_page->api_key_input_callback();
		$output = ob_get_clean();

		$this->assertStringContainsString( 'name="chatai_api_key"', $output );
		$this->assertStringContainsString( $expected, $output );
	}
}
