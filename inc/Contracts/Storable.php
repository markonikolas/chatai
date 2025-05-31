<?php

namespace ChatAi\Contracts;

interface Storable {

	public function getAll( array $args = [] ): array;
}
