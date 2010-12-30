<?php
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package File
 * @author jara
 */
namespace iCms\File;

use iCms\Manager\ManagerAbstract;

/**
 * File manager, sloužící k řízení správy souboru, adresářů 
 * a věcí s tím spojených
 * 
 * @category iCms
 * @package File
 * @final
 * @uses ManagerAbstract
 * @author jara
 */
final class FileManager extends ManagerAbstract
{	
	/**
	 * singleton
	 * 
	 * @access private
	 * @static
	 * @var FileManager
	 */
	private static $_fileManager;
	
	/**
	 * Implementuje vzor singleton
	 * 
	 * @static
	 * @return FileManager
	 */
	public static function getInstance()
	{	
		if(null == self::$_fileManager)
			self::$_fileManager = new self();
		return self::$_fileManager;
	}
	
	/**
	 * Nastavuje práva adresáři nebo souboru
	 * 
	 * @param string $filename
	 * @param octal $unixCode
	 * @return void
	 */
	public function setFileAccess($filename, $unixCode)
	{
		chmod($filename, $unixCode);
	}
	
	/**
	 * Vytváří soubor, pokuď ještě neexistuje a nastavuje mu 
	 * případně práva
	 * 
	 * @param string $filename
	 * @param bool $allHaveAll default true, určuje, jestli se mají nastavit 
	 * práva tak, aby měl každý full přístup
	 * @return void
	 */
	public function createFileIfNotExist($filename,$allHaveAll = true)
	{	
		if(file_exists($filename))
			return;
		$file = fopen($filename, 'a+');
		if($allHaveAll)
			$this->setFileAccess($filename, 0777);
		fclose($file);
	}
	
	/**
	 * Vrací obsah souboru jako řetězec
	 * 
	 * @param string $filename
	 * @return string
	 */
	public function getFileContent($filename)
	{	
		return file_get_contents($filename);
	}
}