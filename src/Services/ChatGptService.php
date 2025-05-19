<?php

namespace ChatAi\Services;

use RuntimeException;

class ChatGptService {

	protected string $api_key;

	protected string $api_endpoint = 'https://api.openai.com/v1/chat/completions';

	public function __construct() {
		$this->api_key = get_option( 'chatai_api_key' );
	}

	public function ask( string $question ): string {
		if ( empty( $question ) ) {
			throw new RuntimeException( 'Please provide a valid question' );
		}

		$payload = [
			'model'    => 'gpt-4.1-nano',
			'messages' => [
				[ 'role' => 'user', 'content' => $question ]
			],
		];

		$response = wp_remote_post( $this->api_endpoint, [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->api_key,
				'Content-Type'  => 'application/json',
			],
			'body'    => wp_json_encode( $payload ),
		] );

		if ( is_wp_error( $response ) ) {
			throw new RuntimeException( $response->get_error_message() );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		return $body['choices'][0]['message']['content'] ?? 'No answer returned.';
	}
}
