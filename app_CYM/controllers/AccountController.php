<?php

namespace controllers;

use PDO;
use PDOException;
use services\AccountService;
use services\GenderService;
use yasmf\HttpHelper;
use yasmf\View;

class AccountController {

    private AccountService $accountService;
    private GenderService $genderService;

    /**
     * Créer et initialise un objet AccountController 
     */
    public function __construct(AccountService $accountService, GenderService $genderService) {
        $this->accountService = $accountService;
        $this->genderService = $genderService;
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge du compte et des informations du compte
     */
    public function index(PDO $pdo): View {
        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //cherche le compte avec cet id
        $compte = $this->accountService->findAccountById($pdo, $idCompte);
        //renvoie la liste des genre pour la modal modifier genre
        $genres = $this->genderService->findAllGenders($pdo);
        $view = new View("/views/accountview");
        $view->setVar("compte", $compte);
        $view->setVar("genres", $genres);
        return $view;
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue de l'accueil après avoir supprimer le compte
     */
    public function deleteAccountById(PDO $pdo): View {
        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        //supprime le compte
        $this->accountService->deleteAccountById($pdo, $idCompte);
        //retourne la vue de l'accueil
        $view = new View("/views/homeview");
        $genres = $this->genderService->findAllGenders($pdo);
        $view->setVar("genres", $genres);
        session_destroy();
        return $view;
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateLastNameById(PDO $pdo): View {
        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //recupere le nouveau nom du compte
        $nom = htmlspecialchars(HttpHelper::getParam("modifNom"));
        //test si le nom est incorecte
        if ($nom == null || $nom == "") {
            //renvoie la page compte en indiquant une erreur
            $view = $this->index($pdo);
            $view->setVar("errModifNom", true);
            return $view;
        } else {
            //renvoie la page compte après avoir modifier le compte
            $this->accountService->updateLastNameById($pdo, $idCompte, $nom);
            $view = $this->index($pdo);
            return $view;
        }
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateFirstNameById(PDO $pdo): View {
        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //recupere le nouveau prenom du compte
        $prenom = htmlspecialchars(HttpHelper::getParam("modifPrenom"));
        //test si le prenom est incorecte
        if ($prenom == null || $prenom == "") {
            //renvoie la page compte en indiquant une erreur
            $view = $this->index($pdo);
            $view->setVar("errModifPrenom", true);
            return $view;
        } else {
            //renvoie la page compte après avoir modifier le compte
            $this->accountService->updateFirstNameById($pdo, $idCompte, $prenom);
            $view = $this->index($pdo);
            return $view;
        }
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateEmailById(PDO $pdo): View {
        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //recupere le nouveau mail du compte
        $email = htmlspecialchars(HttpHelper::getParam("modifEmail"));
        //test si l'email est incorecte
        if ($email == null || $this->accountService->duplicateAccount($pdo, $email) != 0 || $email == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //renvoie la page compte en indiquant une erreur
            $view = $this->index($pdo);
            $view->setVar("errModifEmail", true);
            return $view;
        } else {
            //renvoie la page compte après avoir modifier le compte
            $this->accountService->updateEmailById($pdo, $idCompte, $email);
            $view = $this->index($pdo);
            return $view;
        }
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateMDPById(PDO $pdo): View {
        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //recupere le nouveau mdp du compte
        $mdp = htmlspecialchars(HttpHelper::getParam("modifMdp"));
        $oldMdp = htmlspecialchars(HttpHelper::getParam("oldMdp"));
        //test si le mdp est incorecte
        if ($mdp == null || $mdp == "" || strlen($mdp) < 8 || $mdp != htmlspecialchars(HttpHelper::getParam("confirmModifMdp"))) {
            //renvoie la page compte en indiquant une erreur
            $view = $this->index($pdo);
            $view->setVar("errModifMdp", true);
            return $view;
        } else if (!$this->accountService->verifMdp($pdo, $idCompte, $oldMdp)) {
            //renvoie la page compte en indiquant une erreur
            $view = $this->index($pdo);
            $view->setVar("errMdp", true);
            return $view;
        } else {
            //renvoie la page compte après avoir modifier le compte
            $this->accountService->updateMDPById($pdo, $idCompte, $mdp);
            $view = $this->index($pdo);
            return $view;
        }
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateDateNaissanceById(PDO $pdo): View {
        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //recupere la nouvelle date de naissance du compte
        $dateNaissance = htmlspecialchars(HttpHelper::getParam("modifDateNaissance"));
        if ($dateNaissance == "") {
            $dateNaissance = null;
        }
        //renvoie la page compte après avoir modifier le compte
        $this->accountService->updateDateNaissanceById($pdo, $idCompte, $dateNaissance);
        $view = $this->index($pdo);
        return $view;
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateGenreById(PDO $pdo): View {
        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //recupere le nouveau genre du compte
        $idGenre = HttpHelper::getParam("modifGenre");
        //test si le genre est incorecte
        if ($idGenre == "") {
            //renvoie la page compte en indiquant une erreur
            $view = $this->index($pdo);
            $view->setVar("errModifGenre", true);
            return $view;
        } else {
            //renvoie la page compte après avoir modifier le compte
            $this->accountService->updateGenreById($pdo, $idCompte, $idGenre);
            $view = $this->index($pdo);
            return $view;
        }
    }

    /**
     * @param $data la données que l'on souhaite corriger
     * @return mixed la donnée que l'on a conrigé
     */
    public static function correction($data): mixed {
        //supprime les caractères invisibles en début et fin de chaine
        $data = trim($data);
        // supprime les antislashs de la chaine
        $data = stripslashes($data);
        // converti les caractères spéciaux en entité HTML
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * @param $data donnée que l'on souhaite vérifier
     * @return bool true si $data ok false sinon
     */
    public static function verification($data): bool {
        $verif = $data != null && $data != "";
        return $verif;
    }

    /**
     * @param $data donnée que l'on souhaite comparé
     * @param $donnes donnée que l'on souhaite comparé
     * @return bool true si $data = $donnes, false sinon
     */
    public static function equal($data, $donnes): bool {
        $verif = $data == $donnes;
        return $verif;
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la page d'accueil en fonction des circonstances
     */
    public function createAccount(PDO $pdo): View {
        $nom = htmlspecialchars(HttpHelper::getParam('nom'));
        $prenom = htmlspecialchars(HttpHelper::getParam('prenom'));
        $email = htmlspecialchars(HttpHelper::getParam('mail'));
        $MDP = htmlspecialchars(HttpHelper::getParam('MDP'));
        $MDPC = htmlspecialchars(HttpHelper::getParam('MDPC'));
        $datenais = htmlspecialchars(HttpHelper::getParam('datenais'));
        $genre = htmlspecialchars(HttpHelper::getParam('genre'));

        if ($datenais == '') {
            $datenais = null;
        }

        if ($MDP != $MDPC || strlen($MDP) < 8) {
            $MDP = null;
        } 
        session_start();
        if(AccountController::verification($nom) && AccountController::verification($prenom) 
        && AccountController::verification($email) && AccountController::verification($MDP)
        && AccountController::verification($MDPC) ) {
            try {
                if ($this->accountService->duplicateAccount($pdo, $email) == 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if ($genre != 'null') {
                        $this->accountService->accountInsertion($pdo, $nom, $prenom, $email, $MDP, $datenais, $genre);
                        $view = new View("/views/homeforaccountview");
                        $_SESSION["idCompte"] = $pdo->lastInsertId();
                    } else {
                        $this->accountService->accountInsertionGenre($pdo, $nom, $prenom, $email, $MDP, $datenais);
                        $view = new View("/views/homeforaccountview");
                        $_SESSION["idCompte"] = $pdo->lastInsertId();
                    }
                } else {
                    $view = new View("/views/homeview");
                    $genres = $this->genderService->findAllGenders($pdo);
                    $view->setVar("genres", $genres);
                    $view->setVar("errDuplicateAccount", true);
                }
            } catch (PDOException $e){
                $view = new View("/views/erreurBD");
                $view->setVar('erreurE',$e);
                $view->setVar('date',$datenais);
                return $view;
            }
        } else if ($MDP == null) {
            $view = new View("/views/homeview");
            $genres = $this->genderService->findAllGenders($pdo);
            $view->setVar("genres", $genres);
            $view->setVar("errSignInMdp", true);
        } else {
            $view = new View("/views/homeview");
            $genres = $this->genderService->findAllGenders($pdo);
            $view->setVar("genres", $genres);
            $view->setVar("errSignInAccount", true);
        }
        session_write_close();
        return $view;
    }
}
