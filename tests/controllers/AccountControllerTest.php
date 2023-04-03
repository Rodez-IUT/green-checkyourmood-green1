<?php

namespace controllers;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use services\AccountService;
use services\GenderService;
use yasmf\HttpHelper;

class AccountControllerTest extends TestCase
{

    private AccountService $accountService;
    private GenderService $genderService;

    private PDO $pdo;
    private PDOStatement $pdoStatement;

    private AccountController $accountController;

    public function setUp(): void
    {
        parent::setUp();
        // given un GenderService
        $this->genderService = $this->createStub(GenderService::class);
        $this->accountService = $this->createStub(AccountService::class);
        // et une instance de PDO et un PDOStatement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        $this->accountService->method('findAccountById')->willReturn($this->pdoStatement);
        $this->genderService->method('findAllGenders')->willReturn($this->pdoStatement);
        // et un AccountController
        $this->accountController = new AccountController($this->accountService, $this->genderService);
    }

    public function testIndex_SansSession()
    {
        // when on appel AccountController->index sans session
        $view = $this->accountController->index($this->pdo);
        // when la view est accountview
        self::assertEquals("/views/accountview", $view->getRelativePath());
    }

    public function testUpdateDateNaissanceById_SansSession()
    {
        // when on appel AccountController->updateDateNaissanceById sans session
        $view = $this->accountController->updateDateNaissanceById($this->pdo);
        // when la view est accountview
        self::assertEquals("/views/accountview", $view->getRelativePath());
    }

    public function testDeleteAccountById_SansSession()
    {
        // when on appel AccountController->deleteAccountById sans session
        $view = $this->accountController->deleteAccountById($this->pdo);
        // when la view est accountview
        self::assertEquals("/views/homeview", $view->getRelativePath());
    }

    public function testUpdateGenreById_SansSession()
    {
        // when on appel AccountController->updateGenreById sans session
        $view = $this->accountController->updateGenreById($this->pdo);
        // when la view est accountview
        self::assertEquals("/views/accountview", $view->getRelativePath());
    }

    public function testUpdateLastNameById_SansSession()
    {
        // when on appel AccountController->updateLastNameById sans session
        $view = $this->accountController->updateLastNameById($this->pdo);
        // when la view est accountview
        self::assertEquals("/views/accountview", $view->getRelativePath());
    }

    public function testUpdateFirstNameById_SansSession()
    {
        // when on appel AccountController->updateFirstNameById sans session
        $view = $this->accountController->updateFirstNameById($this->pdo);
        // when la view est accountview
        self::assertEquals("/views/accountview", $view->getRelativePath());
    }

    public function testIndex_AvecSession()
    {
        // given une session avec un id correct
        session_start();
        $_SESSION["idCompte"] = 1;
        // when on appel AccountController->index
        $view = $this->accountController->index($this->pdo);
        // then la view est accountview
        // le parametre 'genres' est un retour pdo
        // le parametre 'compte' est un retour pdo
        self::assertEquals("/views/accountview", $view->getRelativePath());
        self::assertSame($this->pdoStatement, $view->getVar("genres"));
        self::assertSame($this->pdoStatement, $view->getVar("compte"));
        session_destroy();
    }

    public function testUpdateDateNaissanceById_AvecSession()
    {
        // given une session avec un id correct et une date de naissance en parametre
        session_start();
        $_SESSION["idCompte"] = 1;
        $_GET['modifDateNaissance'] = "2023/02/01";
        // when on appel AccountController->updateDateNaissanceById
        $view = $this->accountController->updateDateNaissanceById($this->pdo);
        // then la view est accountview
        self::assertEquals("/views/accountview", $view->getRelativePath());
        session_destroy();
    }

    public function testUpdateGenreById_Valide_AvecSession()
    {
        // given une session avec un id correct et un genre défini en parametre correct
        session_start();
        $_SESSION["idCompte"] = 1;
        $_GET['modifGenre'] = "Homme";
        // when on appel AccountController->updateGenreById
        $view = $this->accountController->updateGenreById($this->pdo);
        // then la view est accountview
        self::assertEquals("/views/accountview", $view->getRelativePath());
        session_destroy();
    }

    public function testUpdateGenreById_Invalide_AvecSession()
    {
        // given une session avec un id correct et un genre défini en parametre vide
        session_start();
        $_SESSION["idCompte"] = 1;
        $_GET['modifGenre'] = "";
        // when on appel AccountController->updateDateNaissanceById
        $view = $this->accountController->updateGenreById($this->pdo);
        // then la view est accountview
        // le parametre 'errModifGenre' est vrai
        self::assertEquals("/views/accountview", $view->getRelativePath());
        self::assertTrue($view->getVar("errModifGenre"));
        session_destroy();
    }

    public function testDeleteAccountById_AvecSession()
    {
        // given une session avec un id correct
        session_start();
        $_SESSION["idCompte"] = 1;
        // when on appel AccountController->deleteAccountById
        $view = $this->accountController->deleteAccountById($this->pdo);
        // then la view est homeview
        self::assertEquals("/views/homeview", $view->getRelativePath());
        session_destroy();
    }

    public function testUpdateLastNameById_Valide_AvecSession()
    {
        // given une session avec un id correct et un nom défini en parametre correct
        session_start();
        $_SESSION["idCompte"] = 1;
        $_GET['modifNom'] = 'nomTest';
        // when on appel AccountController->updateLastNameById
        $view = $this->accountController->updateLastNameById($this->pdo);
        // then la view est accountview
        self::assertEquals("/views/accountview", $view->getRelativePath());
        session_destroy();
    }

    public function testUpdateLastNameById_Invalide_AvecSession()
    {
        // given une session avec un id correct et un nom défini en parametre null
        session_start();
        $_SESSION["idCompte"] = 1;
        $_GET['modifNom'] = null;
        // when on appel AccountController->updateLastNameById
        $view = $this->accountController->updateLastNameById($this->pdo);
        // then la view est accountview
        // le parametre 'errModifNom' est vrai
        self::assertEquals("/views/accountview", $view->getRelativePath());
        self::assertTrue($view->getVar("errModifNom"));
        session_destroy();
    }

    public function testUpdateFirstNameById_Valide_AvecSession()
    {
        // given une session avec un id correct et un prenom défini en parametre correct
        session_start();
        $_SESSION["idCompte"] = 1;
        $_GET['modifPrenom'] = 'prenomTest';
        // when on appel AccountController->updateFirstNameById
        $view = $this->accountController->updateFirstNameById($this->pdo);
        // then la view est accountview
        self::assertEquals("/views/accountview", $view->getRelativePath());
        session_destroy();
    }

    public function testUpdateFirstNameById_Invalide_AvecSession()
    {
        // given une session avec un id correct et un prenom défini en parametre null
        session_start();
        $_SESSION["idCompte"] = 1;
        $_GET['modifPrenom'] = null;
        // when on appel AccountController->updateFirstNameById
        $view = $this->accountController->updateFirstNameById($this->pdo);
        // then la view est accountview
        // le parametre 'errModifPrenom' est vrai
        self::assertEquals("/views/accountview", $view->getRelativePath());
        self::assertTrue($view->getVar("errModifPrenom"));
        session_destroy();
    }
}
