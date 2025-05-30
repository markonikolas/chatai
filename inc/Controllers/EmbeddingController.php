<?php

namespace ChatAi\Controllers;

use ChatAi\Services\EmbeddingService;

readonly class EmbeddingController {

	public function __construct( private EmbeddingService $embedding_service ) { }

	public function create_column(): void {
		$this->embedding_service->create_column();
	}
}
