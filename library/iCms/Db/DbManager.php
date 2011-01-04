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
	private $_dbAdapter = null;
	
	/**
	 * instance Doctrine 2 entity manageru;
	 * 
	 * @var \Doctrine\ORM\EntityManager
	 * @access private
	 */
	private $_doctrineEntityManager = null;
	
	/**
	 * Nahrává options ze svého config.ini
	 * 
	 * @access protected
	 * @return void
	 */
	protected function __construct()
	{
		$this->getOptions();
	}
	
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
		if(null == $this->_dbAdapter) {
			$db = new \Zend_Application_Resource_Db($this->getOptions()->zend_db->toArray());
			$this->_dbAdapter = $db->init();
		}
		return $this->_dbAdapter;
	}
	
	
	/**
	 * Inicializuje Doctrine2 framework, doporučuje se volat
	 * spíše getDoctrineEntityManager
	 * 
	 * @return void
	 */
	public function initDoctrine2()
	{	
		require_once 'iCms/Db/Doctrine.php';
		if(null != $this->_doctrineEntityManager)
			return;
		$doctrineOptions = $this->getOptions()->toArray();
		$doctrineOptions = $doctrineOptions['doctrine'];
		$doctrine = new \Doctrine();
		$this->_doctrineEntityManager = $doctrine->init($doctrineOptions);			
	}
	
	public function setConOptions(array $conOptions)
	{
		$options = $this->getOptions();
		foreach($conOptions as $key => $value) {
			$options->doctrine->connection->$key = $value;
			if('user' == $key) {
				$options->zend_db->params->username = $value;
				continue;
			}
			$options->zend_db->params->$key = $value;
		}
	}
	
	/**
	 * Vrací instanci doctrine 2 entity manageru
	 * 
	 * @return \Doctrine\ORM\EntityManager
	 */
	public function getDoctrineEntityManager()
	{	
		$this->initDoctrine2();
		return $this->_doctrineEntityManager;
	}
	
	/**
	 * Inicializuje Zend_Db komponentu
	 * 
	 * @return void
	 */
	public function initZendDb()
	{
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
			while(trim(($sql = strtok(';'))))
				$adapter->query($sql);
		}
				
	}
	
	public function __destruct()
	{
		$writer = new \Zend_Config_Writer_Ini();
		$writer->write($this->getOptionsPath(),$this->getOptions());
	}
}