<?php

namespace services;

use PDO;

class AccountService {

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param mixed $idCompte id du compte dont l'on souhaite avoir les données
     * @return mixed $compte le compte rechercher
     */
    public function findAccountById(PDO $pdo, mixed $idCompte): mixed {
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
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param string $email du compte que l'on recherche
     * @param string $mdp mot de passe du compte rechercher
     * @return mixed $compte le compte rechercher ayant l'email et le mot de passe
     */
    public function findAccountIdByEmailAndMDP(PDO $pdo, string $email, string $mdp): mixed {
        $sql = "SELECT compte.ID_Compte FROM compte
                WHERE compte.Email = :email
                AND compte.Mot_de_passe = :mdp";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'mdp' => $mdp]);
        return $stmt->fetch();
    }

    /**
     * Supprime un compte et toutes les données liées à ce compte
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte que l'on supprime
     */
    public function deleteAccountById(PDO $pdo, int $idCompte): void {
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
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on souhaite modifier les données
     * @param string $nom le nouveau nom que l'on souhaite avoir
     */
    public function updateLastNameById(PDO $pdo, int $idCompte, string $nom): void {
        $sql = "UPDATE compte SET Nom = :nom WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["nom" => $nom, "idCompte" => $idCompte]);
    }

    /**
     * Modifie le prenom d'un compte
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on souhaite modifier les données
     * @param string $prenom le nouveau prenom que l'on souhaite avoir
     */
    public function updateFirstNameById(PDO $pdo, int $idCompte, string $prenom): void {
        $sql = "UPDATE compte SET Prenom = :prenom WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["prenom" => $prenom, "idCompte" => $idCompte]);
    }

    /**
     * Modifie l'email d'un compte
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on souhaite modifier les données
     * @param string $email le nouvel email que l'on souhaite avoir
     */
    public function updateEmailById(PDO $pdo, int $idCompte, string $email): void {
        $sql = "UPDATE compte SET Email = :email WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["email" => $email, "idCompte" => $idCompte]);
    }

    /**
     * Modifie le mot de passe d'un compte
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on souhaite modifier les données
     * @param string $mdp le nouveau mot de passe que l'on souhaite avoir
     */
    public function updateMDPById(PDO $pdo, int $idCompte, string $mdp): void {
        $sql = "UPDATE compte SET Mot_de_passe = :mdp WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["mdp" => md5($mdp), "idCompte" => $idCompte]);
    }

    /**
     * Modifie la date de naissaince d'un compte
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on souhaite modifier les données
     * @param ?string $dateNaissance la nouvelle date de naissance que l'on souhaite avoir
     */
    public function updateDateNaissanceById(PDO $pdo, int $idCompte, ?string $dateNaissance): void {
        $sql = "UPDATE compte SET Date_de_naissance = :dateNaissance WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["dateNaissance" => $dateNaissance, "idCompte" => $idCompte]);
    }

    /**
     * Modifie le genre d'un compte
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on souhaite modifier les données
     * @param ?string $idGenre l'id du genre que l'on souhaite avoir
     */
    public function updateGenreById(PDO $pdo, int $idCompte, ?string $idGenre): void {
        if ($idGenre == "Aucun") {
            $idGenre = null;
        }
        $sql = "UPDATE compte SET Code_Gen = :idGenre WHERE ID_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idGenre" => $idGenre, "idCompte" => $idCompte]);
    }

    /**
     * Insere un compte dans la base de données avec le genre
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param ?string $nom le nom du compte que l'on souhaite insérer
     * @param ?string $prenom le prenom du compte que l'on souhaite insérer
     * @param ?string $mail
     * @param ?string $MDP
     * @param ?string $datenais la date de naissance du compte que l'on souhaite insérer
     * @param ?string $genre l'id du genre que l'on souhaite insérer
     */
    public function accountInsertion(PDO $pdo, ?string $nom, ?string $prenom, ?string $mail, ?string $MDP, ?string $datenais, ?string $genre): void {
        $sql = "INSERT INTO compte (Nom, Prenom, Date_de_naissance, Code_Gen, Mot_de_passe, Email) VALUES (:leNom, :lePrenom, :laDateDeNaissance, :leGenre, :leMDP, :leMail)";
        $stmt = $pdo->prepare($sql);
        $MDP = md5($MDP ?: "");
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
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param ?string $nom le nom du compte que l'on souhaite insérer
     * @param ?string $prenom le prenom du compte que l'on souhaite insérer
     * @param ?string $mail
     * @param ?string $MDP
     * @param ?string $datenais la date de naissance du compte que l'on souhaite insérer
     */
    public function accountInsertionGenre(PDO $pdo, ?string $nom, ?string $prenom, ?string $mail, ?string $MDP, ?string $datenais): void{
        $sql = "INSERT INTO compte (Nom, Prenom, Date_de_naissance, Mot_de_passe, Email) VALUES (:leNom, :lePrenom, :laDateDeNaissance, :leMDP, :leMail)";
        $stmt = $pdo->prepare($sql);
        $MDP = md5($MDP ?: "");
        $stmt->bindParam("leNom", $nom);
        $stmt->bindParam("lePrenom", $prenom);
        $stmt->bindParam("laDateDeNaissance", $datenais);
        $stmt->bindParam("leMDP", $MDP);
        $stmt->bindParam("leMail", $mail);
        $stmt->execute();
    }

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param ?string $mail le mail que l'on souhaite tester s'il existe
     * @return int $row le nombre de ligne ayant comme mail le mail indiqué
     */
    public function duplicateAccount(PDO $pdo, ?string $mail): int{
        $sql = "SELECT ID_COMPTE FROM compte WHERE Email = :mail";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("mail", $mail);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte que l'on teste
     * @param ?string $mdp le mot de passe à tester
     * @return bool true si le mot de passe correspond, false sinon
     */
    public function verifMdp(PDO $pdo, int $idCompte, ?string $mdp): bool {
        $mdp = md5($mdp ?: "");
        $stmt = $pdo->prepare("SELECT * FROM compte WHERE Mot_de_passe = :mdp AND ID_Compte = :idCompte");
        $stmt->execute(["mdp" => $mdp,
                        "idCompte" => $idCompte]);
        return $stmt->rowCount() != 0;
    }

    //instance static de ce service
    private static AccountService $defaultAccountService;
    /**
     * @return AccountService instance static de ce service
     */
    public static function getDefaultAccountService(): AccountService {
        if (AccountService::$defaultAccountService == null) {
            AccountService::$defaultAccountService = new AccountService();
        }
        return AccountService::$defaultAccountService;
    }
}