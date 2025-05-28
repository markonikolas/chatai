<?php

namespace ChatAi\Providers;

use ChatAi\Contracts\Registrable;
use ChatAi\Services\ChatGPT;
use Throwable;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;

class RestApiServiceProvider implements Registrable {

	const string namespace = '/chatai';

	const string version = '/v1';

	const string endpoint = '/ask';

	public function __construct( protected ChatGPT $chat_gpt_service ) { }

	public function register(): void {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function register_routes(): void {
		register_rest_route( self::namespace . self::version, self::endpoint, [
			'methods'             => 'POST',
			'callback'            => [ $this, 'handle_ask' ],
			'permission_callback' => '__return_true',
			'args'                => [
				'question' => [
					'required' => true,
					'type'     => 'string',
				],
			],
		] );
	}

	public function handle_ask( WP_REST_Request $request ): WP_Error|WP_REST_Response|WP_HTTP_Response {
		$question = $request->get_param( 'question' );

		try {
			$response = $this->chat_gpt_service->ask( $question );

			return rest_ensure_response( [ 'answer' => $response ] );
		} catch ( Throwable $e ) {
			return new WP_Error( 'chat_error', $e->getMessage(), [ 'status' => 500 ] );
		}
	}
}
