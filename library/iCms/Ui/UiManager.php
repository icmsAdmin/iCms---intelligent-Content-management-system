<?php
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package Ui
 * @author jara
 */
namespace iCms\Ui;

use iCms\Manager\ManagerAbstract;

/**
 * Manager Databáze, sloužící k řízení operací nad db
 * a věcí s ní spojených
 * 
 * @category iCms
 * @package Ui
 * @final
 * @uses ManagerAbstract
 * @author jara
 */
final class UiManager extends ManagerAbstract
{	
	/**
	 * loader pro formy
	 * 
	 * @access private
	 * @var \Zend_Loader_PluginLoader
	 */
	private $_formLoader = null;
	
	/**
	 * Vrací instanci požadovaného formu
	 * 
	 * @param string $formName
	 * @return mixed
	 */
	public function createForm($formName)
	{
		$className =  $this->getFormLoader()->load($formName);
		return new $className();
	}
	
	/**
	 * Vrací instanci Zend_Loader_PluginLoader
	 * 
	 * @uses \Zend_Loader_PluginLoader
	 * @return string 
	 */
	public function getFormLoader()
	{	
		if(null == $this->_formLoader) {
			$this->_formsLoader = new \Zend_Loader_PluginLoader();
			$this->_formsLoader->addPrefixPath('\\iCms\\Ui\\forms\\', ICMS_PATH . 'Ui/forms');	
		}
		return $this->_formLoader;
	}
}