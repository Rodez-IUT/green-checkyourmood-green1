<?php
	// Données
		
	function getPDO(){
		// Retourne un objet connexion à la BD
		$host='localhost';	// Serveur de BD
		$db='cym';		// Nom de la BD
		$port = '3306'; # to change with the port your mySql server listen to
		$user='root';		// User 
		$pass='root';		// Mot de passe
		$charset='utf8mb4';	// charset utilisé
		
		// Constitution variable DSN
		$dsn="mysql:host=$host;port=$port;dbname=$db;charset=$charset";
		
		// Réglage des options
		$options=[																				 
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true];
		
		try{	// Bloc try bd injoignable ou si erreur SQL
			$pdo=new PDO($dsn,$user,$pass,$options);
			return $pdo ;			
		} catch(PDOException $e){
			//Il y a eu une erreur de connexion
			$infos['Statut']="KO";
			$infos['message']="Problème connexion base de données";
			sendJSON($infos, 500) ;
			die();
		}
	}
	
	function getHumeurs($idCompte) {
		// Retourne les 5 dernieres humeurs de l'utilisateur
		try {
			$pdo=getPDO();
			$sql = "SELECT ID_Histo, ID_Hum, Libelle, Emoji, Date_Hum, Date_Ajout, Informations
                FROM historique
                JOIN humeur
                ON Code_hum = ID_Hum
                WHERE Code_Compte = :idCompte
                ORDER BY Date_Hum DESC
                LIMIT 5";

       		$stmt = $pdo->prepare($sql);
        	$stmt->execute(["idCompte" => $idCompte]);
				
			$humeurs=$stmt ->fetchALL();
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			sendJSON($humeurs, 200) ;
		} catch(PDOException $e){
			$infos['Statut'] = "KO";
			$infos['message'] = $e->getMessage();
			sendJSON($infos, 500);
		}
	}

	function getListeHumeurs() {
		// Retourne la liste des humeurs
		try {

			$pdo=getPDO();
			$sql = "SELECT ID_Hum, Libelle, Emoji FROM humeur";
        	$stmt = $pdo->query($sql);
        	$humeurs = $stmt->fetchAll();

			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			sendJSON($humeurs, 200);

		} catch(PDOException $e){
			$infos['Statut'] = "KO";
			$infos['message'] = $e->getMessage();
			sendJSON($infos, 500);
		}
	}
	
	function getConnexion($email, $mdp) {
		// Retourne la liste des clients
		try {
			$pdo=getPDO();
			$maRequete = "SELECT compte.ID_Compte FROM compte
							WHERE compte.Email = :email
							AND compte.Mot_de_passe = :mdp";
			$stmt = $pdo->prepare($maRequete);
			$mdp = md5($mdp);
			$stmt->execute(['email' => $email, 'mdp' => $mdp]);
			$nb = $stmt->rowCount();
			$compte = $stmt->fetch();
			
			
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			if ($nb != 0) {
				sendJSON($compte, 200) ;
			} else {
				sendJSON("Mot de passe ou Email Incorrect", 404) ;
			}
		} catch(PDOException $e){
			$infos['Statut'] = "KO";
			$infos['message'] = $e->getMessage();
			sendJSON($infos, 500) ;
		}
	}
	

	
	function ajoutHumeur($donneesJson) {
		if(!empty($donneesJson['ID_COMPTE'])
			&& !empty($donneesJson['ID_HUMEUR']) 
			&& !empty($donneesJson['DATE_HUMEUR'])
			&& !empty($donneesJson['INFO'])
		  ){
			  // Données remplies, on insère dans la table client
			try {
				$pdo=getPDO();
				
				$sql = "INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                VALUES (:idCompte, :idHum, :dateHum, :dateAjout, :info)";

       			$stmt = $pdo->prepare($sql);
        		date_default_timezone_set('Europe/Paris');
        		$now = new DateTime();
        		$stmt->execute(["idCompte" => $donneesJson['ID_COMPTE'],
                        		"idHum" => $donneesJson['ID_HUMEUR'],
                        		"dateHum" => $donneesJson['DATE_HUMEUR'],
                        		"dateAjout" => $now->format("Y-m-d H:i"),
                        		"info" => $donneesJson['INFO']]);
				
				$stmt->closeCursor();				
				$stmt=null;
				$pdo=null;
				
				// Retour des informations au client (statut + id créé)
				$infos['Statut']="OK";
				$infos['ID'] = "Humeur Inserer : ".$donneesJson['ID_HUMEUR']." pour l'utilisateur : ".$donneesJson['ID_COMPTE'];

				sendJSON($infos, 201) ;
			} catch(PDOException $e){
				// Retour des informations au client 
				$infos['Statut']="KO";
				$infos['message']=$e->getMessage();

				sendJSON($infos, 500) ;
			}
		} else {
			// Données manquantes, Retour des informations au client 
			$infos['Statut']="KO";
			$infos['message']="Données incomplètes";
			sendJSON($infos, 400) ;
		}
	}
?>