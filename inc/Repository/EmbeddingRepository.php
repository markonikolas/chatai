<?php

namespace ChatAi\Repository;

use Exception;
use wpdb;

readonly class EmbeddingRepository {

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
}
