<?php
class controleur {

    public function erreur404() {
		http_response_code(404);
		(new vue)->erreur404();
	}

	public function verifierAttributsJson($objetJson, $listeDesAttributs) {
		$verifier = true;
		foreach($listeDesAttributs as $unAttribut) {
			if(!isset($objetJson->$unAttribut)) {
				$verifier = false;
			}
		}
		return $verifier;
	}

    public function getPatients() {
        $donnees = null;

		if(isset($_GET["id"])) {
			if((new patient)->exists($_GET["id"])) {
				http_response_code(200);
				$donnees = (new patient)->getPatient($_GET["id"]);
			}
			else {
				http_response_code(404);
				$donnees = array("message" => "Machine introuvable");
			}
		}
		else {
			http_response_code(200);
			$donnees = (new patient)->getAll();
		}
		
		(new vue)->transformerJson($donnees);

    }

    public function ajouterPatient() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
        if($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        }
        else {
            $attributsRequis = array("nom", "prenom", "rue", "cp", "ville", "tel", "login", "mdp");
            if($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if(!empty($donnees->nom) && !empty($donnees->prenom) && !empty($donnees->rue) && !empty($donnees->cp) && !empty($donnees->ville) && !empty($donnees->login) && !empty($donnees->login) && !empty($donnees->mdp)) {
                    $resultat = (new patient)->insert($donnees->nom, $donnees->prenom, $donnees->rue, $donnees->cp, $donnees->ville, $donnees->tel, $donnees->login, $donnees->mdp);
    
                    if($resultat !== false) {
                        http_response_code(201);
                        $renvoi = array("message" => "Ajout effectué avec succès", "idPatient" => $resultat);
                    }
                    else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                }
                else {
                    http_response_code(400);
                    $renvoi = array("message" => "Données manquantes");
                }
            }
            else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }
    
        (new vue)->transformerJson($renvoi);
    }

    public function modifierPatient() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
        if($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        }
        else {
            $attributsRequis = array("id", "nom", "prenom", "rue", "cp", "ville", "tel", "login", "mdp");
                
            if($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if((new patient)->exists($donnees->id)) {
                    $resultat = (new patient)->update($donnees->id, $donnees->nom, $donnees->prenom, $donnees->rue, $donnees->cp, $donnees->ville, $donnees->tel, $donnees->login, $donnees->mdp);

                    if($resultat != false) {
                        http_response_code(200);
                        $renvoi = array("message" => "Modification effectuée avec succès");
                    }
                    else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                }
                else {
                    http_response_code(400);
                    $renvoi = array("message" => "Le patient spécifié n'existe pas");
                }
            } 
            else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }

        (new vue)->transformerJson($renvoi);
    }


    public function supprimerPatient() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
        if ($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        } else {
            $attributsRequis = array("id");
            if ($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if ((new patient)->exists($donnees->id)) {
                    $resultat = (new patient)->delete($donnees->id);
    
                    if ($resultat != false) {
                        http_response_code(200);
                        $renvoi = array("message" => "Suppression effectuée avec succès");
                    } else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                } else {
                    http_response_code(400);
                    $renvoi = array(
                        "message" => "Le patient spécifié n'existe pas"
                    );
                }
            } else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }
    
        (new vue)->transformerJson($renvoi);
    }
    

    public function getRdvs()
    {
        $donnees = null;

        if(isset($_GET["id"])) {
            if((new rdv)->exists($_GET["id"])) {
                http_response_code(200);
                $donnees = (new rdv)->getRdv($_GET["id"]);
            }
            else {
                http_response_code(404);
                $donnees = array("message" => "Machine introuvable");
            }
        }
        else {
            http_response_code(200);
            $donnees = (new rdv)->getAll();
        }
        
        (new vue)->transformerJson($donnees);
    }


    public function ajouterRdv()
    {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
        if($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        }
        else {
            $attributsRequis = array("dateHeureRdv", "idPatient", "idMedecin");
            if($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if(!empty($donnees->dateRdv) && !empty($donnees->idPatient) && !empty($donnees->idMedecin)){
                    $resultat = (new rdv)->setRdv($donnees->dateRdv, $donnees->idPatient, $donnees->idMedecin);
    
                    if($resultat !== false) {
                        http_response_code(201);
                        $renvoi = array("message" => "Ajout effectué avec succès", "idRdv" => $resultat);
                    }
                    else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                }
                else {
                    http_response_code(400);
                    $renvoi = array("message" => "Données manquantes");
                }
            }
            else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }
    
        (new vue)->transformerJson($renvoi);
    }


    public function modifierRdv() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
    
        if($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        }
        else {
            $attributsRequis = array("id", "date", "heure", "patient_id", "medecin_id");
    
            if($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if((new rendezVous)->exists($donnees->id)) {
                    $resultat = (new rendezVous)->update($donnees->id, $donnees->date, $donnees->heure, $donnees->patient_id, $donnees->medecin_id);
    
                    if($resultat != false) {
                        http_response_code(200);
                        $renvoi = array("message" => "Modification effectuée avec succès");
                    }
                    else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                }
                else {
                    http_response_code(400);
                    $renvoi = array("message" => "Le RDV spécifié n'existe pas");
                }
            } 
            else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }
    
        (new vue)->transformerJson($renvoi);
    }
    

    public function supprimerRdv() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
        
        if ($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        } else {
            $attributsRequis = array("id");
            
            if ($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if ((new RendezVous)->exists($donnees->id)) {
                    $resultat = (new RendezVous)->delete($donnees->id);
        
                    if ($resultat != false) {
                        http_response_code(200);
                        $renvoi = array("message" => "Suppression effectuée avec succès");
                    } else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                } else {
                    http_response_code(400);
                    $renvoi = array(
                        "message" => "Le rendez-vous spécifié n'existe pas"
                    );
                }
            } else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }
    
        (new vue)->transformerJson($renvoi);
    }
    
    public function connexion() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
    
        if ($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        } else {
            $attributsRequis = array("id", "idPatient", "ip", "login", "mdp");
            if($this->verifierAttributsJson($donnees, $attributsRequis)){
                if ((new patient)->exists($donnees->idPatient)) {
                    $resultat = (new authentification)->connexion($donnees->idPatient, $donnees->ip, $donnees->login, $donnees->mdp);

                    if ($resultat != false) {
                        http_response_code(200);
                        $renvoi = array("message" => "Connexion effectuée avec succès");
                    } else {
                        http_response_code(500);
                            $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                } else {
                    http_response_code(400);
                        $renvoi = array(
                            "message" => "Mauvais nom d'utilisateur ou mot de passe"
                        );
                }       
            } else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }

        (new Vue)->transformerJson($renvoi);
    }
    
}

?>