<?php
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package Manager
 * @author jara
 */
namespace iCms\Manager;

use iCms\BootstrapActionsHandler;

/**
 * Implementace ManagerBroker registru, umožnující různou práci a 
 * rozšířené možnosti managerům, které jsou zaregistrovány
 * 
 * @category iCms
 * @package Manager
 * @final
 * @author jara
 */
final class ManagerBroker implements BootstrapActionsHandler
{	
	/**
	 * singleton
	 * 
	 * @access private
	 * @static
	 * @var ManagerBroker
	 */
	private static $_managerBroker = null;
	
	/**
	 * Registr reprezentovaný polem, uchovávající instance 
	 * zaregistrovaných managerů
	 * 
	 * @access private
	 * @var array Pole instanci ManagerAbstract
	 */
	private $_registredManagers = array();
	
	/**
	 * Implementuje vzor singleton
	 * 
	 * @static
	 * @return ManagerBroker
	 */
	public static function getInstance()
	{	
		if(null == self::$_managerBroker)
			self::$_managerBroker = new self();
		return self::$_managerBroker;
	}
	
	/**
	 * Zaregistruje manager do registru
	 * 
	 * @param ManagerAbstract $manager
	 * @return void
	 */
	public function registerManager(ManagerAbstract $manager)
	{
		$this->_addManager($manager);
	}
	
	/**
	 * Odstraní manager z registru manager brokeru
	 * 
	 * @param ManagerAbstract $manager
	 * @return void
	 */
	public function unregisterManager(ManagerAbstract $manager)
	{
		$this->_dropManager($manager);
	}
	
	/**
	 * Vnitřní implementace odstranení zaregistrovaného manageru z 
	 * registru
	 * 
	 * @param ManagerAbstract $manager
	 * @return void
	 */
	private function _dropManager(ManagerAbstract $manager)
	{	
		$managerName = $manager->getName();
		if(array_key_exists($managerName, $this->_registredManagers))
			unset($this->_registredManagers[$managerName]);
	}
	
	/**
	 * Vnitřní implementace přidání manageru do registru
	 * 
	 * @param ManagerAbstract $manager
	 * @access private
	 * @return void
	 */
	private function _addManager(ManagerAbstract $manager)
	{	
		if(!$this->_isInRegister($manager))
			$this->_registredManagers[$manager->getName()] = $manager;	
	}
	
	/**
	 * Zjišťuje, jestli je daný manager už registrovaný
	 * 
	 * @param ManagerAbstract $manager
	 * @return bool
	 */
	public function isRegistred(ManagerAbstract $manager)
	{
		return $this->_isInRegister($manager);
	}
	
	/**
	 * Vnitřní implementace isRegistred(zjištění, jestli je
	 * manager už zaregistrován)
	 * 
	 * @param ManagerAbstract $manager
	 * @access private
	 * @return bool
	 */
	private function _isInRegister(ManagerAbstract $manager)
	{
		$managerClassName = get_class($manager);
		return array_key_exists($managerClassName, $this->_registredManagers);
	}
	
	/**
	 * Na každém zaregistrovaném manageru volá preBootstrap() 
	 * 
	 * @return void
	 */
	public function preBootstrap()
	{
		foreach($this->_registredManagers as $value)
			$value->preBootsrap();
	}
	
	/**
	 * Na každém zaregistrovaném manageru volá postBootstrap() 
	 * 
	 * @return void
	 */
	public function postBootsrap()
	{
		foreach($this->_registredManagers as $value)
			$value->postBootsrap();
	}
	
}