<?php
namespace DoctrineTool\Template;

/**
 * Full virtual property access trait.
 * 
 * @author Alexander Sergeychik
 * @version 0.1
 * @package DoctrineTool\Template
 */
trait AccessibleTrait {
	
	use GettersTrait, SettersTrait, ExistanceTrait;
	
}