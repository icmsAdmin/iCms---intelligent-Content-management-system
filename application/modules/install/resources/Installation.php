<?php 
use iCms\Instalace\Instalace;

class Install_Resource_Installation extends Zend_Application_Resource_ResourceAbstract
{
	public function init()
	{	
		defined('MODULE_INSTALL') || define('MODULE_INSTALL', MODULE_PATH . '/install');
		new Zend_Application_Module_Autoloader(array(
				'namespace' => 'Install',
				'basePath' => MODULE_INSTALL));
		$this->getBootstrap()->bootstrap('frontController');
		$front = $this->getBootstrap()->getResource('frontController');
		$front->registerPlugin(new Install_Plugin_InstallValidator(),1);
		$router = $front->getRouter();
		$router->addRoute("install",new Zend_Controller_Router_Route('install/:action',array(
			'module'=>'install',
			'controller'=>'index')));
	}
}