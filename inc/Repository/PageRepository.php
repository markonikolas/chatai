<?php

namespace ChatAi\Repository;

use PDO;

class PageRepository {

	public function __construct() { }

	public function get_raw_data( array $args = [] ): array {
		if ( empty( $args ) ) {
			$args = [
				'post_type'      => 'page',
				'posts_per_page' => 10,
				'post_status'    => 'publish',
				'meta_query'     => [
					[
						'key'     => '_clean_text_processed',
						'compare' => 'NOT EXISTS',
					],
				],
			];
		}

		return get_posts( $args );
	}

	public function get_all( string $db_path ): array {
		$pdo  = new PDO( 'sqlite:' . $db_path );
		$stmt = $pdo->query( 'SELECT id, heading, content, embedding FROM content WHERE embedding IS NOT NULL' );

		return $stmt->fetchAll( PDO::FETCH_ASSOC );
	}
}
