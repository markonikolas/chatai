<?php

namespace ChatAi\Services;

class SearchService {

	public function cosine_similarity( $vecA, $vecB ): float|int {
		$dotProduct = 0.0;
		$normA      = 0.0;
		$normB      = 0.0;

		foreach ( $vecA as $i => $val ) {
			$dotProduct += $val * $vecB[ $i ];
			$normA      += $val * $val;
			$normB      += $vecB[ $i ] * $vecB[ $i ];
		}

		if ( $normA == 0 || $normB == 0 ) {
			return 0;
		}

		return $dotProduct / ( sqrt( $normA ) * sqrt( $normB ) );
	}
}
