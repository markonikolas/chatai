<?php

namespace ChatAi\Services;

use ChatAi\Repository\Pages as PagesRepository;

class Pages {

	public function __construct(
		protected PagesRepository $repository,
		protected Search $search_service
	) {
	}

	public function get_top_results( $query_embedding, $db_path, $limit = 3 ): string {
		$rows = $this->repository->get_all( $db_path );

		foreach ( $rows as $row ) {
			$embedding = json_decode( $row['embedding'], true );

			if ( ! is_array( $embedding ) ) {
				continue;
			}

			$similarity = $this->search_service->cosine_similarity( $query_embedding, $embedding );

			$scored[] = [
				'id'         => $row['id'],
				'heading'    => $row['heading'],
				'content'    => $row['content'],
				'similarity' => $similarity,
			];
		}

		usort( $scored, function ( $a, $b ) {
			return $b['similarity'] <=> $a['similarity'];
		} );

		$top = array_slice( $scored, 0, $limit );

		$context = array_map( function ( $r ) {
			return "Section: {$r['heading']}\n{$r['content']}\n";
		}, $top );

		return implode( "\n---\n", $context );
	}
}
