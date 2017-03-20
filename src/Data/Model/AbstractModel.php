<?php

/**
 * This class sets up basic model functionality common to all maleficarum models.
 */
declare (strict_types=1);

namespace Maleficarum\Data\Model;

abstract class AbstractModel implements \JsonSerializable {
	
	/* ------------------------------------ Class Traits START ----------------------------------------- */

	/**
	 * \Maleficarum\Data\Container\Error\Container
	 */
	use \Maleficarum\Data\Container\Error\Container;
	
	/**
	 * \Maleficarum\Data\Container\Meta\Container
	 */
	use \Maleficarum\Data\Container\Meta\Container;
	
	/* ------------------------------------ Class Traits END ------------------------------------------- */
	
	/* ------------------------------------ Class Methods START ---------------------------------------- */
	
	/**
	 * Merge provided data into this object.
	 *
	 * @param array $data
	 * @return \Maleficarum\Data\Model\AbstractModel
	 */
	public function merge(array $data) : \Maleficarum\Data\Model\AbstractModel {
		// attempt to merge as much input data as possible
		foreach ($data as $key => $val) {
			$methodName = 'set' . str_replace(' ', '', ucwords($key));
			method_exists($this, $methodName) and $this->$methodName($val);
		}

		// merge meta if any is available
		array_key_exists('_meta', $data) && is_array($data['_meta']) and $this->setMeta($data['_meta']);

		// conclude
		return $this;
	}

	/**
	 * Restore the model object to its most basic state - without any data.
	 *
	 * @return \Maleficarum\Data\Model\AbstractModel
	 */
	public function clear() : \Maleficarum\Data\Model\AbstractModel {
		// recover all model properties from Reflection
		$properties = (new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PRIVATE);

		// clear all properties that have setters
		foreach ($properties as $val) {
			$methodName = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $val->name)));
			method_exists($this, $methodName) and $this->$methodName(null);
		}

		// clear meta
		$this->setMeta([]);

		return $this;
	}

	/**
	 * Fetch a logic-less DTO structure based on current model data.
	 *
	 * @param array $skipProperties
	 * @return array
	 */
	public function getDTO(array $skipProperties = []) : array {
		// initialize result storage
		$result = [];

		// fetch all private properties from Reflection (only private properties are part of actual model data)
		$properties = (new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PRIVATE);

		// include all model data in the result (with the exception of specified "skip" properties)
		foreach ($properties as $val) {
			if (in_array($val->name, $skipProperties)) continue;

			$methodName = 'get' . str_replace(' ', "", ucwords($val->name));
			method_exists($this, $methodName) and $result[$val->name] = $this->$methodName();
		}

		// add meta to DTO (only if it is not empty)
		is_array($this->getMeta()) && count($this->getMeta()) and $result = array_merge($result, ['_meta' => $this->getMeta()]);

		// conclude
		return $result;
	}
	
	/* ------------------------------------ Class Methods END ------------------------------------------ */

	/* ------------------------------------ JsonSerializable methods START ----------------------------- */
	
	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @see \JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize() {
		return $this->getDTO();
	}
	
	/* ------------------------------------ JsonSerializable methods END ------------------------------- */

	/* ------------------------------------ Abstract methods START ------------------------------------- */
	
	/**
	 * Set a unique ID for this object.
	 *
	 * @param mixed $id
	 * @return \Maleficarum\Data\Model\AbstractModel
	 */
	abstract public function setId($id) : \Maleficarum\Data\Model\AbstractModel;

	/**
	 * Fetch the currently assigned unique ID.
	 *
	 * @return mixed
	 */
	abstract public function getId();
	
	/* ------------------------------------ Abstract methods END --------------------------------------- */
}
