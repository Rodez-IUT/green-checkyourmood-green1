<?php
/**
 * Sauvegarde MySQL
 *
 * @package	BackupMySQL
 * @author	Benoit Asselin <contact@ab-d.fr>
 * @version	backup.php, 2013/01/13
 * @link	http://www.ab-d.fr/
 *
 */


error_reporting(E_ALL);
ini_set('display_errors', true);


/**
 * Sauvegarde MySQL
 */
class BackupMySQL extends PDO {
	
	/**
	 * Dossier des fichiers de sauvegardes
	 * @var string
	 */
	protected $dossier;
	
	/**
	 * Nom du fichier
	 * @var string
	 */
	protected $nom_fichier;
	
	/**
	 * Ressource du fichier GZip
	 * @var ressource
	 */
	protected $gz_fichier;
	
	
	/**
	 * Constructeur
	 * @param array $options
	 */
	public function __construct($options = array()) {
		$default = array(
			'dsn' => '',
			'username' => '',
			'passwd' => '',
			'options' => [																				 
				PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
				PDO::ATTR_EMULATE_PREPARES=>false
			],
			// autres options
			'dossier' => './',
			'nbr_fichiers' => 10,
			'nom_fichier' => 'backup'
			);
		$options = array_merge($default, $options);
		extract($options);
		
		// Connexion de la connexion DB
		@parent::__construct($dsn,$username,$passwd,$options);
		
		// Controle du dossier
		$this->dossier = $dossier;
		if(!is_dir($this->dossier)) {
			$this->message('Erreur de dossier &quot;' . htmlspecialchars($this->dossier) . '&quot;');
			return;
		}
		
		// Controle du fichier
		$this->nom_fichier = $nom_fichier. '-' . date('d-m-y') . '.sql.rar';
		$this->gz_fichier = @gzopen($this->dossier . $this->nom_fichier, 'w');
		if(!$this->gz_fichier) {
			$this->message('Erreur de fichier &quot;' . htmlspecialchars($this->nom_fichier) . '&quot;');
			return;
		}
		
		// Demarrage du traitement
		$this->sauvegarder();
		$this->purger_fichiers($nbr_fichiers);
	}
	
	/**
	 * Message d'information ( commenter le "echo" pour rendre le script invisible )
	 * @param string $message HTML
	 */
	protected function message($message = '&nbsp;') {
		echo '<p style="padding:0; margin:1px 10px; font-family:sans-serif;">'. $message .'</p>';
	}
	
	/**
	 * Protection des quot SQL
	 * @param string $string
	 * @return string
	 */
	protected function insert_clean($string) {
		// Ne pas changer l'ordre du tableau !!!
		$s1 = array( "\\"	, "'"	, "\r", "\n", );
		$s2 = array( "\\\\"	, "''"	, '\r', '\n', );
		return str_replace($s1, $s2, $string);
	}
	
	/**
	 * Sauvegarder les tables
	 */
	protected function sauvegarder() {
		$this->message('Sauvegarde...');
		
		$sql5  = '--' ."\n";
		$sql5 .= '-- '. $this->nom_fichier ."\n\n";
		gzwrite($this->gz_fichier, $sql5);

        $sql='';
        $sql2 ='';
        $sql3='';
        $sql4='';
        $sqlDrop1='';
        $sqlDrop2='';
        $sqlDrop3='';
        $sqlDrop4='';
        $sql5='';
		
		// Liste les tables
		$result_tables = $this->query('SHOW TABLE STATUS');
		if($result_tables && $result_tables->rowCount()) {
			while($obj_table = $result_tables->fetch(PDO::FETCH_OBJ)) {
				$this->message('- ' . htmlspecialchars($obj_table->{'Name'}));

				// CREATE ...
				$result_create = $this->query('SHOW CREATE TABLE `'. $obj_table->{'Name'} .'`');
				if($result_create && $result_create->rowCount()) {
					$obj_create = $result_create->fetch(PDO::FETCH_OBJ);
                    if ($obj_table->{'Name'} == 'genre') {
                        $sqlDrop4 = 'DROP TABLE IF EXISTS `'. $obj_table->{'Name'} .'`' .";\n";
                        $sql2 .= $obj_create->{'Create Table'} .";\n\n";
                    }
                    if ($obj_table->{'Name'} == 'humeur') {
                        $sqlDrop3 = 'DROP TABLE IF EXISTS `'. $obj_table->{'Name'} .'`' .";\n";
                        $sql2 .= $obj_create->{'Create Table'} .";\n\n";
                    }
                    if ($obj_table->{'Name'} == 'compte'){
                        $sqlDrop2 = 'DROP TABLE IF EXISTS `'. $obj_table->{'Name'} .'`' .";\n";
                        $sql .= $obj_create->{'Create Table'} .";\n\n";
                    }
                    if ($obj_table->{'Name'} == 'historique') {
                        $sqlDrop1 = 'DROP TABLE IF EXISTS `'. $obj_table->{'Name'} .'`' .";\n";
                        $sql .= $obj_create->{'Create Table'} .";\n\n";
                    }
					$result_create->closeCursor();
				}

				// INSERT ...
				$result_insert = $this->query('SELECT * FROM `'. $obj_table->{'Name'} .'`');
				if($result_insert && $result_insert->rowCount()) {
					$sql3 .= "\n";
                    $sql4 .= "\n";
					while($obj_insert = $result_insert->fetch(PDO::FETCH_OBJ)) {
						$virgule = false;
                        if ($obj_table->{'Name'} == 'genre' || $obj_table->{'Name'} == 'humeur') {
                            $sql3 .= 'INSERT INTO `'. $obj_table->{'Name'} .'` VALUES (';
                            foreach($obj_insert as $val) {
                                $sql3 .= ($virgule ? ',' : '');
                                if(is_null($val)) {
                                    $sql3 .= 'NULL';
                                } else {
                                    $sql3 .= '\''. $this->insert_clean($val) . '\'';
                                }
                                $virgule = true;
                            } // for
                            $sql3 .= ')' .";\n";
                        }
                        $virgule = false;
                        
                        if ($obj_table->{'Name'} == 'compte' || $obj_table->{'Name'} == 'historique') {
                            $sql4 .= 'INSERT INTO `'. $obj_table->{'Name'} .'` VALUES (';
                            foreach($obj_insert as $val) {
                                $sql4 .= ($virgule ? ',' : '');
                                if(is_null($val)) {
                                    $sql4 .= 'NULL';
                                } else {
                                    $sql4 .= '\''. $this->insert_clean($val) . '\'';
                                }
                                $virgule = true;
                            } // for
                            $sql4 .= ')' .";\n";
                        }
                        
					} // while
					$result_insert->closeCursor();
				}
				
			} // while
            $sql5 = $sqlDrop1.$sqlDrop2.$sqlDrop3.$sqlDrop4."\n";
            $sql3 .= "\n".$sql4;
            $sql2 .= $sql.$sql3;
            $sql5 .= $sql2;
            gzwrite($this->gz_fichier, $sql5);
			$result_tables->closeCursor();
		}
		gzclose($this->gz_fichier);
		$this->message('<strong style="color:green;">' . htmlspecialchars($this->nom_fichier) . '</strong>');
		
		$this->message('Sauvegarde termin&eacute;e !');
	}
	
	/**
	 * Purger les anciens fichiers
	 * @param int $nbr_fichiers_max Nombre maximum de sauvegardes
	 */
	protected function purger_fichiers($nbr_fichiers_max) {
		$this->message();
		$this->message('Purge des anciens fichiers...');
		$fichiers = array();
		
		// On recupere le nom des fichiers gz
		if($dossier = dir($this->dossier)) {
			while(false !== ($fichier = $dossier->read())) {
				if($fichier != '.' && $fichier != '..') {
					if(is_dir($this->dossier . $fichier)) {
						// Ceci est un dossier ( et non un fichier )
						continue;
					} else {
						// On ne prend que les fichiers se terminant par ".gz"
						if(preg_match('/\.gz$/i', $fichier)) {
							$fichiers[] = $fichier;
						}
					}
				}
			} // while
			$dossier->close();
		}
		
		// On supprime les  anciens fichiers
		$nbr_fichiers_total = count($fichiers);
		if($nbr_fichiers_total >= $nbr_fichiers_max) {
			// Inverser l'ordre des fichiers gz pour ne pas supprimer les derniers fichiers
			rsort($fichiers);
			
			// Suppression...
			for($i = $nbr_fichiers_max; $i < $nbr_fichiers_total; $i++) {
				$this->message('<strong style="color:red;">' . htmlspecialchars($fichiers[$i]) . '</strong>');
				unlink($this->dossier . $fichiers[$i]);
			}
		}
		$this->message('Purge termin&eacute;e !');
	}
	
}
$host='localhost';	// Serveur de BD
$db='id20013142_cym';		// Nom de la BD
$charset='utf8mb4';	// charset utilisé
$port='3306';	// port

$param_utilisateur='id20013142_admin';// nom d'utilisateur pour se connecter
$param_mot_passe='CheckYourMood123@';// mot de passe de l'utilisateur pour se connecter
$param_dsn="mysql:host=$host;port=$port;dbname=$db;charset=$charset";


// Instance de la classe ( a copier autant que necessaire, mais attention au timeout )
// Rq: pour les parametres, reprendre une ou plusieurs cles de $default ( dans la methode __construct() )
new BackupMySQL(array(
	'dsn' => $param_dsn,
	'username' => $param_utilisateur,
	'passwd' => $param_mot_passe,
	));
?>