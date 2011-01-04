<?php 
namespace iCms\Installation;


class InstallationHandlers 
{	
	
	public function tableValidErrorHandler()
	{
		$this->getManager()->getManager('Db')->executeSqlFromFile(ICMS_PATH . '/Installation/sql/tables.sql');
	}
	
	public function dataValidErrorHandler()
	{
		$this->getManager()->getManager('Db')->executeSqlFromFile(ICMS_PATH . '/Installation/sql/data.sql');		
	}
}