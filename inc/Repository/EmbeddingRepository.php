<?php

namespace ChatAi\Repository;

use ChatAi\Contracts\Storable;
use Exception;
use wpdb;

readonly class EmbeddingRepository implements Storable {

	public function __construct( private wpdb $wpdb ) { }

	public function create_column( string $table_name, string $column = 'embedding' ): void {
		try {
			if ( empty( $table_name ) ) {
				$table_name = $this->wpdb->prefix . 'posts';
			}

			$exists = $this->check_colum_exists( table_name: $table_name, column: $column );

			if ( empty( $exists ) ) {
				$this->wpdb->query( "ALTER TABLE `$table_name` ADD `$column` LONGTEXT NULL" );
			}
		} catch ( Exception $e ) {
			error_log( $e->getMessage(), 0, __CHATAI_PLUGIN_FILE__ . '/log/error.log' );
		}
	}

	private function check_colum_exists( string $table_name, string $column ): array|object {
		return $this->wpdb->get_results( "SHOW COLUMNS FROM `$table_name` LIKE '$column'" );
	}

	public function update_clean_text( $post_id, $text ): void {
		global $wpdb;

		$wpdb->update(
			$wpdb->prefix . 'posts',
			[ 'clean_text' => $text ],
			[ 'ID' => $post_id ],
			[ '%s' ],
			[ '%d' ]
		);

		update_post_meta( $post_id, '_clean_text_processed', 1 );
	}

	public function get_all( array $args = [] ): array {
		// TODO: Get data from Embedding column for use in vector search.
		return [];
	}
}
