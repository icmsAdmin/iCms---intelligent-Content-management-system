<?php
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package Db
 * @author jara
 */
namespace iCms\Db;

use iCms\Manager\ManagerAbstract;

/**
 * Manager Databáze, sloužící k řízení operací nad db
 * a věcí s ní spojených
 * 
 * @category iCms
 * @package Db
 * @final
 * @uses ManagerAbstract
 * @author jara
 */
final class DbManager extends ManagerAbstract
{	
	/**
	 * singleton
	 * 
	 * @access private
	 * @static
	 * @var DbManager
	 */
	private static $_dbManager = null;
	
	/**
	 * instance db adapteru 
	 * 
	 * @access private
	 * @var \Zend_Db_Adapter_Abstract
	 */
	private $_dbAdapter;
	
	/**
	 * Implementuje vzor singleton
	 * 
	 * @static
	 * @param array|Zend_Config $conOptions údaje pro připojení k databázi
	 * @return DbManager
	 */
	public static function getInstance()
	{	
		if(null == self::$_dbManager)
			self::$_dbManager = new self();
		return self::$_dbManager;
	}
	
	/**
	 * Vrací instanci Db adapteru
	 * 
	 * @return \Zend_Db_Adapter_Abstract
	 */
	public function getDbAdapter()
	{	
		return $this->_dbAdapter;
	}
	
	/**
	 * Instancuje Db adapteru, je na developerovi, aby zajistil včasné instancování
	 * adaptéru, aby nedošlo k problémum při používání Db manageru
	 * 
	 * @param array|Zend_Config $options
	 * @param string $adapterType defaultně pdo_mysql
	 * @param bool $setDefault jestli nastavit do Zend_Db_Table jako default adapter
	 * @return \Zend_Db_Adapter_Abstract
	 */
	public function createDbAdapter($conOptions, $adapterType = 'pdo_mysql', $setDefault = true)
	{
		if($this->_dbAdapter)
			return $this->_dbAdapter;
		$adapter = \Zend_Db::factory($adapterType, $conOptions);
		$this->_dbAdapter = $adapter;
		if($setDefault)
			\Zend_Db_Table::setDefaultAdapter($adapter);
		return $adapter;
	}
	
	/**
	 * Otestuje připojení na adaptéru
	 * 
	 * @return void
	 */
	public function checkConnection()
	{
		$this->getDbAdapter()->getConnection();	
	}
	
	/**
	 * Provede sql příkazy ze souboru, ale nic nevrací, 
	 * tzn. se hodí jen na některé příkazy
	 * 
	 * @param string filename
	 * @return void
	 */
	public function executeSqlFromFile($filename)
	{
		$fileContent = $this->getManager()->getManager('File')->getFileContent($filename);
		$sql = strtok($fileContent, ';');
		if($sql) {
			$adapter = $this->getDbAdapter();
			$adapter->query($sql);
			while($sql = strtok(';'))
				$adapter->query($sql);
		}
				
	}
}