<?php

namespace controllers;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use services\GenderService;

class HomeControllerTest extends TestCase {

    private HomeController $homeController;
    private GenderService $genderService;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        parent::setUp();
        // given un GenderService
        $this->genderService = $this->createStub(GenderService::class);
        // et une instance de PDO et un PDOStatement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        $this->genderService->method('findAllGenders')->willReturn($this->pdoStatement);
        // et un homeController
        $this->homeController = new HomeController($this->genderService);
    }

    public function testHomeForAccount_sansSession()
    {
        self::assertNotNull($this->homeController);
        // when on appel homeForAccount sans session
        $view = $this->homeController->homeForAccount();
        // then la vue du fichier attendu est affichée
        self::assertEquals("/views/homeview", $view->getRelativePath());
    }

    public function testHomeForAccount_avecSession()
    {
        // given une session avec un id de compte
        session_start();
        $_SESSION['idCompte'] = 1;
        self::assertNotNull($this->homeController);
        // when on appel homeForAccount avec une session
        $view = $this->homeController->homeForAccount();
        // then la vue du fichier attendu est affichée
        self::assertEquals("/views/homeforaccountview", $view->getRelativePath());
        session_destroy();
    }

    public function testIndex_sansSession()
    {
        self::assertNotNull($this->genderService);
        self::assertNotNull($this->homeController);
        // when on appel l'index
        $view = $this->homeController->index($this->pdo);
        // then la vue du fichier attendu
        self::assertEquals("/views/homeview", $view->getRelativePath());
        // et les données retournées par le service sont dans la variable de la vue
        self::assertSame($this->pdoStatement, $view->getVar("genres"));
    }
}