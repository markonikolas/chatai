<?php

namespace ChatAi\Services;

use ChatAi\Repository\EmbeddingRepository;
use ChatAi\Repository\PageRepository;

readonly class EmbeddingService {

	public function __construct(
		private PageRepository $page_repository,
		private EmbeddingRepository $embedding_repository
	) {
	}

	public function create_column( string $column = 'embedding' ): void {
		$this->embedding_repository->create_column( table_name: 'wp_posts', column: $column );
	}

	public function create_embeddings(): void {
		$pages = $this->page_repository->get_raw_data();

		foreach ( $pages as $page ) {
			$clean = $this->clean_text( $page->post_content );
			$this->embedding_repository->update_clean_text( $page->ID, $clean );
		}
	}

	protected function clean_text( $html ): string {
		$html = apply_filters( 'the_content', $html );
		$html = preg_replace( '/<!--\s*\/?wp:[^>]*-->/', '', $html );
		$text = wp_strip_all_tags( $html );

		return trim( preg_replace( '/\s+/', ' ', html_entity_decode( $text, ENT_QUOTES, 'UTF-8' ) ) );
	}

	public function register_cron(): void {
		if ( ! wp_next_scheduled( 'chatai_cron_hook' ) ) {
			wp_schedule_event( time(), 'every_10_minutes', 'chatai_cron_hook' );
		}
	}

	public function unregister_cron(): void {
		wp_unschedule_event( time(), 'chatai_cron_hook' );
	}
}
