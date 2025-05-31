<?php

namespace ChatAi\Controllers;

use ChatAi\Services\EmbeddingService;

readonly class EmbeddingController {

	public function __construct( private EmbeddingService $embedding_service ) {
	}

	public function create_column(): void {
		$this->embedding_service->create_column();
		$this->embedding_service->create_column( 'clean_text' );
	}

	public function create_embeddings(): void {
		$this->embedding_service->create_embeddings();
	}

	public function register_cron(): void {
		$this->embedding_service->register_cron();
	}

	public function unregister_cron(): void {
		$this->embedding_service->unregister_cron();
	}
}
