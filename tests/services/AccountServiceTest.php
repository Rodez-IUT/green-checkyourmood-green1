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

    private function getPDOTest() {
        try {
            $dataSource = new DataSource(
                $host = '127.0.0.1',
                $port = '3306', # to change with the port your mySql server listen to
                $db = 'cym_test', # to change with your db name
                $user = 'root', # to change with your db user name
                $pass = '', # to change with your db password
                $charset = 'utf8mb4'
            ); 
            return $dataSource->getPDO();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
            return null;
        }
    }

    public function testFindAccountById() {
        $pdoTest = $this->getPDOTest();
        $accountService = new AccountService();

        $result = $accountService->findAccountById($pdoTest, 1);
        $accountExpected = $pdoTest->query("SELECT compte.ID_Compte, compte.Nom, compte.Prenom, compte.Date_de_naissance, compte.Code_Gen as Genre, compte.Mot_de_passe, compte.Email
                                            FROM compte
                                            WHERE compte.ID_Compte = 1");
        $accountExpected = $accountExpected->fetch();
        $accountExpected["Genre"] = "Homme";
        $this->assertEquals($accountExpected, $result);

        $result = $accountService->findAccountById($pdoTest, 3);
        $accountExpected = $pdoTest->query("SELECT compte.ID_Compte, compte.Nom, compte.Prenom, compte.Date_de_naissance, compte.Code_Gen as Genre, compte.Mot_de_passe, compte.Email
                                            FROM compte
                                            WHERE compte.ID_Compte = 3");
        $accountExpected = $accountExpected->fetch();
        $accountExpected["Genre"] = "Non défini";
        $this->assertEquals($accountExpected, $result);

        $result = $accountService->findAccountById($pdoTest, 4);
        $accountExpected = $pdoTest->query("SELECT compte.ID_Compte, compte.Nom, compte.Prenom, compte.Date_de_naissance, compte.Code_Gen as Genre, compte.Mot_de_passe, compte.Email
                                            FROM compte
                                            WHERE compte.ID_Compte = 4");
        $accountExpected = $accountExpected->fetch();
        $accountExpected["Genre"] = "Femme";
        $this->assertEquals($accountExpected, $result);

        $result = $accountService->findAccountById($pdoTest, "toto");
        $this->assertEquals(null, $result);
    }
   
    public function testFindAccountIdByEmailAndMDP() {
        $pdoTest = $this->getPDOTest();
        $accountService = new AccountService();

        $result = $accountService->findAccountIdByEmailAndMDP($pdoTest, "enzo.soulier@iut-rodez.fr", "soulier123");
        $accountExpected = $pdoTest->query("SELECT compte.ID_Compte FROM compte
                                            WHERE compte.Email = \"enzo.soulier@iut-rodez.fr\"
                                            AND compte.Mot_de_passe = \"soulier123\"");
        $accountExpected = $accountExpected->fetch();
        $this->assertEquals($accountExpected, $result);

        $result = $accountService->findAccountIdByEmailAndMDP($pdoTest, "gamer.william@orange.fr", "xxxgamerxxx");
        $accountExpected = $pdoTest->query("SELECT compte.ID_Compte FROM compte
                                            WHERE compte.Email = \"gamer.william@orange.fr\"
                                            AND compte.Mot_de_passe = \"xxxgamerxxx\"");
        $accountExpected = $accountExpected->fetch();
        $this->assertEquals($accountExpected, $result);

        $result = $accountService->findAccountIdByEmailAndMDP($pdoTest, "gertrudelabest@gmail.com", "deuxiemeguerre");
        $accountExpected = $pdoTest->query("SELECT compte.ID_Compte FROM compte
                                            WHERE compte.Email = \"gertrudelabest@gmail.com\"
                                            AND compte.Mot_de_passe = \"deuxiemeguerre\"");
        $accountExpected = $accountExpected->fetch();
        $this->assertEquals($accountExpected, $result);

        $result = $accountService->findAccountIdByEmailAndMDP($pdoTest, "faux.mail@outlook.com", "MotDePasse");
        $this->assertEquals(null, $result);
    }

    public function testDeleteAccountById() {
        $pdoTest = $this->getPDOTest();
        try {
            $accountService = new AccountService();

            $pdoTest->beginTransaction();

            $accountService->deleteAccountById($pdoTest, 1);
            $verif = $pdoTest->query("SELECT COUNT(*) as nbRow FROM compte WHERE ID_Compte = 1");
            $verif = $verif->fetch();
            $this->assertTrue($verif["nbRow"] == 0);

            $accountService->deleteAccountById($pdoTest, 3);
            $verif = $pdoTest->query("SELECT COUNT(*) as nbRow FROM compte WHERE ID_Compte = 3");
            $verif = $verif->fetch();
            $this->assertTrue($verif["nbRow"] == 0);

            $accountService->deleteAccountById($pdoTest, 4);
            $verif = $pdoTest->query("SELECT COUNT(*) as nbRow FROM compte WHERE ID_Compte = 4");
            $verif = $verif->fetch();
            $this->assertTrue($verif["nbRow"] == 0);


            $nbRow = $pdoTest->query("SELECT COUNT(*) as nbRow FROM compte");
            $nbRow = $nbRow->fetch();
            $accountService->deleteAccountById($pdoTest, 100000);
            $verif = $pdoTest->query("SELECT COUNT(*) as nbRow FROM compte");
            $verif = $verif->fetch();
            $this->assertTrue($verif["nbRow"] == $nbRow["nbRow"]);
            $pdoTest->rollBack();
        } catch (Exception) {
            $pdoTest->rollBack();
        }
    }

    public function testUpdateLastNameById() {
        $pdoTest = $this->getPDOTest();
        try {
            $accountService = new AccountService();

            $pdoTest->beginTransaction();

            $accountService->updateLastNameById($pdoTest, 1, "Restoueix");
            $verif = $pdoTest->query("SELECT Nom FROM compte WHERE ID_Compte = 1");
            $verif = $verif->fetch();
            $this->assertEquals("Restoueix", $verif["Nom"]);
            $pdoTest->rollBack();
        } catch (Exception) {

            $pdoTest->rollBack();
        }
    }

    public function testUpdateFirstNameById() {
        $pdoTest = $this->getPDOTest();
        try {
            $accountService = new AccountService();

            $pdoTest->beginTransaction();

            $accountService->updateFirstNameById($pdoTest, 1, "Emilien");
            $verif = $pdoTest->query("SELECT Prenom FROM compte WHERE ID_Compte = 1");
            $verif = $verif->fetch();
            $this->assertEquals("Emilien", $verif["Prenom"]);

            $pdoTest->rollBack();
        } catch (Exception) {
            $pdoTest->rollBack();
        }
    }

    public function testUpdateEmailById() {
        $pdoTest = $this->getPDOTest();
        try {
            $accountService = new AccountService();

            $pdoTest->beginTransaction();

            $accountService->updateEmailById($pdoTest, 1, "emilien.restoueix@iut-rodez.fr");
            $verif = $pdoTest->query("SELECT Email FROM compte WHERE ID_Compte = 1");
            $verif = $verif->fetch();
            $this->assertEquals("emilien.restoueix@iut-rodez.fr", $verif["Email"]);
            $pdoTest->rollBack();
        } catch (Exception) {
            $pdoTest->rollBack();
        }
    }

    public function testUpdateMDPById() {
        $pdoTest = $this->getPDOTest();
        try {
            $accountService = new AccountService();

            $pdoTest->beginTransaction();

            $accountService->updateMDPById($pdoTest, 1, "MotDePasse");
            $verif = $pdoTest->query("SELECT Mot_de_passe FROM compte WHERE ID_Compte = 1");
            $verif = $verif->fetch();
            $this->assertEquals(md5("MotDePasse"), $verif["Mot_de_passe"]);
            $pdoTest->rollBack();
        } catch (Exception) {
            $pdoTest->rollBack();
        }
    }

    public function testUpdateDateNaissanceById() {
        $pdoTest = $this->getPDOTest();
        try {
            $accountService = new AccountService();

            $pdoTest->beginTransaction();

            $accountService->updateDateNaissanceById($pdoTest, 1, "2000-10-10");
            $verif = $pdoTest->query("SELECT Date_de_naissance FROM compte WHERE ID_Compte = 1");
            $verif = $verif->fetch();
            $this->assertEquals("2000-10-10", $verif["Date_de_naissance"]);

            $accountService->updateDateNaissanceById($pdoTest, 1, null);
            $verif = $pdoTest->query("SELECT Date_de_naissance FROM compte WHERE ID_Compte = 1");
            $verif = $verif->fetch();
            $this->assertEquals(null, $verif["Date_de_naissance"]);

            $pdoTest->rollBack();
        } catch (Exception) {
            $pdoTest->rollBack();
        }
    }

    public function testUpdateGenreById() {
        $pdoTest = $this->getPDOTest();
        try {
            $accountService = new AccountService();

            $pdoTest->beginTransaction();

            $accountService->updateGenreById($pdoTest, 1, 2);
            $verif = $pdoTest->query("SELECT Code_Gen FROM compte WHERE ID_Compte = 1");
            $verif = $verif->fetch();
            $this->assertEquals(2, $verif["Code_Gen"]);

            $accountService->updateGenreById($pdoTest, 1, "Aucun");
            $verif = $pdoTest->query("SELECT Code_Gen FROM compte WHERE ID_Compte = 1");
            $verif = $verif->fetch();
            $this->assertEquals(null, $verif["Code_Gen"]);

            $pdoTest->rollBack();

        } catch (Exception) {
            $pdoTest->rollBack();
        }
    }

    public function testAccountInsertion() {
        $pdoTest = $this->getPDOTest();
        try {
            $accountService = new AccountService();

            $pdoTest->beginTransaction();

            $accountService->accountInsertion($pdoTest, "TEST", "Test", "test@gmail.com", "mdp", "2000-01-01", 1);
            $accountExpected = ["Nom" => "TEST",
                                "Prenom" => "Test",
                                "Email" => "test@gmail.com",
                                "Mot_de_passe" => md5("mdp"),
                                "Date_de_naissance" => "2000-01-01",
                                "Code_Gen" => 1];
            $id = $pdoTest->lastInsertId();
            $verif = $pdoTest->query("SELECT Nom, Prenom, Email, Mot_de_passe, Date_de_naissance, Code_Gen FROM compte WHERE ID_Compte = ".$id);
            $verif = $verif->fetch();
            $this->assertEquals($accountExpected, $verif);

            $accountService->accountInsertion($pdoTest, "TEST2", "Test2", "test2@gmail.com", "mdp", null, 1);
            $accountExpected = ["Nom" => "TEST2",
                                "Prenom" => "Test2",
                                "Email" => "test2@gmail.com",
                                "Mot_de_passe" => md5("mdp"),
                                "Date_de_naissance" => null,
                                "Code_Gen" => 1];
            $id = $pdoTest->lastInsertId();
            $verif = $pdoTest->query("SELECT Nom, Prenom, Email, Mot_de_passe, Date_de_naissance, Code_Gen FROM compte WHERE ID_Compte = ".$id);
            $verif = $verif->fetch();
            $this->assertEquals($accountExpected, $verif);

            $accountService->accountInsertionGenre($pdoTest, "TEST3", "Test3", "test3@gmail.com", "mdp", "1980-11-11");
            $accountExpected = ["Nom" => "TEST3",
                                "Prenom" => "Test3",
                                "Email" => "test3@gmail.com",
                                "Mot_de_passe" => md5("mdp"),
                                "Date_de_naissance" => "1980-11-11",
                                "Code_Gen" => null];
            $id = $pdoTest->lastInsertId();
            $verif = $pdoTest->query("SELECT Nom, Prenom, Email, Mot_de_passe, Date_de_naissance, Code_Gen FROM compte WHERE ID_Compte = ".$id);
            $verif = $verif->fetch();
            $this->assertEquals($accountExpected, $verif);

            $accountService->accountInsertionGenre($pdoTest, "TEST4", "Test4", "test4@gmail.com", "mdp", null);
            $accountExpected = ["Nom" => "TEST4",
                                "Prenom" => "Test4",
                                "Email" => "test4@gmail.com",
                                "Mot_de_passe" => md5("mdp"),
                                "Date_de_naissance" => null,
                                "Code_Gen" => null];
            $id = $pdoTest->lastInsertId();
            $verif = $pdoTest->query("SELECT Nom, Prenom, Email, Mot_de_passe, Date_de_naissance, Code_Gen FROM compte WHERE ID_Compte = ".$id);
            $verif = $verif->fetch();
            $this->assertEquals($accountExpected, $verif);

            $pdoTest->rollBack();
        } catch (Exception) {
            $pdoTest->rollBack();
        }
    }

    public function testDuplicateAccount() {
        $pdoTest = $this->getPDOTest();

        $accountService = new AccountService();

        $result = $accountService->duplicateAccount($pdoTest, "enzo.soulier@iut-rodez.fr");
        $this->assertEquals(1, $result);

        $result = $accountService->duplicateAccount($pdoTest, "toto@iut-rodez.fr");
        $this->assertEquals(0, $result);
    }

    public function testGetDefaultAccountService() {
        $result = AccountService::getDefaultAccountService();
        $this->assertInstanceOf(AccountService::class, $result);
    }
}
