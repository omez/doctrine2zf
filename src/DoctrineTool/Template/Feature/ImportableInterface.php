<?php
namespace DoctrineTool\Template\Feature;
use Traversable;

/**
 * Interface for importing data to some model/class
 *
 * @author Alexander Sergeychik
 * @package DoctrineTool\Template
 * @version 0.4
 */
interface ImportableInterface {
	
	/**
	 * Imports data into class/model
	 * 
	 * @param array|Traversable $data
	 */
	public function import($data);
	
}