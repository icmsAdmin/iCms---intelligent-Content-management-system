<?php
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package Installation
 * @author jara
 */
namespace iCms\Installation;

use iCms\Manager\ManagerAbstract;

/**
 * Třída InstallationManager, sloužící k řízení instalace
 * a věcí s ní spojených
 * 
 * @category iCms
 * @package Installation
 * @final
 * @uses ManagerAbstract
 * @author jara
 */
final class InstallationManager extends ManagerAbstract
{	
	/**
	 * singleton
	 * 
	 * @access private
	 * @static
	 * @var InstallationManager
	 */
	private static  $_installationManager = null;
	
	/**
	 * konstruktor
	 * 
	 * @access protected
	 * @return void
	 */
	protected function __construct()
	{}
	
	/**
	 * Otestuje validitu instalace podle configu obsahujícího
	 * informace o probíhající/proběhlé instalaci
	 * 
	 * @return null|ArrayObject vrací null, když je vše ok
	 */
	public function isValid()
	{
		$options = $this->getOptions();
		$fm = $this->getTopManager()->getManager('File');
		if(!$fm->isExist($options->installation->installScript))
			$fm->makeCopy($this->getOptionsDirectory() . '/installation_default.ini',$options->installation->installScript);
		$config = new \Zend_Config_Ini($options->installation->installScript,'installation');
		$em = $this->getTopManager()->getManager('Error');
		if($result = $em->scanValidation($config->toArray()))
			return $result;
		else 
			return null;
	}
	
	public function installTables()
	{	
		$this->getManager('Db')->executeSqlFromFile($this->getOptions()->installation->tableScript);
	}
	
	public function installData()
	{
		$this->getManager('Db')->executeSqlFromFile($this->getOptions()->installation->dataScript);
	}
	
	/**
	 * Implementuje návrhový vzor singleton
	 * 
	 * @static
	 * @return InstallationManager
	 */
	public static function getInstance()
	{
		if(null === self::$_installationManager)
			self::$_installationManager = new self();
		return self::$_installationManager; 	
	}
	
}