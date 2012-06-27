<?php
namespace DoctrineTool\Template;

use DoctrineTool\Template\Feature\SetterInvokableInterface;
use DoctrineTool\Template\Utils;

/**
 * Virtual setter provider trait
 * 
 * @author Alexander Sergeychik
 * @version 0.1
 * @package DoctrineTool\Template
 */
trait SettersTrait implements SetterInvokableInterface {
	
	/**
	 * (non-PHPdoc)
	 * @see SetterInvokableInterface::__getPropertySetterName()
	 * @return string
	 */
	protected function __getPropertySetterName($property) {
		return self::INVOKABLE_SETTER_PREFIX . Utils::property2method($property);
	}

	/**
	 * (non-PHPdoc)
	 * @see SetterInvokableInterface::__invokePropertySetter()
	 * @throws InvalidPropertyNameException
	 * @throws ViolatedAccessPropertyException
	 * @return SettersTrait
	 */
	protected function __invokePropertySetter($property, $value) {
		
		if (!$property) {
			throw new InvalidPropertyNameException("Property name can't be empty string");
		} 
		if ($property{0}=='_') {
			throw new ViolatedAccessPropertyException("Property ($property) prefixed by '_' are protected and write access has been violated");
		}
		
		if ($this->__hasPropertySetter($property)) {
			return call_user_func(array($this, $this->__getPropertySetterName($property)), $value);
		} elseif (property_exists($this, $property)) {
			return $this->$property = $value;
		} else {
			throw new InvalidPropertyNameException("No property or setter with name <{$property}> exists in ".get_class($this));
		}
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see SetterInvokableInterface::__hasPropertySetter()
	 * @return boolean
	 */
	protected function __hasPropertySetter($property) {
		return method_exists($this, $this->__getPropertySetterName($property));
	}
	
	
}