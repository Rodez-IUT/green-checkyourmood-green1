<?php 
	// Routeur
	// Décomposition de l'URL via l'écriture de l'url grace au .htacess
		
	/*	
		-----------  GET ----------------------
		- index.php/typesClients  	-> Récup des types des clients
			devient : index.php?demande=typesClients  	(vue API) grace à une réécriture d'URL .HTaccess
			Exemple : index.php/typesClients
			
		- index.php/clients  		-> récup de tous les clients devient : 
			devient index.php?demande=clients  	(vue API) grace à une réécriture d'URL .HTaccess
			Exemple : index.php/clients
			
		- index.php/clients/:type  -> récup de tous les clients du type client
			devient index.php?demande=clients/typeClient  	(vue API) grace à une réécriture d'URL .HTaccess
			Exemple : index.php/clients/1
			
		- index.php/client/:id  		-> récup du client id X
			devient index.php?demande=client/X  	(vue API) grace à une réécriture d'URL .HTaccess
			Exemple : index.php/client/1
			
		- index.php/login/monLogin/monPassword -> Récupération d'une clé API

		- index.php/catalogue			-> Récup du catalogue des méthodes proposées par l'API
			devient index.php?demande=catalogue

	*/
	
	/*
		-----------  POST ----------------------
		- index.php/client  -> Création d'un client (envoi des données en JSON via postman ou curl)
		Exemple JSON : 
		{
			"CODE_CLIENT": "CLIENT API",
			"NOM_MAGASIN": "MAGASIN RODEZ API",
			"RESPONSABLE": "M. Pierre Api",
			"ADRESSE_1": "33 rue de l'API",
			"ADRESSE_2": "Local API3",
			"CODE_POSTAL": "12000",
			"VILLE": "Rodez",
			"TELEPHONE": "0565656565",
			"EMAIL": "misterapi@api.com",
			"TYPE_CLIENT": 4
		}
		
		- index.php/typeClient  -> Création d'un type client (envoi des données en JSON via postman ou curl)
		Exemple JSON : 
		{
			"TYPE_CLIENT_DESIGNATION": "Nouveau type client"
		}
		
		-----------  DELETE ----------------------
		- index.php/client/:idClient  -> Suppression du client idClient
		Exemple : index.php/client/16
		
		- index.php/typeClient/:idTypeClient  -> Suppression du type client  idTypeClient
		Exemple : index.php/typeClient/9
		
		-----------  PUT ----------------------
		- index.php/client/:idClient  -> Modification du client No IdClient
		Exemple JSON : 
		{
			"CODE_CLIENT": "CLIENT API",
			"NOM_MAGASIN": "MAGASIN RODEZ API",
			"RESPONSABLE": "M. Pierre Api",
			"ADRESSE_1": "33 rue de l'API",
			"ADRESSE_2": "Local API3",
			"CODE_POSTAL": "12000",
			"VILLE": "Rodez",
			"TELEPHONE": "0565656565",
			"EMAIL": "misterapi@api.com",
			"TYPE_CLIENT": 4
		}
		
		- index.php/typeClient/:idTypeClient  -> Modification du type de client
		Exemple JSON : 
			{
				"TYPE_CLIENT_DESIGNATION": "Centre Ville modifié"
			}
	*/
	
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
					case 'humeur' : 
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