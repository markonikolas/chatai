<?php

namespace ChatAi\Services;

use RuntimeException;
use WP_Error;

class ChatGPTService {

	protected string $api_key;

	public function __construct( protected PageService $pages_service ) {
		$this->api_key = get_option( 'chatai_api_key' );
	}

	public function ask( string $query ) {
		if ( empty( $query ) ) {
			throw new RuntimeException( 'Please provide a valid question' );
		}

		$query_embedding = $this->embed( $query );
		$context         = $this->pages_service->get_top_results( $query_embedding, dirname( __DIR__, 2 ) . '/content.db' );

		$input = "Answer the question based only on the following content: $context \n\n Question: $query \n\n Answer:";

		$payload = [
			'model' => 'gpt-4.1-nano',
			'input' => $input,
		];

		$response = wp_remote_post( "https://api.openai.com/v1/responses", [
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

		return $body['output'][0]['content'][0]['text'] ?? 'No answer returned.';
	}

	public function embed( string $text ) {
		if ( empty( $text ) ) {
			throw new RuntimeException( 'Please provide a valid text for embedding.' );
		}

		$payload = [
			'model' => 'text-embedding-3-small',
			'input' => $text,
		];

		$response = wp_remote_post( "https://api.openai.com/v1/embeddings", [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->api_key,
				'Content-Type'  => 'application/json',
			],
			'body'    => json_encode( $payload ),
			'timeout' => 30,
		] );

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'request_failed', $response->get_error_message() );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $body['data'][0]['embedding'] ) ) {
			return new WP_Error( 'no_embedding', 'No embedding returned from OpenAI.' );
		}

		return $body['data'][0]['embedding'];
	}
}
