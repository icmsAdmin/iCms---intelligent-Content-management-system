<?php
namespace iCms\Databaze\Entity;

abstract class EntityAbstract 
{	
	protected $_values;
	
	public function __construct($values)
	{
		if(is_array($values))
		{	
			foreach($values as $key => $value)
				if(method_exists(get_class($this), 'set' . $key))
				{	
					$method = 'set' . ucfirst($key);
					$this->$method($value);
				}				
		}
		else 
			throw new \iCms\Exception('Špatné nastavení pro vytvoření entity');
	}
}
