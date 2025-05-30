<?php
/**
 * ChatAISettingsPage class.
 * Handles creation of a settings page.
 *
 * @package ChatAI
 */

declare( strict_types=1 );

namespace ChatAi\Pages;

use ChatAi\Contracts\Renderable;

class ChatAiSettingsPage implements Renderable {

	public function add_settings_page(): void {
		add_menu_page(
			'Chat AI Settings',
			'Chat AI',
			'manage_options',
			'chatai-settings',
			[ $this, 'render' ],
			'dashicons-admin-generic',
			80
		);
	}

	public function api_key_input_callback(): void {
		$api_key = get_option( 'chatai_api_key', '' );
		echo '<input type="text" name="chatai_api_key" value="' . esc_attr( $api_key ) . '" class="regular-text">';
	}

	public function register_settings(): void {
		register_setting( 'chatai_settings_group', 'chatai_api_key' );

		add_settings_section(
			'chatai_settings_section',
			'API Key Configuration',
			fn() => null,
			'chatai-settings-page'
		);

		add_settings_field(
			'chatai_api_key',
			'API Key',
			[ $this, 'api_key_input_callback' ],
			'chatai-settings-page',
			'chatai_settings_section'
		);
	}

	public function render(): void {
		?>
		<div class="wrap">
			<h1><?php
				_e( 'ChatAI Settings', 'chatai' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'chatai_settings_group' );
				do_settings_sections( 'chatai-settings-page' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public function register(): void {
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}
}
