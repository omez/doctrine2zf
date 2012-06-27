<?php
namespace DoctrineTool\Template;

use DoctrineTool\Template\Feature\GetterInvokableInterface;
use DoctrineTool\Template\Utils;

/**
 * Virtual getter provider trait
 * 
 * @author Alexander Sergeychik
 * @version 0.1
 * @package DoctrineTool\Template
 */
trait GettersTrait implements GetterInvokableInterface {
	
	/**
	 * (non-PHPdoc)
	 * @see GetterInvokableInterface::__getPropertyGetterName()
	 * @return string
	 */
	protected function __getPropertyGetterName($property) {
		return self::INVOKABLE_GETTER_PREFIX . Utils::property2method($property);
	}

	/**
	 * (non-PHPdoc)
	 * @see GetterInvokableInterface::__invokePropertyGetter()
	 * @throws InvalidPropertyNameException
	 * @throws ViolatedAccessPropertyException
	 * @return mixed
	 */
	protected function __invokePropertyGetter($property) {
		
		if (!$property) {
			throw new InvalidPropertyNameException("Property name can't be empty string");
		} 
		if ($property{0}=='_') {
			throw new ViolatedAccessPropertyException("Property ($property) prefixed by '_' are protected and read access has been violated");
		}
		
		if ($this->__hasPropertyGetter($property)) {
			return call_user_func(array($this, $this->__getPropertyGetterName($property)));
		} elseif (property_exists($this, $property)) {
			return $this->$property;
		} else {
			throw new InvalidPropertyNameException("No property or getter with name <{$property}> exists in ".get_class($this));
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see GetterInvokableInterface::__hasPropertyGetter()
	 * @return boolean
	 */
	protected function __hasPropertyGetter($property) {
		return method_exists($this, $this->__getPropertyGetterName($property));
	}
	
	
}