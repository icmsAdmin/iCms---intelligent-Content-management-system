<?php
use iCms\Formulare\FormAbstract;

class Install_Form_Db extends FormAbstract
{		
	/** 
 	*@type text 
 	*/
	public $host;
	public $hostOptions = array(
		'required'=>true,
		'label'=>'Hostitel(např. localhost):');
	
	/** 
	*@type text 
	*/
	public  $dbname;
	public $dbnameOptions = array(
		'required'=>true,
		'label'=>'Jméno databáze:');
	
		/** 
 	*@type text 
 	*/
	public $username;
	public $usernameOptions = array(
		'required'=>true,
		'label'=>'Uživatelské jméno mysql:');
	
	/** 
 	*@type text 
 	*/
	public $password;
	public  $passwordOptions = array(
		'label'=>'Heslo k mysql:');
	
	/** 
 	*@type submit 
 	*/
	public $sendButton;
	
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setAction($this->getView()->url(array('akce'=>'db'),'installCreate'));
		$this->setMethod('post');
		$this->addItems();
	}
}