<?php
/*
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2023   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace application;

use controllers\HomeController;
use controllers\ConnexionController;
use controllers\AccountController;
use controllers\MoodsController;

use services\AccountService;
use services\GenderService;
use services\MoodsService;

use yasmf\ComponentFactory;
use yasmf\NoControllerAvailableForName;
use yasmf\NoServiceAvailableForName;

/**
 *  The controller factory
 */
class DefaultComponentFactory implements ComponentFactory
{
    private ?AccountService $accountService = null;

    private ?GenderService $genderService = null;

    private ?MoodsService $moodsService = null;

    /**
     * @param string $controller_name the name of the controller to instanciate
     * @return mixed the controller
     * @throws NoControllerAvailableForName when controller is not found
     */
    public function buildControllerByName(string $controller_name): mixed
    {   
        return match($controller_name) {
            "Home" => $this->buildHomeController(),
            "Account" => $this->buildAccountController(),
            "Connexion" => $this->buildConnexionController(),
            "Moods" => $this->buildMoodsController(),
            default => throw new NoControllerAvailableForName($controller_name)
        };
    }

    /**
     * @param string $service_name the name of the service
     * @return mixed the created service
     * @throws NoServiceAvailableForName when service is not found
     */
    public function buildServiceByName(string $service_name): mixed
    {
        return match($service_name) {
            "Users" => $this->buildUsersService(),
            default => throw new NoServiceAvailableForName($service_name)
        };
    }

    /**
     * @return HomeController
     */
    private function buildHomeController(): HomeController
    {
        return new HomeController($this->buildGenderService());
    }

    /**
     * @return AccountController
     */
    private function buildAccountController(): AccountController
    {
        return new AccountController($this->buildAccountService(), $this->buildGenderService());
    }

    /**
     * @return ConnexionController
     */
    private function buildConnexionController(): ConnexionController
    {
        return new ConnexionController($this->buildAccountService());
    }

    /**
     * @return MoodsController
     */
    private function buildMoodsController(): MoodsController
    {
        return new MoodsController($this->buildMoodsService());
    }

    /**
     * @return AccountService
     */
    private function buildAccountService(): AccountService
    {
        if ($this->accountService == null) {
            $this->accountService = new AccountService();
        }
        return $this->accountService;
    }

    /**
     * @return GenderService
     */
    private function buildGenderService(): GenderService
    {
        if ($this->genderService == null) {
            $this->genderService = new GenderService();
        }
        return $this->genderService;
    }

    /**
     * @return MoodsService
     */
    private function buildMoodsService(): MoodsService
    {
        if ($this->moodsService == null) {
            $this->moodsService = new MoodsService();
        }
        return $this->moodsService;
    }

}