<?php
class Install_CreateController extends Zend_Controller_Action
{	
	protected $_instalace;
	protected $_fm;
	protected $_form;
	
	public function init()
	{
		$this->_instalace = Zend_Registry::get('instalace');
		$this->_fm = $this->_helper->getHelper('flashMessenger');	
	}
	
	public function dbAction()
	{
		$this->_form = new Install_Form_Db();
		if(($this->getRequest()->isPost()) &&
		   ($this->_form->isValid($_POST)) 	   &&
		   ($this->_instalace->installDb($this->_form->getValues())))
		   {	
		   		$this->_fm->addMessage('Databáze úspěšně vytvořena');
				$redirector = $this->_helper->getHelper('Redirector');	
				$redirector->gotoSimpleAndExit('user');
		   }   		
	}
	
	public function userAction()
	{			
				if($this->_fm->hasMessages()) {
					$msg = $this->_fm->getMessages();
					$this->view->message = $msg[0];
				}
				
				$this->_form = new Install_Form_User();
				
				if($this->getRequest()->isPost() && 
				   $this->_form->isValid($_POST)) {
				     	
				}
						
	}
	
	public function postDispatch()
	{
		$this->view->form = $this->_form;
	}
}