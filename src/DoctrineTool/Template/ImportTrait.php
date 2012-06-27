<?php
namespace DoctrineTool\Template;

use DoctrineTool\Template\Feature\ImportableInterface;
use \InvalidArgumentException;

/**
 * Implements importable interface.
 * 
 * @todo implement import conflicts strategies for array2array/scalar2array/array2scalar situations
 * 
 * @author Alexander Sergeychik
 * @package DoctrineTool\Template
 * @version 0.1-dev
 */
trait ImportTrait implements ImportableInterface {
	
	/**
	 * (non-PHPdoc)
	 * @see ImportableInterface::import()
	 * @return ImportTrait
	 */
	public function import($data) {
		
		if (!is_array() || !$data instanceof \Traversable) {
			throw new InvalidArgumentException('Import data is not an array or Traversable');
		}
		
		foreach ($data as $name=>$data) {
			if (!isset($this->$name)) continue;
			
			if (is_array($data) || $data instanceof \Traversable && $this->$name instanceof ImportableInterface) {
				$this->$name->import($data);
			} else {
				$this->$name = $data;
			}
		}
		
		return $this;
	}
	
}