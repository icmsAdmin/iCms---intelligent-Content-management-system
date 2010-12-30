<?php
namespace iCms\Databaze\Mappery;
use iCms\Databaze\Entity\EntityAbstract;

abstract class MapperAbstract
{
	private $_table;
	protected static $_tableLoader = null;
	
	public function __construct($table = null)
	{	
		if(null == self::$_tableLoader)
			self::$_tableLoader = new \Zend_Loader_PluginLoader(array('iCms\Databaze\Tabulky\\' => 'iCms/Databaze/Tabulky'));
		if(is_string($table))
			$this->_table = new $table;
		elseif ($table instanceof \Zend_Db_Table_Abstract)
			$this->_table = $table;
		else 
		{	
			$className = explode("\\",get_class($this));
			$className = array_pop($className);
			$className = explode('Mapper', $className);
			$className = self::$_tableLoader->load($className[0]);
			$this->_table = new $className();
		}
			
	}
	
	public function save(EntityAbstract $object)
	{
		if(!($object instanceof EntityAbstract))
			throw new \iCms\Exception('Špatný formát dat');
		$this->getTable()->insert(get_object_vars($object));
	}
	
	public function getTable()
	{
		return $this->_table;
	}
}