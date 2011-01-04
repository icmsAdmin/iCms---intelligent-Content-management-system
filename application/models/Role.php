<?php

/**
 * @Entity
 * @table (name="role")
 */
class Application_Model_Role 
{	
	/**
	 * @id @column(type="integer")
	 * @generatedValue
	 */
	private $id;
	
	/**
	 * @column(type="string", length=50)
	 */
	private $nazev;
	
	public function getId() {
		return $this->id;
	}
	
	public function getNazev() {
		return $this->nazev;
	}
	
	public function setId($id) {
		$this->id = $id;
	}

	public function setNazev($nazev) {
		$this->nazev = $nazev;
	}

}