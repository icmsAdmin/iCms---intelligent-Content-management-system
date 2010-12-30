<?php
namespace iCms\Databaze\Entity;

class Role extends EntityAbstract
{
	private $id;
	public  $nazev;
	
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