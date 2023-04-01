<?php

namespace controllers;

use PDO;
use services\AccountService;
use yasmf\HttpHelper;
use yasmf\View;

class ConnexionController {

    private AccountService $accountService;

    /**
     * Créer et initialise un objet ConnexionController 
     */
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

    /**
     * @return View la vue en charge de la connexion
     */
    public function index(): View {
        return new View("/views/connexion");
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données
     * @return View la vue de la connexion en cas d'echec, sinon la page des humeurs
     */
    public function logInAction(PDO $pdo): View {
        $email = htmlspecialchars(HttpHelper::getParam('Email'));
        $mdp = htmlspecialchars(HttpHelper::getParam('mot_de_passe'));

        $email = AccountController::correction($email);
        $mdp = AccountController::correction($mdp);
        $mdp = md5($mdp);
        if (empty($email) || empty($mdp)) {
            $view = new View("/views/connexion");
            $view->setVar("errLogInAccount", true);
        } else {
            $idCompte = $this->accountService->findAccountIdByEmailAndMDP($pdo, $email, $mdp);
            if ($idCompte == null) {
                $view = new View("/views/connexion");
                $view->setVar("errLogInAccount", true);
            } else {
                session_start();
                $_SESSION["idCompte"] = $idCompte["ID_Compte"];
                session_write_close();
                header("Location: index.php?controller=Moods");
                exit();
            }
        }
        return $view;
    }

}