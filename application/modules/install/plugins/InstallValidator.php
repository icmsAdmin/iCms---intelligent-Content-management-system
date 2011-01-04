<?php

use iCms\Installation\InstallationManager;

class Install_Plugin_InstallValidator extends Zend_Controller_Plugin_Abstract
{	
	public function dispatchLoopStartup($request)
	{	
		$im = InstallationManager::getInstance();
		if((null != $im->isValid()) && ($request->getModuleName() != 'install')) {
			$request->setModuleName('install')
					->setControllerName('index')
					->setActionName('index');
		} 
	}
}