<?php

namespace ChatAi\Repository;

use ChatAi\Contracts\Readable;
use ChatAi\Contracts\Storable;
use ChatAi\Traits\Storable as StorableTrait;

class PageRepository implements Readable, Storable {
	use StorableTrait;

	public function getAll( array $args = [] ): array {
		$args = wp_parse_args( $args, [
			'post_type'      => 'page',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
		] );

		return get_posts( $args );
	}
}
