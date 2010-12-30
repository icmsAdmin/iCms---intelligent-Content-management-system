<?php
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package Manager
 * @author jara
 */
namespace iCms;

/**
 * Rozhraní, které implementují třídy, které chtějí reagovat
 * na akce související s bootstrapováním hlavního bootsrapu
 * 
 * @category iCms
 * @package Manager
 * @author jara
 */
interface BootstrapActionsHandler
{	
	/**
	 * Vykonává se těsně před bootstrapem Zend_Application
	 */
	public function preBootstrap();
	
	/**
	 * Vykonává se těsně po bootstrapu Zend_Application
	 */
	public function postBootsrap();
}