<?php
namespace iCms\Ui\forms;

abstract class FormAbstract extends \Zend_Form
{
	public function addItems()
	{	
		
		$reflexe = new \Zend_Reflection_Class($this);
		$properties = $reflexe->getProperties(\ReflectionProperty::IS_PUBLIC);
		foreach ($properties as $property)
		if(($docBlock = $property->getDocComment()) && ($docBlock->hasTag('type')))
		{	
			$type = $docBlock->getTag('type')->getDescription();
			$propName = $property->name;
			$element = '\Zend_Form_Element_' . ucfirst(trim($type));
			$elemOptions = $propName . 'Options';
			property_exists($this,$elemOptions) ? $this->$propName = new $element($propName,$this->$elemOptions)
									   : $this->$propName = new $element($propName); 
			$this->addElement($this->$propName);		
		}	
		
	}
}