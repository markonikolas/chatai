<?php

namespace ChatAi\Traits;

use wpdb;

trait Storable {
	private wpdb $wpdb;

	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
	}
}
