<?php

class patient
{
    private $pdo;

    public function __construct() {
		$config = parse_ini_file("config.ini");
		
		try {
			$this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

    public function getAll() {
        $sql = "SELECT * FROM patient";

        $req = $this->pdo->prepare($sql);
        $req->execute();

        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get($id){
        $sql = "SELECT * FROM patient where idPatient = :id";

        $req = $this->pdo->prepare($sql);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();

        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function exists($id){
        $sql = "SELECT COUNT(*) AS nb FROM patient where idPatient = :id";

        $req = $this->pdo->prepare($sql);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();

        $nb = $req->fetch(\PDO::FETCH_ASSOC)["nb"];
        if ($nb == 1) {
            return true;
        }
        else {
            return false;
        }
    }

    public function insert($nom, $prenom, $rue, $cp, $ville, $tel, $login, $mdp) {
		$sql = "INSERT INTO patient (idPatient, nomPatient, prenomPatient, ruePatient, cpPatient, villePatient, telPatient, loginPatient, mdpPatient) 
        VALUES (:nom, :prenom, :rue, :cp, :ville, :tel, :login, :mdp)";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':nom', $nom, PDO::PARAM_STR);
        $req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $req->bindParam(':rue', $rue, PDO::PARAM_STR);
        $req->bindParam(':cp', $cp, PDO::PARAM_STR);
        $req->bindParam(':ville', $ville, PDO::PARAM_STR);
        $req->bindParam(':tel', $tel, PDO::PARAM_STR);
        $req->bindParam(':login', $login, PDO::PARAM_STR);
        $req->bindParam(':mdp', $mdp, PDO::PARAM_STR);

		$result = $req->execute();

		if($result === true) {
			return $this->pdo->lastInsertId();
		}
		else {
			return false;
		}
	}

    public function update($id, $nom, $prenom, $rue, $cp, $ville, $tel, $login, $mdp) {
		$sql = "UPDATE patient SET nomPatient = :nom, prenomPatient = :prenom, ruePatient = :rue, cpPatient = :cp, villePatient = :ville, telPatient = :tel, loginPatient = :login, mdpPatient = :mdp WHERE idPatient = :id";
		
		$req = $this->pdo->prepare($sql);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->bindParam(':nom', $nom, PDO::PARAM_STR);
        $req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $req->bindParam(':rue', $rue, PDO::PARAM_STR);
        $req->bindParam(':cp', $cp, PDO::PARAM_STR);
        $req->bindParam(':ville', $ville, PDO::PARAM_STR);
        $req->bindParam(':tel', $tel, PDO::PARAM_STR);
        $req->bindParam(':login', $login, PDO::PARAM_STR);
        $req->bindParam(':mdp', $mdp, PDO::PARAM_STR);

		return $req->execute();
	}

	public function delete($id) {
		$sql = "DELETE FROM patient WHERE idPatient = :id";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		return $req->execute();
	}
}



?>