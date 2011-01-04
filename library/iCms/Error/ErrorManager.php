<?php 
/**
 * iCms - intelligent Content management system
 * 
 * @category iCms
 * @package Error
 * @author jara
 */
namespace iCms\Error;

use iCms\Manager\ManagerAbstract;

/**
 * Error manager, slouží ke správě chyb v rámci iCms systému
 * 
 * @category iCms
 * @package Error
 * @final
 * @uses ManagerAbstract
 * @author jara
 */
final class ErrorManager extends ManagerAbstract
{	
	/**
	 * singleton
	 * 
	 * @access private
	 * @static
	 * @var ErrorManager
	 */
	private static $_errorManager;
	
	
	private $_lastScanResult = null;

	/**
	 * Implementuje vzor singleton
	 * 
	 * @static
	 * @return ErrorManager
	 */
	public static function getInstance()
	{	
		if(null == self::$_errorManager)
			self::$_errorManager = new self();
		return self::$_errorManager;
	}
	
	/**
	 * Ověří validitu na zadaném poli, ověřuje se stylem, že tam kde bude klíč roven
	 * false nebo null, tam se bude brát daná část jako nevalidní!
	 *
	 * @param array $info pole, kde chceme ověřit validitu
	 * @return \ArrayObject
	 */
	public function scanValidation(array $info)
	{	
		$validationInfo = new \ArrayObject();
		foreach($info as $key => $value) {
			if(is_array($value) && ($subArray = $this->scanValidation($value)))
				$validationInfo[$key] = $subArray; 
			elseif (null == $value || false == $value) 
				$validationInfo[$key] = 'invalid';			
		}
		
		if(count($validationInfo)) {
			$this->_lastScanResult = $validationInfo;
			return $validationInfo;
		}
		else {
			$this->_lastScanResult = null;
			return null;
		}
	}
	
	public function getLastScanResult()
	{
		return $this->_lastScanResult;
	}
	
	/**
	 * Převede pole získané voláním scanValidation na názvy příslišných 
	 * handlerů pro případnou opravu
	 * 
	 * @param \ArrayObject $errors
	 */
	public function convertToHandlers(\ArrayObject $errors)
	{	
		$handlers = array();
		$errors->setFlags(\ArrayObject::ARRAY_AS_PROPS);
		foreach($errors as $key => $value) {
			if(is_object($value)) 
				foreach($this->convertToHandlers($value) as $value) 
					$handlers[] = $key . $value;
			else 
				$handlers[] = $key;
		}
		return $handlers;
	}
}