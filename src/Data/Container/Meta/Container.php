<?php

/**
 * This trait provides meta data container functionality.
 */
declare (strict_types=1);

namespace Maleficarum\Data\Container\Meta;

trait Container {
	/**
	 * Internal storage for container meta data.
	 *
	 * @var array
	 */
	protected $_meta = [];

	/* ------------------------------------ Magic methods START ---------------------------------------- */
	
	/**
	 * Fetch value from container meta.
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get(string $name) {
		return array_key_exists($name, $this->_meta) ? $this->_meta[$name] : null;
	}

	/**
	 * Add new value to model meta.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function __set(string $name, $value) {
		$this->_meta[$name] = $value;
	}
	
	/* ------------------------------------ Magic methods END ------------------------------------------ */
	
	/* ------------------------------------ Class Methods START ---------------------------------------- */

	/**
	 * Clear all container data.
	 * 
	 * @return \Maleficarum\Data\Container\Meta\Container
	 */
	public function clearMeta() {
		$this->_meta = [];
		return $this;
	}


	/**
	 * Replace current meta data.
	 * 
	 * @param array $meta
	 * @return \Maleficarum\Data\Container\Meta\Container
	 */
	public function setMeta(array $meta) {
		$this->_meta = $meta;
		return $this;
	}


	/**
	 * Fetch current meta data.
	 * 
	 * @return array
	 */
	public function getMeta() {
		return $this->_meta;
	}
	
	/* ------------------------------------ Class Methods END ------------------------------------------ */
}