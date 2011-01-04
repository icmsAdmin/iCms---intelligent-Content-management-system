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
	 * @var \iCms\Manager
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
	 * @return \iCms\Manager
	 */
	public function getTopManager()
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
		$this->_options = $this->getManager('File')->openConfig($this->getOptionsPath());
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
	 * Vrací cestu k adresáři s configy pro daný modul
	 * 
	 * @return string
	 */
	public function getOptionsDirectory()
	{
		return ICMS_PATH . '/' . $this->getShortName() . '/configs'; 	
	}
	
	/**
	 * Vrací jméno celé manageru
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
	 * TODO
	 * 
	 * @return \iCms\Error\ErrorAbstract
	 */
	public function getErrorClass()
	{
		$className = strstr(get_class($this),'Manager',true) . 'Error';
		return new $className();
	}
	
	/**
	 * Vrací cestu k manageru
	 * 
	 * @return string
	 */
	public function getPath()
	{
		return ICMS_PATH . '/' . $this->getShortName();
	}
	
	public function callHandler($name,$params = null)
	{
		$className = 'iCms\\' . $this->getShortName(false) . '\\' . $this->getShortName() . 'Handlers';
		$handlers = $className::getInstance();
		$handlers->$name($params); 
	}
	
	/**
	 * Slouží k získání instnace libovolného z manažerů iCms systému,
	 * je předpokládána určitá hierarchie složek v iCms library
	 * 
	 * @param string $name jméno managera
	 * @return \iCms\Manager\ManagerAbstract vrací instanci požadovaného manažera
	 */
	public function getManager($name = null)
	{
		if(!$name)
			return $this->getInstance();
		($name = strtolower($name)) && $name = ucfirst($name);
		$className = 'iCms\\' . $name . '\\' . $name . 'Manager';
		return $className::getInstance();	
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