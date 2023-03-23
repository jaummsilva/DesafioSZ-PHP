<?php


namespace MF\Model;

abstract class Model {

	protected $db;

	public function __construct(\PDO $db) {
		$db->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$this->db = $db;
	}
}


?>