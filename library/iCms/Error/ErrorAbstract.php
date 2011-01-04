<?php
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package Error
 * @author jara
 */
namespace iCms\Error;

/**
 * Abstraktní třída, ze které musí dědit všechny iCms Error třídy
 * 
 * @abstract
 * @category iCms
 * @package Error
 * @author jara
 */
abstract class ErrorAbstract
{	
	/**
	 * jméno error objektu
     *
	 * @var string
	 */
	protected $_name;
	
	/**
	 * text error zprávy
     *
	 * @var string
	 */
	protected $_message;
	
	/**
	 * Nastaví error message
	 * 
	 * @param string $message
	 */
	protected function setErrorMessage($message)
	{
		$this->message = $message;
	}
	
	/**
	 * Vrátí error message
	 * 
	 * @return string
	 */
	protected function getErrorMessage()
	{
		return $this->_message;
	}
	
}