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

	/**
	 * Needs to be called statically to trigger
	 * the register_uninstall_hook.
	 *
	 * @see register_uninstall_hook()
	 */
	public static function cleanup(): void {
		EmbeddingRepository::cleanup();
	}

	public function createColumns(): void {
		$this->embeddingRepository->createColumns( table_name: 'wp_posts' );
	}

	public function createEmbeddings(): void {
		$pages = $this->pageRepository->getAll();

		foreach ( $pages as $page ) {
			$clean_text  = $this->cleanText( $page->post_content );
			$new_hash    = md5( $clean_text );
			$stored_hash = $this->embeddingRepository->getStoredHash( $page->ID );

			if ( $new_hash !== $stored_hash ) {
				$this->embeddingRepository->updateCleanText( $page->ID, $clean_text, $new_hash );
			}
		}
	}

	protected function cleanText( $html ): string {
		$html = apply_filters( 'the_content', $html );
		$html = preg_replace( '/<!--\s*\/?wp:[^>]*-->/', '', $html );
		$text = wp_strip_all_tags( $html );

		return trim( preg_replace( '/\s+/', ' ', html_entity_decode( $text, ENT_QUOTES, 'UTF-8' ) ) );
	}

	public function registerEvent(): void {
		if ( ! wp_next_scheduled( 'chatai_create_embeddings' ) ) {
			wp_schedule_event( time(), 'hourly', 'chatai_create_embeddings' );
		}
	}

	public function unregisterEvent(): void {
		wp_clear_scheduled_hook( 'chatai_create_embeddings' );
	}
}
