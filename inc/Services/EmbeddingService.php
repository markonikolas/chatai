<?php

namespace ChatAi\Services;

use ChatAi\Repository\EmbeddingRepository;
use ChatAi\Repository\PageRepository;

readonly class EmbeddingService {
	public function __construct(
		private PageRepository $pageRepository,
		private EmbeddingRepository $embeddingRepository
	) {
	}

	public function create_column( string $column = 'embedding' ): void {
		$this->embeddingRepository->create_column( table_name: 'wp_posts', column: $column );
	}

	public function create_embeddings(): void {
		$pages = $this->pageRepository->get_raw_data();

		foreach ( $pages as $page ) {
			$clean = $this->clean_text( $page->post_content );
			$this->embeddingRepository->update_clean_text( $page->ID, $clean );
		}
	}

	protected function clean_text( $html ): string {
		$html = apply_filters( 'the_content', $html );
		$html = preg_replace( '/<!--\s*\/?wp:[^>]*-->/', '', $html );
		$text = wp_strip_all_tags( $html );

		return trim( preg_replace( '/\s+/', ' ', html_entity_decode( $text, ENT_QUOTES, 'UTF-8' ) ) );
	}

	public function register_cron(): void {
		if ( ! wp_next_scheduled( 'chatai_create_embeddings' ) ) {
			wp_schedule_event( time(), 'hourly', 'chatai_create_embeddings' );
		}
	}

	public function unregister_cron(): void {
		wp_unschedule_event( time(), 'chatai_create_embeddings' );
	}
}
