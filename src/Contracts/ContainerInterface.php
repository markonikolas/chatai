<?php

namespace ChatAI\Contracts;

interface ContainerInterface {
	public function get(string $id);
	public function set(string $id, callable $resolver): void;
}
