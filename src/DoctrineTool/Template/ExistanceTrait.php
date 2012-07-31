<?php
namespace DoctrineTool\Template;

/**
 * Full virtual property access trait.
 * 
 * @author Alexander Sergeychik
 * @version 0.1-dev
 * @package DoctrineTool\Template
 */
trait ExistanceTrait {
	
	/**
	 * Checks for property existance using default public property access and Getter/Setter traits check. 
	 * 
	 * @todo ToBeTested
	 * @param string $property
	 * @return boolean
	 */
	public function __isset($property) {
		
		$reflection = new \ReflectionObject($this);
		
		// reflect to public property
		$byProperty = $reflection->hasProperty($property) ?  $reflection->getProperty($property)->isPublic() : false;
		
		
		$used_traits = $reflectionObject->getTraitNames();
		
		// reflect to GettersTrait
		$byGetter = in_array(__NAMESPACE__.'\GettersTrait', $used_traits)? call_user_func(array($this, '__hasPropertyGetter'), $property): false;
		
		// reflect to SettersTrait
		$bySetter = in_array(__NAMESPACE__.'\SettersTrait', $used_traits)? call_user_func(array($this, '__hasPropertySetter'), $property): false;
		
		
		return $byProperty || $byGetter || $bySetter;
	}
	
	
}