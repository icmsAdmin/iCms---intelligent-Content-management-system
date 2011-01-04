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
	 * Zkopíruje daný soubor
	 * 
	 * @param string $source
	 * @param strin $destination
	 */
	public function makeCopy($source, $destination)
	{	
		copy($source, $destination);
	}
	
	/**
	 * Zjistí, jestli daný adresář nebo soubor existuje
	 * 
	 * @param string $filename
	 * @return bool
	 */
	public function isExist($filename)
	{
		return file_exists($filename);
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
	 * Smaže daný soubor
	 * 
	 * @param string $filename
	 * @return bool jestli to vyšlo
	 */
	public function deleteFile($filename)
	{
		return unlink($filename);
	}
	
	/**
	 * Přejmenuje adresář nebo soubor
	 * 
	 * @param string $filename
	 * @param string $newName
	 * @return bool jestli to vyšlo
	 */
	public function rename($filename, $newName)
	{
		return rename($filename, $newName);
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
	 * Vrací instanci Zend_Config_Ini
	 * 
	 * @param string $filename
	 * @return \Zend_Config_Ini
	 */
	public function openConfig($filename)
	{
		return new \Zend_Config_Ini($filename,null, true);
	}
	
	/**
	 * Vrací obsah souboru jako řetězec
	 * 
	 * @param string $filename
	 * @return string
	 */
	public function getFileContent($filename)
	{	
		return ($content = file_get_contents($filename)) ? $content : die("Nepodařilo se otevřít soubor");
	}
}