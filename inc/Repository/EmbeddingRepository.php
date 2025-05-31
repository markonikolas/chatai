<?php

namespace ChatAi\Repository;

use ChatAi\Contracts\Storable;
use ChatAi\Traits\Storable as StorableTrait;
use Exception;

class EmbeddingRepository implements Storable {
	use StorableTrait;

	public static function cleanup(): void {
		global $wpdb;

		$table_name = $wpdb->prefix . 'posts';

		$column_names = [
			'embedding',
			'clean_text',
		];

		foreach ( $column_names as $column_name ) {
			$exists = $wpdb->get_results( "SHOW COLUMNS FROM `$table_name` LIKE '$column_name'" );

			if ( ! empty( $exists ) ) {
				$wpdb->query( "ALTER TABLE `$table_name` DROP COLUMN `$column_name`" );
			}
		}
	}

	public function createColumns( string $table_name ): void {
		try {
			if ( empty( $table_name ) ) {
				$table_name = $this->wpdb->prefix . 'posts';
			}

			/**
			 * TODO: refactor.
			 */
			if ( ! $this->check_colum_exists( table_name: $table_name, column_name: 'embedding' ) ) {
				$this->wpdb->query( "ALTER TABLE $table_name ADD COLUMN clean_text LONGTEXT NULL" );
			}

			if ( ! $this->check_colum_exists( table_name: $table_name, column_name: 'clean_text' ) ) {
				$this->wpdb->query( "ALTER TABLE $table_name ADD COLUMN clean_text LONGTEXT NULL" );
			}

			if ( ! $this->check_colum_exists( table_name: $table_name, column_name: 'clean_text_hash' ) ) {
				$this->wpdb->query( "ALTER TABLE $table_name ADD COLUMN clean_text_hash VARCHAR(32) NULL" );
			}
		} catch ( Exception $e ) {
			error_log( $e->getMessage(), 0, __CHATAI_PLUGIN_FILE__ . '/log/error.log' );
		}
	}

	private function check_colum_exists( string $table_name, string $column_name ): bool {
		$column = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SHOW COLUMNS FROM $table_name LIKE %s",
				$column_name
			)
		);

		return ! empty( $column );
	}

	public function updateCleanText( $post_id, $text, $hash ): void {
		global $wpdb;

		$wpdb->update(
			$wpdb->prefix . 'posts',
			[
				'clean_text' => $text,
				'clean_text_hash' => $hash,
			],
			[ 'ID' => $post_id ],
			[ '%s', '%s' ],
			[ '%d' ]
		);
	}

	public function getStoredHash( $post_id ): ?string {
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare(
			"SELECT clean_text_hash FROM {$wpdb->prefix}posts WHERE ID = %d",
			$post_id
		) );
	}

	public function getAll( array $args = [] ): array {
		// TODO: Get data from Embedding column for use in vector search.
		return [];
	}
}
