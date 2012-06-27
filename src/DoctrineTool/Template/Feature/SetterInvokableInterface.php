<?php
namespace DoctrineTool\Template\Feature;

/**
 * Interface for virtual writing hidden/protected properties
 *
 * @author Alexander Sergeychik
 * @package DoctrineTool\Template
 * @version 0.5
 */
interface SetterInvokableInterface {
	
	/**
	 * Default prefix for invokable setters
	 * @var string
	 */
	const INVOKABLE_SETTER_PREFIX = 'set';
	
	/**
	 * Returns name of setter method for $property
	 *
	 * @param string $property
	 * @return string
	 */
	public function __getPropertySetterName($property);
	
	/**
	 * Invokes setter method for some $property
	 *
	 * @param string $property
	 * @return void
	 */
	public function __invokePropertySetter($property, $value);
	
	/**
	 * Checks for setter method existance according to $property
	 *
	 * @param string $property
	 * @return boolean
	 */
	public function __hasPropertySetter($property);

}