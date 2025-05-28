<?php

namespace ChatAi\Repository;

use PDO;

class Pages {

	public function __construct() { }

	public function get_all( string $db_path ): array {
		$pdo  = new PDO( 'sqlite:' . $db_path );
		$stmt = $pdo->query( 'SELECT id, heading, content, embedding FROM content WHERE embedding IS NOT NULL' );

		return $stmt->fetchAll( PDO::FETCH_ASSOC );
	}
}
