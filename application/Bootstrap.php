<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype()
	{
		$doctype = new Zend_View_Helper_Doctype(Zend_View_Helper_Doctype::XHTML1_STRICT);
	}
	
	protected function _initPokusCache()
	{	
		
		$cache = Zend_Cache::factory('Output', 'File',array('lifetime'=>'60','automatic_serialization'=>true),array('cache_dir'=>APPLICATION_PATH. '/cache/','cache_file_umask'=>0777));
		if(!$cache->start('pokus')) {
			echo 'Dobrý den ze Zendd Frameworku';
			$cache->end();
		}
		//$vysledek = $cache->call('getTime');
		//echo $vysledek['sec'];
		//$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		exit;
	}
	
}

