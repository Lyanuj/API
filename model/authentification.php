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

    public function connexion($id, $ip, $login, $mdp){

        $sql = "SELECT mdpPatient FROM patient WHERE loginPatient = :login";

        $req = $this->pdo->prepare($sql);
        $req->bindParam(':login', $login, PDO::PARAM_STR);
        $req->execute();

        $result = $req->fetch();
        $hash = $result[0];

        $correctPassword = password_verify($mdp, $hash);

        if (!$correctPassword) {
            return false;
        }
    
        $idPatient = $resultat['idPatient'];
        if ($idPatient != $id) {
            return false;
        }
    
        if ($_SERVER['REMOTE_ADDR'] != $ip) {
            var_dump($_SERVER['REMOTE_ADDR'])
            return false;
        }
    
        return true;
        }
        
    }

    public function getIdPatient(){
        
    }

    public function verifIP(){

    }
}

?>