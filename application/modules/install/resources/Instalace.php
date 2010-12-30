<?php 
use iCms\Instalace\Instalace;

class Install_Resource_Instalace extends Zend_Application_Resource_ResourceAbstract
{
	public function init()
	{	
		defined('MODULE_INSTALL') || define('MODULE_INSTALL', MODULE_PATH . '/install');
		new Zend_Application_Module_Autoloader(array(
				'namespace' => 'Install',
				'basePath' => MODULE_INSTALL));
		Zend_Controller_Front::getInstance()->registerPlugin(new Install_Plugin_InstallValidator(),1);
		$router = Zend_Controller_Front::getInstance()->getRouter();
		$router->addRoute('allToInstall',new Zend_Controller_Router_Route('*',array(
									'module' => 'install',
									'controller' => 'index',
									'action' => 'index')));
		$router->addRoute('installCreate',new Zend_Controller_Router_Route('install/:controller/:action',array(
										'module'=>'install')));
		Zend_Registry::set('instalace', Instalace::getInstance());
		return Instalace::getInstance();	
	}
}