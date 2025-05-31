<?php

namespace ChatAi\Contracts;

interface Storable {

	public function get_all( array $args = [] ): array;
}
