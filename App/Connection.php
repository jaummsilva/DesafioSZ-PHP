<?php

namespace App;

class Connection {

	public static function getDb() {
		try {

			$conn = new \PDO(
				"mysql:host=localhost;dbname=e_commerce;charset=utf8",
				"root",
				"Jaumm#99" 
			);

			return $conn;

		} catch (\PDOException $e) {
			echo 'Erro de conexão: ' .$e;
		}
	}
}
?>