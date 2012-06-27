<?php
namespace DoctrineTool\Template\Feature;

/**
 * Interface for virtual reading hidden/protected properties
 *
 * @author Alexander Sergeychik
 * @package DoctrineTool\Template
 * @version 0.5
 */
interface GetterInvokableInterface {

	/**
	 * Default prefix for invokable getters
	 * @var string
	 */
	const INVOKABLE_GETTER_PREFIX = 'get';

	/**
	 * Returns name of getter method for $property
	 *
	 * @param string $property
	 * @return string
	 */
	public function __getPropertyGetterName($property);
	
	/**
	 * Invokes getter method for some $property
	 * 
	 * @param string $property
	 * @return mixed
	 */
	public function __invokePropertyGetter($property);
	
	/**
	 * Checks for getter method existance according to $property
	 * 
	 * @param string $property
	 * @return boolean
	 */
	public function __hasPropertyGetter($property);
	
}