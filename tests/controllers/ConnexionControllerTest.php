<?php

namespace controllers;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use services\AccountService;

class ConnexionControllerTest extends TestCase {

    private ConnexionController $connexionController;
    private AccountService $accountService;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        parent::setUp();
        // given un accountService
        $this->accountService = $this->createStub(AccountService::class);
        // et une instance de PDO et un PDOStatement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        // et un connexionController
        $this->connexionController = new ConnexionController($this->accountService);
    }

    public function testIndex()
    {
        self::assertNotNull($this->accountService);
        self::assertNotNull($this->connexionController);
        // when on appel l'index
        $view = $this->connexionController->index();
        // then la vue du fichier attendu
        self::assertEquals("/views/connexion", $view->getRelativePath());
    }

    public function testLogInAction_sansEmailsansMDPsansSession()
    {
        self::assertNotNull($this->accountService);
        self::assertNotNull($this->connexionController);
        // when on appel login action sans email, sans mot de passe
        $view = $this->connexionController->logInAction($this->pdo);
        // then la vue du fichier attendu
        self::assertEquals("/views/connexion", $view->getRelativePath());
        // et les données sont dans la variable de la vue
        self::assertTrue($view->getVar("errLogInAccount"));
    }

    public function testLogInAction_avecEmailavecMDPsansSession()
    {
        // given un email et un mot de passe qui ne corresponde à aucun compte
        // et le type de retour de la methode findAccountIdByEmailAndMDP
        $_GET['Email'] = "antoinegouzy@orangesfr.fr";
        $_GET['mot_de_passe'] = "123";
        $this->accountService->method('findAccountIdByEmailAndMDP')->willReturn(null);
        self::assertNotNull($this->accountService);
        self::assertNotNull($this->connexionController);
        // when on appel login action avec un email et un mot de passe incorrect
        $view = $this->connexionController->logInAction($this->pdo);
        // then la vue du fichier attendu,
        self::assertEquals("/views/connexion", $view->getRelativePath());
        // la requete retourne null
        self::assertNull($this->pdoStatement);
        // et les données sont dans la variable de la vue
        self::assertTrue($view->getVar("errLogInAccount"));
    }

    public function testLogInAction_avecEmailavecMDPAvecSession()
    {
        // given un email et un mot de passe qui corresponde à un compte
        // et le type de retour de la methode findAccountIdByEmailAndMDP
        $_GET['Email'] = "enzo.soulier@iut-rodez.fr";
        $_GET['mot_de_passe'] = "soulier123";
        $this->accountService->method('findAccountIdByEmailAndMDP')->willReturn($this->pdoStatement);
        self::assertNotNull($this->accountService);
        self::assertNotNull($this->connexionController);
        // when on appel login action avec un email et un mot de passe correct
        $view = $this->connexionController->logInAction($this->pdo);
        // then la vue du fichier attendu,
        self::assertEquals("/views/mood", $view->getRelativePath());
        // la requete retourne un compte
        self::assertNotNull($this->pdoStatement);
        // et les données sont dans la variable de la session
        self::assertEquals(1,$_SESSION["idCompte"]);
    }


}
