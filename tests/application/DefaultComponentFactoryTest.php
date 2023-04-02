<?php

namespace application;

use controllers\AccountController;
use controllers\ConnexionController;
use controllers\HomeController;
use controllers\MoodsController;

use services\AccountService;
use services\GenderService;
use services\MoodsService;

use yasmf\NoControllerAvailableForName;
use yasmf\NoServiceAvailableForName;

use PHPUnit\Framework\TestCase;
class DefaultComponentFactoryTest extends TestCase
{

    private DefaultComponentFactory $componentFactory;

    public function setUp(): void
    {
        parent::setUp();
        // given un componentFactory
        $this->componentFactory = new DefaultComponentFactory();
    }

    public function testBuildControllerByName_Home()
    {
        // when on demande le controller Home
        $controller = $this->componentFactory->buildControllerByName("Home");
        // then l'instance du controller est HomeController
        self::assertInstanceOf(HomeController::class,$controller);
    }

    public function testBuildControllerByName_Account()
    {
        // when on demande le controller Account
        $controller = $this->componentFactory->buildControllerByName("Account");
        // then l'instance du controller est AccountController
        self::assertInstanceOf(AccountController::class,$controller);
    }

    public function testBuildControllerByName_Connexion()
    {
        // when on demande le controller Connexion
        $controller = $this->componentFactory->buildControllerByName("Connexion");
        // then l'instance du controller est ConnexionController
        self::assertInstanceOf(ConnexionController::class,$controller);
    }

    public function testBuildControllerByName_Moods()
    {
        // when on demande le controller Moods
        $controller = $this->componentFactory->buildControllerByName("Moods");
        // then l'instance du controller est MoodsController
        self::assertInstanceOf(MoodsController::class,$controller);
    }

    public function testBuildControllerByName_Other()
    {
        // Attendu une exception when on demande un controller qui n'existe pas
        $this->expectException(NoControllerAvailableForName::class);
        $this->componentFactory->buildControllerByName("NoController");
    }


    public function testBuildServiceByName_Account()
    {
        // when on demande un AccountService
        $service = $this->componentFactory->buildServiceByName("Account");
        // then on obtient une instance de AccountService
        self::assertInstanceOf(AccountService::class,$service);
    }

    public function testBuildServiceByName_Gender()
    {
        // when on demande un GenderService
        $service = $this->componentFactory->buildServiceByName("Gender");
        // then on obtient une instance de GenderService
        self::assertInstanceOf(GenderService::class,$service);
    }

    public function testBuildServiceByName_Moods()
    {
        // when on demande un MoodsService
        $service = $this->componentFactory->buildServiceByName("Moods");
        // then on obtient une instance de MoodsService
        self::assertInstanceOf(MoodsService::class,$service);
    }

    public function testBuildServiceByName_Other()
    {
        // Attendu une exception when on demande un service qui n'existe pas
        $this->expectException(NoServiceAvailableForName::class);
        $this->componentFactory->buildServiceByName("NoService");
    }
}