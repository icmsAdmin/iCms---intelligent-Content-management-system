<?php
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package Exception
 * @author jara
 */
namespace iCms;

/**
 * Hlavní třída sloužící pro vyhazování vyjímek v iCms
 * 
 * @category iCms
 * @package Exception
 * @uses \Exception
 * @author jara
 */
class Exception extends \Exception
{	
	/**
	 * Konstruktor
	 *
	 * @see \Exception
	 * @return void
	 */
	public function __construct($message = '', $code = 0, $previous = null)
	{	
		$msg = $this->_decorateMsg($message);
		parent::__construct($msg, $code, $previous);
	} 
	
	/**
	 * Přidává před tělo zprávy informaci, že se jedná o 
	 * vyjímku vyhozenou iCms systémem
	 * 
	 * @param string $message původní zpráva
	 * @return string vrací dekorovanou původní zprávu
	 */
	protected function _decorateMsg($message)
	{
		return 'iCms Error - ' . $message;
	}
}