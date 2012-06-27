<?php
namespace DoctrineTool\Template;

/**
 * Utils used in templates
 * 
 * @author Alexander Sergeychik
 */
class Utils {
	
	static public $property2method_cache = array();
	
	/**
	 * Translates property name to method name
	 * @todo think about perfomance grow
	 * 
	 * CHANGELOG:
	 * - added property cache
	 * - property cache set as public to be accessible from external cache tools
	 * 
	 * @param string $property
	 * @return string
	 */
	static public function property2method($property) {
		
		if (isset(self::$property2method_cache[$property])) return self::$property2method_cache[$property];
		
		$parts = explode('_',$property);
		$parts = array_map('ucfirst', $parts);
		$method = implode($parts);
		
		self::$property2method_cache[$property] = $method;
		return $method;
	}
	
	
}