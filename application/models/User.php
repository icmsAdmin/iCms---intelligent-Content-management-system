<?php
/**
 *  @Entity
 *  @table(name="users")
 */
class Application_Model_User
{	
	/**
	 * @id @column(type="integer")
	 * @generatedValue
	 */
	private $id;
	
	/**
	 * @column(length=16)
	 */
	private $password;
	
	/**
	 * @column(length=16, unique=true)
	 */
	private $username;
	
	/**
	 * @manyToOne(targetEntity="Application_Model_Role")
	 * @joinColumn(name="role_id", referencedColumnName="id")
	 */
	private $role;
	
	public function getRole() {
		return $this->role;
	}

	public function setRole($role) {
		$this->role = $role;
	}

	public function getId() {
		return $this->id;
	}

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

}