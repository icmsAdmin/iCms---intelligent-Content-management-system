<?php
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package Manager
 * @author jara
 */
namespace iCms;

use iCms;
use iCms\Manager\ManagerAbstract;
use iCms\Manager\ManagerBroker;

/**
 * Hlavní třída Manager, sloužící k řízení celého systému
 * 
 * @category iCms
 * @final
 * @package Manager
 * @author jara
 */
final class Manager
{	
	/**
	 * singleton
	 * 
	 * @access private
	 * @var iCms\Manager
	 */
	private static $_manager = null;
	
	/**
	 * Reference na hlavní bootstrap aplikace
	 * 
	 * @access private
	 * @var \Zend_Application_Bootstrap_Bootstrap
	 */
	private $_bootstrap;
	
	/**
	 * Instance správce managerů
	 * 
	 * @access private
	 * @var ManagerBroker
	 */
	private $_managerBroker = NULL;
	
	/**
	 * Identifikuje, jestli uz byla instance Zend_Application bootstrapnuta
	 * 
	 * @access private
	 * @var bool
	 */
	private $_isBootstrap = false;
	
	/**
	 * Cesta k hlavnímu configuračnímu souboru 
	 * 
	 * @access private
	 * @var string
	 */
	private $_appConfigPath;
	
	/**
	 * Cesta ke configuračnímu souboru icms systému
	 * 
	 * @access private
	 * @var string
	 */
	private $_icmsConfigPath;
	
	/**
	 * Loader iCms tříd
	 * 
	 * @access private
	 * @var iCms\Loader
	 */
	private $_icmsAutoLoader;
	
	/**
	 * Config iCms systému
	 * 
	 * @access private
	 * @var \Zend_Config_Ini
	 */
	private $_icmsOptions;
	
	/**
	 * Instanace Zend_Application
	 * 
	 * @access private
	 * @var \Zend_Application
	 */
	private $_application;
	
	/**
	 * Vrací cestu ke configuračnímu souboru icms systému
	 * 
	 * @return string
	 */
	public function getIcmsConfigPath() 
	{
		return $this->_icmsConfigPath;
	}
	
	/**
	 * Vrací, jestli už byla instance Zend_Application bootstrapnuta
	 * 
	 * @return bool
	 */
	public function isBootstrap()
	{
		return $this->_isBootstrap;
	}

	/**
	 * Nastavuje cestu ke configuračnímu souboru icms systému
	 * 
	 * @param string $icmsConfigPath 
	 * @return void
	 */
	public function setIcmsConfigPath($icmsConfigPath) 
	{
		$this->_icmsConfigPath = $icmsConfigPath;
	}

	/**
	 * Instancuje a registruje autoloader pro třídy iCms systému
	 * Nastavuje cestu k hlavnímu configu aplikace 
	 * 
	 * @access private
	 * @return void
	 */
	private function __construct()
	{	
		//potřeba registrovat autoloader jako prvni !
		$this->registerAutoloading();
		$this->getManagerBroker();
		$this->setAppConfigPath(APPLICATION_PATH . '/configs/application.ini');
		$this->setIcmsConfigPath(APPLICATION_PATH . '/configs/icmsconfig.ini');
		$this->getApplication();
	}
	
	/**
	 * Pokuď neexistuje, tak instancuje objekt třídy Manager 
	 * a vrátí jeho instanci
	 * 
	 * @static
	 * @return iCms\Manager
	 */
	public static function getInstance()
	{	
		if(null == self::$_manager)
			self::$_manager = new self();
		return self::$_manager;
	}
	
	/**
	 * Přidá referencí na instanci Manager do Zend_Registry 
	 * pod klíčem 'manager'
	 * 
	 * @return void
	 */
	public function addToZendRegistry()
	{	
		require_once 'Zend/Registry.php';
		\Zend_Registry::set('manager', $this->getInstance());
	}
	
	/**
	 * Zaregistruje Loader pro automatické loadování tříd
	 * iCms systému do hlavního Loaderu Zend Frameworku
	 * 
	 * @return void
	 */
	public function registerAutoloading()
	{	
		require_once 'Zend/Loader/Autoloader.php';
		\Zend_Loader_Autoloader::getInstance()->unshiftAutoloader($this->getAutoloader(),'iCms');
	}
	
	/**
	 * Umožňuje přidat options do Zend_Application instance
	 * 
	 * @param bool $useIcmsConfig jestli použít options z icms configu
	 * @param \Zend_Config_Ini|array|string $anotherOptions další options
	 * @return void
	 */
	public function appendOptionsToApp($useIcmsConfig = false, $anotherOptions = null)
	{	
		$application = $this->getApplication();
		if($useIcmsConfig)
			$application->setOptions($this->getIcmsOptions()->toArray());
		if($anotherOptions instanceof \Zend_Config_Ini)
			$application->setOptions($anotherOptions->toArray());
		elseif(is_array($anotherOptions))
			$application->setOptions($anotherOptions);
		//TODO - dodělat ještě větev pro $anotherOptions = string, tzn. např .ini soubor		
	}
	
	/**
	 * Bootstrapuje Zend_Application instanci a nastavuje 
	 * na instanci Manageru referenci na hlavní bootstrap
	 * 
	 * @return \Zend_Application
	 */
	public function bootstrapApp()
	{	
		$application = $this->getApplication();
		if(!$this->isBootstrap()) {
			//tady se volá preBootstrap na všech zaregistrovaných managerech
			$this->getManagerBroker()->preBootstrap();
			$application->bootstrap();
			$this->_isBootstrap = true;
			//tady se volá postBootstrap na všech zaregistrovaných managerech
			$this->getManagerBroker()->postBootsrap();
			$this->setBootstrap($application->getBootstrap());
		}
		return $application;
	}
	
	/**
	 * Spouští Zend_Application
	 * 
	 * @return void
	 */
	public function run()
	{	
		$this->bootstrapApp()->run();
	}
	
	/**
	 * Vrací Zend_Config_Ini objekt, reprezentující iCms configurační soubor
	 * 
	 * @return \Zend_Config_Ini
	 */
	public function getIcmsOptions()
	{	
		if(null == $this->_icmsOptions)
			$this->_icmsOptions = new \Zend_Config_Ini($this->getIcmsConfigPath(), APPLICATION_ENV ,false);
		return $this->_icmsOptions;
	}
	
	/** 
	 * Vraci referenci na hlavní bootstrap instanci applikace 
	 * 
	 * @return \Zend_Application_Bootstrap_Bootstrap
	 */
	public function getBootstrap()
	{	
		return $this->_bootstrap;	
	}
	
	/**
	 * Nastavuje referenci na hlavní bootstrap instanci
	 * 
	 * @access protected
	 * @param \Zend_Application_Bootstrap_Bootstrap $bootstrap
	 */
	protected  function setBootstrap(\Zend_Application_Bootstrap_Bootstrap $bootstrap)
	{
		$this->_bootstrap = $bootstrap;
	}
	
	/**
	 * Vrací instanci Zend_Application, pokuď není vytvořená, tak ji 
	 * instancuje a defaultně předává jako options application.ini
	 * 
	 * @return \Zend_Application
	 */
	public function getApplication()
	{
		if(null == $this->_application)
			$this->_application = new \Zend_Application(APPLICATION_ENV,$this->getAppConfigPath());
		return $this->_application;			
	}
	
	/**
	 * Vrací cestu k hlavnímu application configu
	 * 
	 * @return string
	 */
	public function getAppConfigPath()
	{
		return $this->_appConfigPath;
	}
	
	/**
	 * Nastavuje cestu k hlavnímu config souboru aplikace
	 * 
	 * @param string $appConfigPath
	 * @return void
	 */
	public function setAppConfigPath($appConfigPath)
	{
		$this->_appConfigPath = $appConfigPath;
	}
	
	/**
	 * Vrací referenci na instanci autoloaderu pro
	 * třídy iCms systému
	 * 
	 * @return iCms\Loader
	 */
	public function getAutoloader()
	{	
		require_once 'iCms/Loader.php';
		if(null === $this->_icmsAutoLoader)
			$this->_icmsAutoLoader = new Loader();
		return $this->_icmsAutoLoader;
	}
	
	/**
	 * Vrací instanci hlavního loaderu Zend Frameworku
	 * 
	 * @return \Zend_Loader_Autoloader
	 */
	public function getZfLoader()
	{	
		return \Zend_Loader_Autoloader::getInstance();
	}
	
	/**
	 * Vrací referenci na Front Controller
	 * 
	 * @return \Zend_Controller_Front
	 */
	public function getFrontController()
	{
		return \Zend_Controller_Front::getInstance();
	}
	
	/**
	 * Zaregistruje instanci pluginu na front controlleru
	 * 
	 * @param \Zend_Controller_Plugin_Abstract $plugin instance pluginu
	 * @param integer $stackIndex pozice ve stacku (čím menší, tím dřív se spouští)
	 * @return void
	 */
	public function registerFcPlugin(\Zend_Controller_Plugin_Abstract $plugin, $stackIndex = null)
	{	
		$this->getFrontController()->registerPlugin($plugin, $stackIndex);
	}
	
	/**
	 * Slouží k získání instnace libovolného z manažerů iCms systému,
	 * je předpokládána určitá hierarchie složek v iCms library
	 * 
	 * @param string $name jméno managera
	 * @return mixed vrací instanci požadovaného manažera
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
	 * Zaregistruje daný manager na brokeru
	 * 
	 * @return void
	 */
	public function register($manager)
	{
		if($manager instanceof ManagerAbstract)
			$this->getManagerBroker()->registerManager($manager);
	}
	
	/**
	 * Odstraní daný manager z brokeru
	 * 
	 * @return void
	 */
	public function unregister($manager)
	{
		if($manager instanceof ManagerAbstract)
			$this->getManagerBroker()->unregisterManager($manager);
	}
	
	/**
	 * Vrací instanci manager brokeru
	 * 
	 * @return ManagerBroker
	 */
	public function getManagerBroker()
	{
		if(null == $this->_managerBroker)
			$this->_managerBroker = ManagerBroker::getInstance();
		return $this->_managerBroker;
	}
}