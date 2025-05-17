<?php

namespace ChatAI\Bootstrap;

use ChatAI\Contracts\ContainerInterface;

use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use Exception;

class Container implements ContainerInterface {

	protected array $instances = [];

	public function set(string $id, callable $resolver): void {
		$this->instances[$id] = $resolver;
	}

	/**
	 * Throws if it can't automatically resolve
	 *
	 * @throws Exception
	 */
	public function get(string $id) {
		if (isset($this->instances[$id])) {
			return $this->instances[$id]($this);
		}

		return $this->auto_resolve($id);
	}

	/**
	 * Throws on:
	 *  - Missing class
	 *  - Not instantiable class
	 * @throws ReflectionException
	 * @throws Exception
	 */
	protected function auto_resolve(string $id) {
		if (!class_exists($id)) {
			throw new Exception("Class $id does not exist.");
		}

		$reflection = new ReflectionClass($id);

		if (!$reflection->isInstantiable()) {
			throw new Exception("Class $id is not instantiable.");
		}

		$constructor = $reflection->getConstructor();

		if (!$constructor) {
			return new $id;
		}

		$params = $constructor->getParameters();
		$dependencies = array_map(fn(ReflectionParameter $param) =>
		$this->get($param->getType()->getName()), $params
		);

		return $reflection->newInstanceArgs($dependencies);
	}
}
