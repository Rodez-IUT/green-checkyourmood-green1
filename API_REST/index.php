<?php
    require_once("json.php");
    require_once("pdo.php");
    require_once("authentification.php");
	require_once("donnees.php");

	//
	$request_method = $_SERVER["REQUEST_METHOD"];  // GET / POST / DELETE / PUT
	switch($_SERVER["REQUEST_METHOD"]) {
		case "GET" :
			if (!empty($_GET['demande'])) {
				
				// décomposition URL par les / et  FILTER_SANITIZE_URL-> Supprime les caractères illégaux des URL
				$url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
				
				switch($url[0]) {
					case 'login' :
						if (isset($url[1])) {$email=$url[1];} else {$email="";}
						if (isset($url[2])) {$password=$url[2];} else {$password="";}
						verifConnexion($email, $password);
					break;
					case 'humeurs' : 
						// Retourne les humeurs
						if (isset($url[1])) {$idCompte=$url[1];} else {$idCompte="";}
                        authentification();
						getHumeurs($idCompte);
						break ;
					case 'listeHumeurs' :
                        authentification();
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
				$url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
				switch($url[0]) {
					case 'humeur':
						$donnees = json_decode(file_get_contents("php://input"),true);
                        authentification();
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
	
