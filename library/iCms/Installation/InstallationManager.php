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
	 * @return void
	 */
	public function isValid()
	{
		$options = $this->getOptions();
		$this->getManager()->getManager('File')->createFileIfNotExist($options->installation->installScript);
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
	
	/**
	 * Zahájí instalaci databáze - tzn. tabulek i dat
	 */
	public function installDb()
	{
		$this->installDbTables();
		$this->installDbData();	
	}
	
	/**
	 * Zahájí instalaci tabulek do existující databáze
	 */
	public function installDbTables()
	{
		$this->getManager()->getManager('File')->executeSqlFromFile(ICMS_PATH . '/Installation/sql/tables.sql');							
	}
	
	/**
	 * Zahájí instalaci dat do existující databáze
	 */
	public function installDbData()
	{
		$this->getManager()->getManager('File')->executeSqlFromFile(ICMS_PATH . '/Installation/sql/data.sql');
	}
}