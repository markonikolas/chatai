<?php

namespace ChatAi\Services;

use ChatAi\Repository\EmbeddingRepository;

readonly class EmbeddingService {

	public function __construct( private EmbeddingRepository $repository ) { }

	public function create_column(): void {
		$this->repository->create_column( table_name: 'wp_posts' );
	}
}
