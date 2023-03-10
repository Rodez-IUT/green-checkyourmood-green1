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
			&& !empty($donneesJson['']) 
			&& !empty($donneesJson[''])
			&& !empty($donneesJson[''])
			&& !empty($donneesJson[''])
			&& !empty($donneesJson[''])
			&& !empty($donneesJson[''])
			&& !empty($donneesJson[''])
			&& !empty($donneesJson[''])
			&& !empty($donneesJson[''])
			&& !empty($donneesJson[''])
		  ){
			  // Données remplies, on insère dans la table client
			try {
				$pdo=getPDO();
				$maRequete='INSERT INTO clients(CODE_CLIENT, NOM_MAGASIN, ADRESSE_1, ADRESSE_2, CODE_POSTAL, VILLE, RESPONSABLE, TELEPHONE, EMAIL, TYPE_CLIENT) VALUES (:CODE_CLIENT, :NOM_MAGASIN, :ADRESSE_1, :ADRESSE_2, :CODE_POSTAL, :VILLE, :RESPONSABLE, :TELEPHONE, :EMAIL, :TYPE_CLIENT)';
				$stmt = $pdo->prepare($maRequete);						// Préparation de la requête
				$stmt->bindParam("CODE_CLIENT", $donneesJson['CODE_CLIENT']);				
				$stmt->bindParam("NOM_MAGASIN", $donneesJson['NOM_MAGASIN']);
				$stmt->bindParam("ADRESSE_1", $donneesJson['ADRESSE_1']);
				$stmt->bindParam("ADRESSE_2", $donneesJson['ADRESSE_2']);
				$stmt->bindParam("CODE_POSTAL", $donneesJson['CODE_POSTAL']);
				$stmt->bindParam("VILLE", $donneesJson['VILLE']);
				$stmt->bindParam("RESPONSABLE", $donneesJson['RESPONSABLE']);
				$stmt->bindParam("TELEPHONE", $donneesJson['TELEPHONE']);
				$stmt->bindParam("EMAIL", $donneesJson['EMAIL']);
				$stmt->bindParam("TYPE_CLIENT", $donneesJson['TYPE_CLIENT']);
				$stmt->execute();	
				
				$IdInsere=$pdo->lastInsertId() ;
					
				$stmt=null;
				$pdo=null;
				
				// Retour des informations au client (statut + id créé)
				$infos['Statut']="OK";
				$infos['ID']=$IdInsere;

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