<?php

/**
 * This trait provides error data container functionality.
 */
declare (strict_types=1);

namespace Maleficarum\Data\Container\Error;

trait Container {
	
	/* ------------------------------------ Class Property START --------------------------------------- */
	
	/**
	 * Internal storage container for error values.
	 *
	 * @var array
	 */
	protected $errors = ['__default_error_namespace__' => []];

	/* ------------------------------------ Class Property END ----------------------------------------- */
	
	/* ------------------------------------ Class Methods START ---------------------------------------- */
	
	/**
	 * Clear all errors assigned to this object.
	 *
	 * @return \Maleficarum\Data\Container\Error\Container
	 */
	public function clearErrors() {
		$this->errors = ['__default_error_namespace__' => []];

		return $this;
	}

	/**
	 * Merge input errors into this container.
	 *
	 * @param array $errors
	 * @return \Maleficarum\Data\Container\Error\Container
	 * @throws \InvalidArgumentException
	 */
	public function mergeErrors(array $errors) {
		if (!array_key_exists('__default_error_namespace__', $errors)) throw new \InvalidArgumentException(sprintf('Incorrect error set provided. \%s::mergeErrors()', static::class));

		foreach ($errors as $namespace => $errs) {
			// skip incorrect namespace entries
			if (!is_array($errs) || !count($errs)) continue;

			foreach ($errs as $err) {
				// skip incorrect error entries
				if (!is_array($err)) continue;
				if (!array_key_exists('code', $err) || !preg_match('/^\d{4}\-\d{6}$/', $err['code'])) continue;
				if (!array_key_exists('msg', $err) || !is_string($err['msg'])) continue;
				
				// add valid errors into this container
				$this->addError($err['code'], $err['msg'], $namespace, false);
			}
		}

		return $this;
	}

	/**
	 * Fetch all errors attached to this object and stored in the specified namespace.
	 *
	 * @param string $namespace
	 * @return array
	 */
	public function getErrors(string $namespace = '__default_error_namespace__') : array {
		if (array_key_exists($namespace, $this->errors)) {
			return $this->errors[$namespace];
		}

		return [];
	}

	/**
	 * Import an outside set of errors into this container.
	 *
	 * @param array $errors
	 * @return \Maleficarum\Data\Container\Error\Container
	 * @throws \InvalidArgumentException
	 */
	public function setErrors(array $errors) {
		if (!array_key_exists('__default_error_namespace__', $errors)) throw new \InvalidArgumentException(sprintf('Incorrect error set provided. \%s::setErrors()', get_class($this)));

		$this->errors = $errors;

		return $this;
	}

	/**
	 * Fetch all errors attached to this object, regardless of their namespaces.
	 *
	 * @return array
	 */
	public function getAllErrors() : array {
		return $this->errors;
	}

	/**
	 * Check if there are any errors attached to the specified namespace for this object.
	 *
	 * @param string $namespace
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function hasErrors(string $namespace = '__default_error_namespace__') : bool {
		if (array_key_exists($namespace, $this->errors)) {
			return (bool)count($this->errors[$namespace]);
		}

		return false;
	}

	/**
	 * Attach a new error to this object. Errors attached to non default namespaces will be duplicated in the default
	 * namespace unless the $duplicateToDefault param is set to false.
	 *
	 * @param string $message
	 * @param string $code
	 * @param string $namespace
	 * @param bool $duplicateToDefault
	 * @return \Maleficarum\Data\Container\Error\Container
	 * @throws \InvalidArgumentException
	 */
	public function addError(string $code, string $message, string $namespace = '__default_error_namespace__', bool $duplicateToDefault = true) {
		if (!preg_match('/^\d{4}\-\d{6}$/', $code)) throw new \InvalidArgumentException(sprintf('Incorrect error code format. \%s::addError()', static::class));
		
		$error = [
			'msg' => $message,
			'code' => $code
		];

		array_key_exists($namespace, $this->errors) or $this->errors[$namespace] = [];
		$this->errors[$namespace][$error['code']] = $error;

		if ($duplicateToDefault && $namespace !== '__default_error_namespace__') {
			array_key_exists('__default_error_namespace__', $this->errors) or $this->errors['__default_error_namespace__'] = [];
			$this->errors['__default_error_namespace__'][$error['code']] = $error;
		}

		return $this;
	}
	
	/* ------------------------------------ Class Methods END ------------------------------------------ */
}
