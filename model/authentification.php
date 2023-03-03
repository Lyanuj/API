<?php

class authentification {

    private $pdo;

    public function __construct() {
		$config = parse_ini_file("config.ini");
		
		try {
			$this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

    public function connexion($id, $ip){
        
    }

    public function getIdPatient(){

    }

    public function verifIP(){

    }
}

?>