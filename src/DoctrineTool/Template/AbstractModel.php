<?php
namespace BusinessDomain\Model\Common;

/**
 * Generic abstract model for all business models
 * 
 * @author Alexander Sergeychik
 */
abstract class AbstractModel implements GettersInterface, SettersInterface, AccessibleInterface, ImportableInterface {
	
	const GETTER_PREFIX = 'get';
	const SETTER_PREFIX = 'set';
	
	/**
	 * Cached map of already calculated property getter/setter names
	 * @var unknown_type
	 */
	static protected $_propery2method_map_cache = array();
	
	/**
	 * Returns property value
	 * (non-PHPdoc)
	 * @see BusinessDomain\Common.AccessibleInterface::__get()
	 */
	public function __get($property) {
		if (!$property) {
			throw new InvalidPropertyNameException("Property name can't be empty string");
		} elseif ($this->__hasGetter($property)) {
			return $this->__invokeGetter($property);	
		} elseif ($property{0}=='_') {
			throw new ViolatedAccessPropertyException("Property ($property) prefixed by '_' are protected and read access has been violated without getter");
		} elseif (property_exists($this, $property)) {
			return $this->$property;
		} else {
			throw new InvalidPropertyNameException("No such property with name ({$property}) in ".get_class($this));
		}
	}
	
	/**
	 * Sets property value
	 * (non-PHPdoc)
	 * @see BusinessDomain\Common.AccessibleInterface::__set()
	 */
	public function __set($property, $value) {
		if (!$property) {
			throw new InvalidPropertyNameException("Property name can't be empty string");
		} elseif ($this->__hasSetter($property)) {
			return $this->__invokeSetter($property, $value);
		} elseif ($property{0}=='_') {
			throw new ViolatedAccessPropertyException("Property ($property) prefixed by '_' are protected and write access has been violated withot setter");
		} elseif (property_exists($this, $property)) {
			return $this->$property = $value;
		} else {
			throw new InvalidPropertyNameException("No such property with name ({$property}) in ".get_class($this));
		}
	}
	
	/**
	 * Checks property for presense in model.
	 * NB. Functionality restricted and throws exception. Added for copability
	 * (non-PHPdoc)
	 * @see BusinessDomain\Common.AccessibleInterface::__isset()
	 */
	public function __isset($property) {
		throw new Exception("Property presencse check is not avalable for domain models");
	}
	
	/**
	 * Removes property from model.
	 * NB. Functionality restricted and throws exception. Added for copability
	 * (non-PHPdoc)
	 * @see BusinessDomain\Common.AccessibleInterface::__unset()
	 */
	public function __unset($property) {
		throw new Exception("Unsetting properties is not avalable for domain models");
	}
	
	/**
	 * ArrayAccess offset getting proxy method
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetGet()
	 */
	public function offsetGet($offset) {
		return $this->__get($offset);
	}
	
	/**
	 * ArrayAccess offset setting proxy method
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetGet()
	 */
	public function offsetSet($offset, $value) {
		return $this->__get($offset, $value);
	}
	
	/**
	 * ArrayAccess offset presence check proxy method
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetGet()
	 */
	public function offsetExists($offset) {
		return $this->__isset($offset);
	}
	
	/**
	 * ArrayAccess offset remove proxy method
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetGet()
	 */
	public function offsetUnset($offset) {
		return $this->__unset($offset);
	}
	
	/**
	 * Imports data to record
	 * @param \Traversable | array $data
	 * @return AbstractModel
	 */
	public function import($data) {
		foreach ($data as $name=>$value) {
			$this->__set($name, $value);
		}
		return $this;
	}
	
	/**
	 * Invokes getter for property $property
	 * (non-PHPdoc)
	 * @see BusinessDomain\Common.GettersInterface::__invokeGetter()
	 */
	public function __invokeGetter($property) {
		try {
			$method_name = self::GETTER_PREFIX . self::property2method($property);
			return call_user_func(array($this, self::GETTER_PREFIX . self::property2method($property)));
		} catch (Exception $e) {
			throw new Exception("Unable to call getter {$method_name}() for property {$property}",null,$e);
		}
	}
	
	/**
	 * Checks if property getter exists 
	 * (non-PHPdoc)
	 * @see BusinessDomain\Common.GettersInterface::__hasGetter()
	 */
	public function __hasGetter($property) {
		return method_exists($this, self::GETTER_PREFIX . self::property2method($property));
	}
	
	/**
	 * Invokes setter for property $property with $value as argument
	 * (non-PHPdoc)
	 * @see BusinessDomain\Common.GettersInterface::__invokeGetter()
	 */
	public function __invokeSetter($property, $value) {
		try {
			$method_name = self::SETTER_PREFIX . self::property2method($property);
			return call_user_func(array($this, self::SETTER_PREFIX . self::property2method($property)),$value);
		} catch (Exception $e) {
			throw new Exception("Unable to call setter {$method_name}() for property {$property}",null,$e);
		}
	}
	
	/**
	 * Checks if property setter exists 
	 * (non-PHPdoc)
	 * @see BusinessDomain\Common.SettersInterface::__hasSetter()
	 */
	public function __hasSetter($property) {
		return method_exists($this, self::SETTER_PREFIX . self::property2method($property));
	}
	
	/**
	 * Converts name 'my_nameSpace' to camel case definition 'MyNameSpace'
	 * Also caches already managed properties
	 * @TODO refactor for faster execution
	 * @param string $name
	 * @return string
	 */
	protected static function property2method($property_name) {
		if (isset(self::$_propery2method_map_cache[$property_name])) {
			return self::$_propery2method_map_cache[$property_name];
		}
		$parts = explode('_', $property_name);
		$parts = array_filter($parts);
		$parts = array_map('ucfirst', $parts);
		$method_name = implode('', $parts);
		self::$_propery2method_map_cache[$property_name] = $method_name;
		return $method_name;
	}
	
	public function toArray()
	{
		return get_object_vars($this);
	}
}