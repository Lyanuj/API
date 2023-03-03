<?php
session_start();

// Test de connexion à la base
$config = parse_ini_file("config.ini");
try {
	$pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
} catch(Exception $e) {
	http_response_code(500);
	header('Content-Type: application/json');
	header("Access-Control-Allow-Origin: *");
	echo '{ "message":"Erreur de connexion à la base de données" }';
	exit;
}

// Chargement des fichiers MVC
require("control/controleur.php");
require("view/vue.php");
require("model/authentification.php");
require("model/patient.php");
require("model/rdv.php");

// Routes et méthodes HTTP associées
if(isset($_GET["action"])) {
	switch($_GET["action"]) {
		case "patient":
			switch($_SERVER["REQUEST_METHOD"]) {
				case "GET":
					(new controleur)->getPatients();
					break;
				case "POST":
					(new controleur)->ajouterPatient();
					break;
				case "PUT":
					(new controleur)->modifierPatient();
					break;
				case "DELETE":
					(new controleur)->supprimerPatient();
					break;
				default:
					(new controleur)->erreur404();
					break;
			}
			break;

		case "fabriquant":
			switch($_SERVER["REQUEST_METHOD"]) {
				case "GET":
					(new controleur)->getFabriquants();
					break;
				default:
					(new controleur)->erreur404();
					break;
			}
			break;
		
		// Route par défaut : erreur 404
		default:
			(new controleur)->erreur404();
			break;
	}
}
else {
	// Pas d'action précisée = erreur 404
	(new controleur)->erreur404();
}