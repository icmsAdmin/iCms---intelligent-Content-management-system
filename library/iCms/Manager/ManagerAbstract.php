<?php
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package Manager
 * @author jara
 */
namespace iCms\Manager;

use iCms\Manager;
use iCms\BootstrapActionsHandler;

/**
 * Abstraktní třída, ze které musí dědit všechny modul Managery
 * 
 * @abstract
 * @category iCms
 * @package Manager
 * @uses BootstrapActionsHandler
 * @author jara
 */
abstract class ManagerAbstract implements BootstrapActionsHandler
{	
	/**
	 * Jméno třídy manageru
	 * 
	 * @access protected
	 * @var string
	 */
	protected $_name;
	
	/**
	 * Nastavení vztahující se k danému manageru
	 * 
	 * @access protected
	 * @var \Zend_Config
	 */
	protected $_options;
	
	/**
	 * Instance hlavního manageru
	 * 
	 * @acces protected
	 * @var Manager
	 */
	protected $_manager = null;
	
	/**
	 * Zajistí nemožnost instancovaní přes new
	 * 
	 * @access protected
	 * @return void
	 */
	protected function __construct()
	{}
	
	/**
	 * Vrací referenci na instanci hlavního manageru
	 * 
	 * @return Manager
	 */
	public function getManager()
	{
		if(null == $this->_manager)
			$this->_manager = Manager::getInstance();
		return $this->_manager;
	}
	
	/**
	 * Nahrává a vrací nastaveni specifické pro daný manager, aktuálně má každý 
	 * modul složku configs a v ní config.ini
	 * 
	 * @param bool $reload určuje, jestli nahrát i když už je nastavení nahrané,
	 * defaultně je false
	 * @return \Zend_Config
	 */
	public function getOptions($reload = false)
	{
		if(!$reload && $this->_options)
			return $this->_options;
		$this->_options = new \Zend_Config_Ini($this->getOptionsPath(),null,true);
		return $this->_options;
	}
	
	/**
	 * Vrací cestu ke configu modulu
	 * 
	 * @return string
	 */
	public function getOptionsPath()
	{
		return ICMS_PATH . '/' . $this->getShortName() . '/configs/config.ini'; 	
	}
	
	/**
	 * Vrací jméno manageru
	 * 
	 * @return string
	 */
	public function getName()
	{	
		if(!$this->_name)
			$this->_name = get_class($this);
		return $this->_name;
	}
	
	/**
	 * Vrací krátké jméno manageru s prefixem 'Manager' nebo bez,
	 * záleží na nastavení parametru
	 * 
	 * @param bool $withManagerPostfix
	 * @return string 
	 */
	public function getShortName($withManagerPostfix = false)
	{	
		$managerName = $this->getName();
		$shortName = strtok($managerName, '\\');
		if(!$shortName)
			return $withManagerPostfix ? $managerName : strstr($managerName, 'Manager',true);
		while($hlp = strtok('\\'))
			$shortName = $hlp;
		return $withManagerPostfix ? $shortName : strstr($shortName, 'Manager',true);
	}
	
	/**
	 * @see BootsrapActionsHandler
	 */
	public function preBootstrap()
	{}
	
	/**
	 * @see BootsrapActionsHandler
	 */
	public function postBootsrap()
	{}
}