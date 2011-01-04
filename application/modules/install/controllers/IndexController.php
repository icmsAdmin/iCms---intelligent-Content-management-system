<?php
use iCms\Manager;

class Install_IndexController extends Zend_Controller_Action
{	
	protected $_form = '';
	
	protected $_dbManager;
	
	public function init()
	{
		$this->_dbManager = Manager::getInstance()->getManager('Db');
	}
	
	public function indexAction()
	{}
	
	public function dbAction()
	{
		$this->_form = new Install_Form_Db();
		if($this->getRequest()->isPost() && $this->_form->isValid($_POST)) {
			$db = iCms\Manager::getInstance()->getManager('Db');
			$db->setConOptions($this->_form->getValues());
			$db->getManager('installation')->installTables();
			$db->getManager('installation')->installData();
			$this->_helper->redirector('user');
		}	
	}
	
	public function userAction()
	{	
		$this->_form = new Install_Form_User();
		if($this->getRequest()->isPost() && $this->_form->isValid($_POST)) {
			$em = $this->_dbManager->getDoctrineEntityManager();
			$admin = new Application_Model_User();
			$admin->setUsername($this->_form->getValue('username'));
			$admin->setPassword($this->_form->getValue('password'));
			$admin->setRole($em->find('Application_Model_Role',1));
			$em->persist($admin);
			$em->flush();														
		}		
	}
	
	public function postDispatch()
	{
		$this->view->form = $this->_form;
	}
}