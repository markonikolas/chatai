<?php

namespace ChatAi\Controllers;

use ChatAi\Services\EmbeddingService;

readonly class EmbeddingController {

	public function __construct( private EmbeddingService $embeddingService ) {
	}

	public function create_column(): void {
		$this->embeddingService->create_column();
		$this->embeddingService->create_column( 'clean_text' );
	}

	public function create_embeddings(): void {
		$this->embeddingService->create_embeddings();
	}

	public function register_cron(): void {
		$this->embeddingService->register_cron();
	}

	public function unregister_cron(): void {
		$this->embeddingService->unregister_cron();
	}
}
