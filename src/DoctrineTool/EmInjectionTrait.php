<?php
namespace DoctrineTool;

use Doctrine\ORM\EntityManager;

/**
 * Sets Doctrine2 entity manager to controllers/models etc.
 * 
 * @author Alexander Sergeychik
 */
trait EmInjectionTrait {
	
	/**
	 * Default entity manager name
	 * @var string
	 */
	const DEFAULT_ENTITYMANAGER_NAME = 'orm_default';
	
	/**
	 * Entity Manager instances pool
	 * @var array
	 */
	protected $_entityManagers = array();
	
	/**
	 * Returns entity manager specified by name
	 * 
	 * @param string $name - name of EM instance
	 * @return EntityManager
	 */
	public function getEntityManager($name = self::DEFAULT_ENTITYMANAGER_NAME) {
		
		if (empty($name)) {
			throw new Exception("Entity Manager alias name should be set");
		} elseif (isset($this->_entityManagers[$name])) {
			throw new Exception("Entity Manager '{$name}' is not set");
		}
		
		return $this;
	}
	
	/**
	 * Sets entity manager by it's name.
	 * Optional name defaults by self::DEFAULT_ENTITYMANAGER_NAME
	 * 
	 * @param EntityManager $em
	 * @param string $name
	 * @return EntityManager
	 */
	public function setEntityManager(EntityManager $em, $name = self::DEFAULT_ENTITYMANAGER_NAME) {
		$this->entityManagers[$name] = $em;
		return $this;
	}
	
}
