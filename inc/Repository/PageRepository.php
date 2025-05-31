<?php

namespace ChatAi\Repository;

use ChatAi\Contracts\Storable;

class PageRepository implements Storable {

	public function __construct() { }

	public function get_all( array $args = [] ): array {
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
}
