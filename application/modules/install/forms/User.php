<?php
use iCms\Formulare\FormAbstract;

class Install_Form_User extends FormAbstract
{	
	/**
	 * @type text
	 */
	public $username;
	public $usernameOptions = array(
			'label'=>'Uživatelské jméno:',
			'required' => 'true');
	
	/**
	 * @type text
	 */
	public $password;
	public $passwordOptions = array(
			'label'=>'Heslo:',
			'required' => 'true');
	
	/**
	 * @type text
	 */
	public $email;
	public $emailOptions = array(
			'label'=>'Email:');
	
	/** 
 	*@type submit 
 	*/
	public $sendButton;
	
	
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setAction($this->getView()->url(array('akce'=>'user'),'installCreate'));
		$this->setMethod('post');
		$this->addItems();
	}
	
}