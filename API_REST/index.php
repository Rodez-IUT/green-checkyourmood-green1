<?php
	// Récupération URL si besoin par exemple pour les chemins vers des images. Non utilisé dans cet exemple
	//define("URL", str_replace("index.php","",(isset($_SERVER['HTTPS'])? "https" : "http")."://".$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"]));

	require_once("json.php");
	require_once("donnees.php");

	//
	$request_method = $_SERVER["REQUEST_METHOD"];  // GET / POST / DELETE / PUT
	switch($_SERVER["REQUEST_METHOD"]) {
		case "GET" :
			if (!empty($_GET['demande'])) {
				// $encode=urlencode($_GET['demande']);
				// $decode=urldecode($encode);
				
				// décomposition URL par les / et  FILTER_SANITIZE_URL-> Supprime les caractères illégaux des URL
				$url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
				
				switch($url[0]) {
					case 'login' :
						if (isset($url[1])) {$email=$url[1];} else {$email="";}
						if (isset($url[2])) {$password=$url[2];} else {$password="";}
						getConnexion($email, $password);
					break;
					case 'humeurs' : 
						// Retourne les humeurs
						//authentification(); // Test si on est bien authenfifié pour l'API
						if (isset($url[1])) {$idCompte=$url[1];} else {$idCompte="";}
						getHumeurs($idCompte);
						break ;
					case 'listeHumeurs' :
						getListeHumeurs();
						break;
					default : 
						$infos['Statut']="KO";
						$infos['message'] = $url[0]." inexistant";
						sendJSON($infos, 404) ;
				}
			} else {
				$infos['Statut']="KO";
				$infos['message']="URL non valide";
				sendJSON($infos, 404) ;
			}
			break ;
		case "POST" :
			if (!empty($_GET['demande'])) {
				// Ajout d'une humeur
				// Récupération des données envoyées
				$url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
				switch($url[0]) {
					case 'humeur': 
						// Ajout d'un client
						$donnees = json_decode(file_get_contents("php://input"),true);
						ajoutHumeur($donnees);
						break ;
					default : 
						$infos['Statut']="KO";
						$infos['message']="'".$url[0]."' inexistant";
						sendJSON($infos, 404) ;
				}	
			} else {
				$infos['Statut']="KO";
				$infos['message']="URL non valide";
				sendJSON($infos, 404) ;
			}
			break;

		default :
			$infos['Statut']="KO";
			$infos['message']="URL non valide";
			sendJSON($infos, 404) ;
	}
	
?>