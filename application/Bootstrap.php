<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype()
	{
		$doctype = new Zend_View_Helper_Doctype(Zend_View_Helper_Doctype::XHTML1_STRICT);
	}

	protected function _initIcms()
	{
		iCms\Manager::getInstance()->addToZendRegistry();
		Zend_Registry::get('manager')->getManager('Installation')->getInstance()->isValid();
	}
	
}

