<?php


namespace services;

use PDO;
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;

class AccountServiceTest extends TestCase {

    private PDO $pdo;
    private AccountService $accountService;

    public function setUp(): void
    {
        parent::setUp();
        // given un pdo pour les tests
        $datasource = new DataSource(
            $host = '127.0.0.1',
            $port = 3306, # to change with the port your mySql server listen to
            $db_name = 'cym_test', # to change with your db name
            $user = 'root', # to change with your db username
            $pass = '', # to change with your db password
            $charset = 'utf8mb4'
        );
        $this->pdo = $datasource->getPdo();
        // et un genderService
        $this->accountService= new AccountService();

    }

    public function testFindAccountById_IdOk() {

        // Compte à retrouver
        $accountExpected = $this->pdo->query("SELECT compte.ID_Compte, compte.Nom, compte.Prenom, compte.Date_de_naissance, compte.Code_Gen as Genre, compte.Mot_de_passe, compte.Email
                                            FROM compte
                                            WHERE compte.ID_Compte = 1");
        $accountExpected = $accountExpected->fetch();
        $accountExpected["Genre"] = "Homme";

        // When: On cherche un compte grace à l'ID de l'utik
        $result = $this->accountService->findAccountById($this->pdo, 1);

        // Then: Le service renvoi le bon compte
        $this->assertEquals($accountExpected, $result);
    }

    public function testFindAccountById_IdNonOk() {
        // When: on cherche un compte avec
        $result = $this->accountService->findAccountById($this->pdo, "toto");

        // Alors le service renvoie null
        $this->assertEquals(null, $result);
    }

    public function testFindAccountIdByEmailAndMDP_DonnéesCorrects() {
        // Compte à trouver
        $accountExpected = $this->pdo->query("SELECT compte.ID_Compte FROM compte
                                            WHERE compte.Email = \"enzo.soulier@iut-rodez.fr\"
                                            AND compte.Mot_de_passe = \"soulier123\"");
        $accountExpected = $accountExpected->fetch();

        // When: On cherche un compte avec un email et un mot de passe correcte
        $result = $this->accountService->findAccountIdByEmailAndMDP($this->pdo, "enzo.soulier@iut-rodez.fr", "soulier123");

        // Then: Le service renvoie le bon compte
        $this->assertEquals($accountExpected, $result);
    }

    public function testFindAccountIdByEmailAndMDP_DonnéesIncorrectes() {

        // When: on cherche un compte inexistant
        $result = $this->accountService->findAccountIdByEmailAndMDP($this->pdo, "faux.mail@outlook.com", "MotDePasse");

        // Then: le service renvoi null
        $this->assertEquals(null, $result);
    }

    public function testDeleteAccountById_IdOk() {

        $this->pdo->beginTransaction();

        // When: On supprimer un compte avec un id correcte
        $this->accountService->deleteAccountById($this->pdo, 1);

        // Then: Le service supprime le compte
        $verif = $this->pdo->query("SELECT COUNT(*) as nbRow FROM compte WHERE ID_Compte = 1");
        $verif = $verif->fetch();
        $this->assertTrue($verif["nbRow"] == 0);

        $this->pdo->rollBack();
    }

    public function testDeleteAccountById_IdNonOk() {

        $this->pdo->beginTransaction();

        // Nb compte avant suppression
        $nbRow = $this->pdo->query("SELECT COUNT(*) as nbRow FROM compte");
        $nbRow = $nbRow->fetch();

        // When: On supprimer un compte avec un id incorrecte
        $this->accountService->deleteAccountById($this->pdo, 100000);

        // Nb compte apres suppression
        $verif = $this->pdo->query("SELECT COUNT(*) as nbRow FROM compte");
        $verif = $verif->fetch();

        // Then: Le service ne supprime pas le compte
        $this->assertTrue($verif["nbRow"] == $nbRow["nbRow"]);

        $this->pdo->rollBack();
    }

    public function testUpdateLastNameById_IdOk() {

        $this->pdo->beginTransaction();

        // When: on met a jour un nom avec un id correct
        $this->accountService->updateLastNameById($this->pdo, 1, "Restoueix");

        $verif = $this->pdo->query("SELECT Nom FROM compte WHERE ID_Compte = 1");
        $verif = $verif->fetch();

        // Then: Le nom est modifier
        $this->assertEquals("Restoueix", $verif["Nom"]);

        $this->pdo->rollBack();
    }

    public function testUpdateFirstNameById_IdOk() {

        $this->pdo->beginTransaction();

        // When: On met à jour le prenom
        $this->accountService->updateFirstNameById($this->pdo, 1, "Emilien");

        $verif = $this->pdo->query("SELECT Prenom FROM compte WHERE ID_Compte = 1");
        $verif = $verif->fetch();

        // Then: Le prenom est bien mit a jour
        $this->assertEquals("Emilien", $verif["Prenom"]);

        $this->pdo->rollBack();
    }

    public function testUpdateEmailById_IdOk() {

        $this->pdo->beginTransaction();

        // When: on essaye de changer de mail
        $this->accountService->updateEmailById($this->pdo, 1, "emilien.restoueix@iut-rodez.fr");

        $verif = $this->pdo->query("SELECT Email FROM compte WHERE ID_Compte = 1");
        $verif = $verif->fetch();

        // Then: le mail est bien changer
        $this->assertEquals("emilien.restoueix@iut-rodez.fr", $verif["Email"]);

        $this->pdo->rollBack();
    }

    public function testUpdateMDPById_IdOk() {

        $this->pdo->beginTransaction();

        // When: on essaye de mettre ajour son mot de passe
        $this->accountService->updateMDPById($this->pdo, 1, "MotDePasse");

        $verif = $this->pdo->query("SELECT Mot_de_passe FROM compte WHERE ID_Compte = 1");
        $verif = $verif->fetch();

        // Then: Le mot de passe est bien changer
        $this->assertEquals(md5("MotDePasse"), $verif["Mot_de_passe"]);

        $this->pdo->rollBack();
    }

    public function testUpdateDateNaissanceById() {

        $this->pdo->beginTransaction();

        // When: on essaye de changer la date de naissance
        $this->accountService->updateDateNaissanceById($this->pdo, 1, "2000-10-10");

        $verif = $this->pdo->query("SELECT Date_de_naissance FROM compte WHERE ID_Compte = 1");
        $verif = $verif->fetch();

        // Then: Alors la date de naissance est bien changer
        $this->assertEquals("2000-10-10", $verif["Date_de_naissance"]);

        $this->pdo->rollBack();
    }

    public function testUpdateGenreById_IdOk() {

        $this->pdo->beginTransaction();

        // When: on essaye de changer de genre
        $this->accountService->updateGenreById($this->pdo, 1, 2);

        $verif = $this->pdo->query("SELECT Code_Gen FROM compte WHERE ID_Compte = 1");
        $verif = $verif->fetch();

        // Then: Le genre est changer
        $this->assertEquals(2, $verif["Code_Gen"]);

        $this->pdo->rollBack();
    }

    public function testAccountInsertion() {

        $this->pdo->beginTransaction();

        // When: On insere un compte correcte
        $this->accountService->accountInsertion($this->pdo, "TEST", "Test", "test@gmail.com", "mdp", "2000-01-01", 1);

        //Compte à ajouter
        $accountExpected = ["Nom" => "TEST",
            "Prenom" => "Test",
            "Email" => "test@gmail.com",
            "Mot_de_passe" => md5("mdp"),
            "Date_de_naissance" => "2000-01-01",
            "Code_Gen" => 1];

        $id = $this->pdo->lastInsertId();
        $verif = $this->pdo->query("SELECT Nom, Prenom, Email, Mot_de_passe, Date_de_naissance, Code_Gen FROM compte WHERE ID_Compte = ".$id);
        $verif = $verif->fetch();

        // Then: le compte est bien ajouter
        $this->assertEquals($accountExpected, $verif);

        $this->pdo->rollBack();
    }

    public function testDuplicateAccount_CompteExistant() {

        // When: on verfie qu'un compte existant n'est pas present en double et on renvoie le nombre de compte trouver
        $result = $this->accountService->duplicateAccount($this->pdo, "enzo.soulier@iut-rodez.fr");

        // Then: le resultat renvoie 1 (Le compte n'est pas dupliquer)
        $this->assertEquals(1, $result);
    }

    public function testDuplicateAccount_CompteInexistant() {

        // When: on verfie qu'un compte inexistant n'est pas present et on renvoie le nombre de compte trouver
        $result = $this->accountService->duplicateAccount($this->pdo, "toto@iut-rodez.fr");

        // Then: le resultat est egal à 0
        $this->assertEquals(0, $result);
    }

    public function testVerifMdp_MdpOk(){

        // When: on test avec un bon mot de passe
        $bool = $this->accountService->verifMdp($this->pdo, 1, "soulier123");

        //Then: Le mot de passe est bon (renvoie true)
        $this->assertTrue($bool);
    }

    public function testVerifMdp_MdpNonOk(){

        // When: on test avec un mauvais mot de passe
        $bool = $this->accountService->verifMdp($this->pdo, 1, "soul12");

        //Then: Le mot de passe est bon (renvoie false)
        $this->assertFalse($bool);
    }
}