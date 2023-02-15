<?php

namespace services;

class AccountService {

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $idCompte id du compte dont l'on souhaite avoir les données
     * @return $compte le compte rechercher
     */
    public function findAccountById($pdo, $idCompte) {
        $sql = "SELECT compte.ID_Compte, compte.Nom, compte.Prenom, compte.Date_de_naissance, compte.Code_Gen as Genre, compte.Mot_de_passe, compte.Email
                FROM compte
                WHERE compte.ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idCompte" => $idCompte]);
        $compte = $stmt->fetch();
        if ($compte == null) {
            return null;
        }
        if ($compte["Genre"] == null) {
            $compte["Genre"] = "Non défini";
        } else {
            $sql = "SELECT Nom
                    FROM genre
                    WHERE ID_Gen = :Genre";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["Genre" => $compte["Genre"]]);
            $genre = $stmt->fetch();
            $compte["Genre"] = $genre["Nom"];
        }
        return $compte;
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $email du compte que l'on recherche
     * @param $mdp mot de passe du compte rechercher
     * @return $compte le compte rechercher ayant l'email et le mot de passe
     */
    public function findAccountIdByEmailAndMDP($pdo, $email, $mdp) {
        $sql = "SELECT compte.ID_Compte FROM compte
                WHERE compte.Email = :email
                AND compte.Mot_de_passe = :mdp";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'mdp' => $mdp]);
        $compte = $stmt->fetch();
        return $compte;
    }

    /**
     * supprime un compte et toutes les données liées à ce compte
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $idCompte id du compte dont l'on supprimer
     */
    public function deleteAccountById($pdo, $idCompte) {
        //suppresion des humeurs de ce compte
        $sql = "DELETE FROM historique WHERE Code_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idCompte" => $idCompte]);
        //suppresion du compte
        $sql = "DELETE FROM compte WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idCompte" => $idCompte]);
    }

    /**
     * Modifie le nom d'un compte
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $idCompte id du compte dont l'on souhaite modifier les données
     * @param $nom le nouveau nom que l'on souhaite avoir
     */
    public function updateLastNameById($pdo, $idCompte, $nom) {
        $sql = "UPDATE compte SET Nom = :nom WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["nom" => $nom, "idCompte" => $idCompte]);
    }

    /**
     * Modifie le prenom d'un compte
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $idCompte id du compte dont l'on souhaite modifier les données
     * @param $prenom le nouveau prenom que l'on souhaite avoir
     */
    public function updateFirstNameById($pdo, $idCompte, $prenom) {
        $sql = "UPDATE compte SET Prenom = :prenom WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["prenom" => $prenom, "idCompte" => $idCompte]);
    }

    /**
     * Modifie l'email d'un compte
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $idCompte id du compte dont l'on souhaite modifier les données
     * @param $email le nouveau email que l'on souhaite avoir
     */
    public function updateEmailById($pdo, $idCompte, $email) {
        $sql = "UPDATE compte SET Email = :email WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["email" => $email, "idCompte" => $idCompte]);
    }

    /**
     * Modifie le mot de passe d'un compte
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $idCompte id du compte dont l'on souhaite modifier les données
     * @param $mdp le nouveau mot de passe que l'on souhaite avoir
     */
    public function updateMDPById($pdo, $idCompte, $mdp) {
        $sql = "UPDATE compte SET Mot_de_passe = :mdp WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["mdp" => md5($mdp), "idCompte" => $idCompte]);
    }

    /**
     * Modifie la date de naissaince d'un compte
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $idCompte id du compte dont l'on souhaite modifier les données
     * @param $dateNaissance la nouvelle date de naissance que l'on souhaite avoir
     */
    public function updateDateNaissanceById($pdo, $idCompte, $dateNaissance) {
        $sql = "UPDATE compte SET Date_de_naissance = :dateNaissance WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["dateNaissance" => $dateNaissance, "idCompte" => $idCompte]);
    }

    /**
     * Modifie le genre d'un compte
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $idCompte id du compte dont l'on souhaite modifier les données
     * @param $idGenre l'id du genre que l'on souhaite avoir
     */
    public function updateGenreById($pdo, $idCompte, $idGenre) {
        if ($idGenre == "Aucun") {
            $idGenre = null;
        }
        $sql = "UPDATE compte SET Code_Gen = :idGenre WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idGenre" => $idGenre, "idCompte" => $idCompte]);
    }

    /**
     * Insere un compte dans la base de données avec le genre
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $nom le nom du compte que l'on souhaite insérer
     * @param $prenom le prenom du compte que l'on souhaite insérer
     * @param $email l'email du compte que l'on souhaite insérer
     * @param $mdp le mot de passe du compte que l'on souhaite insérer
     * @param $datenais la date de naissance du compte que l'on souhaite insérer
     * @param $genre l'id du genre que l'on souhaite insérer
     */
    public function accountInsertion($pdo, $nom, $prenom, $mail, $MDP, $datenais, $genre){
        $sql = "INSERT INTO compte (Nom, Prenom, Date_de_naissance, Code_Gen, Mot_de_passe, Email) VALUES (:leNom, :lePrenom, :laDateDeNaissance, :leGenre, :leMDP, :leMail)";
        $stmt = $pdo->prepare($sql);
        $MDP = md5($MDP);
        $stmt->bindParam("leNom", $nom);
        $stmt->bindParam("lePrenom", $prenom);
        $stmt->bindParam("laDateDeNaissance", $datenais);
        $stmt->bindParam("leGenre", $genre);
        $stmt->bindParam("leMDP", $MDP);
        $stmt->bindParam("leMail", $mail);
        $stmt->execute();
    }

    /**
     * Insere un compte dans la base de données sans le genre
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $nom le nom du compte que l'on souhaite insérer
     * @param $prenom le prenom du compte que l'on souhaite insérer
     * @param $email l'email du compte que l'on souhaite insérer
     * @param $mdp le mot de passe du compte que l'on souhaite insérer
     * @param $datenais la date de naissance du compte que l'on souhaite insérer
     */
    public function accountInsertionGenre($pdo, $nom, $prenom, $mail, $MDP, $datenais){
        $sql = "INSERT INTO compte (Nom, Prenom, Date_de_naissance, Mot_de_passe, Email) VALUES (:leNom, :lePrenom, :laDateDeNaissance, :leMDP, :leMail)";
        $stmt = $pdo->prepare($sql);
        $MDP = md5($MDP);
        $stmt->bindParam("leNom", $nom);
        $stmt->bindParam("lePrenom", $prenom);
        $stmt->bindParam("laDateDeNaissance", $datenais);
        $stmt->bindParam("leMDP", $MDP);
        $stmt->bindParam("leMail", $mail);
        $stmt->execute();
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $mail le mail que l'on souhaite tester s'il existe
     * @return $row le nombre de ligne ayant comme mail le mail indiqué
     */
    public function duplicateAccount($pdo, $mail){
        $sql = "SELECT ID_COMPTE FROM compte WHERE Email = :mail";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("mail", $mail);
        $stmt->execute();
        $row = $stmt->rowCount();
        return $row;
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $idCompte id du compte que l'on teste
     * @param $mdp le mot de passe à tester
     * @return true si le mot de passe correspond false sinon
     */
    public function verifMdp($pdo, $idCompte, $mdp) {
        $mdp = md5($mdp);
        $stmt = $pdo->prepare("SELECT * FROM compte WHERE Mot_de_passe = :mdp AND ID_Compte = :idCompte");
        $stmt->execute(["mdp" => $mdp,
                        "idCompte" => $idCompte]);
        return $stmt->rowCount() != 0;
    }

    //instance static de ce service
    private static $defaultAccountService;
    /**
     * @return mixed instance static de ce service
     */
    public static function getDefaultAccountService() {
        if (AccountService::$defaultAccountService == null) {
            AccountService::$defaultAccountService = new AccountService();
        }
        return AccountService::$defaultAccountService;
    }
}