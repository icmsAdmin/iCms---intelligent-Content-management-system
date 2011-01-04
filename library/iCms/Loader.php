<?php 
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package Loader
 * @author jara
 */
namespace iCms;

/**
 * Hlavní Loader iCms systému, který nahrává třídy z iCms namespace 
 * a je injectován do Zend_Loader_Autoloader
 * 
 * @category iCms
 * @package Loader
 * @uses \Zend_Loader_Autoloader_Interface
 * @author jara
 */
class Loader implements \Zend_Loader_Autoloader_Interface
{	
	/**
	 * Funkce sloužící pro jako callback pro nahrátí tříd iCms
	 * 
	 * @param string $class název požadované třídy pro nahrátí
	 * @see \Zend_Loader_Autoloader_Interface::autoload()
	 * @return void
	 */
	public function autoload($class)
	{	
		$className = str_replace('\\', '/', $class) . '.php';
		require_once ($className);
	}
}