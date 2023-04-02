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
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @return View la vue en charge du compte et des informations du compte
     */
    public function index(PDO $pdo): View {

        // Déclaration (pour phpstan)
        $idCompte = 0;

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
        //renvoie la liste des genres pour la modal modifier genre
        $genres = $this->genderService->findAllGenders($pdo);
        $view = new View("/views/accountview");
        $view->setVar("compte", $compte);
        $view->setVar("genres", $genres);
        return $view;
    }

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @return View la vue de l'accueil après avoir supprimé le compte
     */
    public function deleteAccountById(PDO $pdo): View {

        // Déclaration (pour phpstan)
        $idCompte = 0;

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
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateLastNameById(PDO $pdo): View {

        // Déclaration (pour phpstan)
        $idCompte = 0;

        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //recupere le nouveau nom du compte
        $nom = HttpHelper::getParam("modifNom");
        if ($nom != null) $nom = htmlspecialchars($nom);
        //test si le nom est incorecte
        if ($nom == null) {
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
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateFirstNameById(PDO $pdo): View {

        // Déclaration (pour phpstan)
        $idCompte = 0;

        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //recupere le nouveau prenom du compte
        $prenom = HttpHelper::getParam("modifPrenom");
        if ($prenom != null) {
            $prenom = htmlspecialchars($prenom);
        }
        //test si le prenom est incorecte
        if ($prenom == null) {
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
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateEmailById(PDO $pdo): View {

        // Déclaration (pour phpstan)
        $idCompte = 0;

        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //recupere le nouveau mail du compte
        $email = HttpHelper::getParam("modifEmail");
        if ($email != null) {
            $email = htmlspecialchars($email);
        }
        //test si l'email est incorecte
        if ($email == null || $this->accountService->duplicateAccount($pdo, $email) != 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateMDPById(PDO $pdo): View {

        // Déclaration (pour phpstan)
        $idCompte = 0;

        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //recupere le nouveau mdp du compte
        $mdp = HttpHelper::getParam("modifMdp");
        if ($mdp != null) {
            $mdp = htmlspecialchars($mdp);
        }
        $oldMdp = HttpHelper::getParam("oldMdp");
        if ($oldMdp != null) {
            $oldMdp = htmlspecialchars($oldMdp);
        }
        //test si le mdp est incorecte
        $confirmModifMdp = HttpHelper::getParam("confirmModifMdp");
        if ($confirmModifMdp != null) {
            $confirmModifMdp = htmlspecialchars($confirmModifMdp);
        }
        if ($mdp == null || strlen($mdp) < 8 || $mdp != $confirmModifMdp) {
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
            //renvoie la page compte après avoir modifié le compte
            $this->accountService->updateMDPById($pdo, $idCompte, $mdp);
            $view = $this->index($pdo);
            return $view;
        }
    }

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateDateNaissanceById(PDO $pdo): View {

        // Déclaration (pour phpstan)
        $idCompte = 0;

        //récupere l'id
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        //recupere la nouvelle date de naissance du compte
        $dateNaissance = HttpHelper::getParam("modifDateNaissance");
        if ($dateNaissance != null) {
            $dateNaissance = htmlspecialchars($dateNaissance);
        }
        if ($dateNaissance == "") {
            $dateNaissance = null;
        }
        //renvoie la page compte après avoir modifié le compte
        $this->accountService->updateDateNaissanceById($pdo, $idCompte, $dateNaissance);
        $view = $this->index($pdo);
        return $view;
    }

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @return View la vue en charge du compte et des informations du compte
     */
    public function updateGenreById(PDO $pdo): View {

        // Déclaration (pour phpstan)
        $idCompte = 0;

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
        if ($idGenre != null) {
            $idGenre = htmlspecialchars($idGenre);
        }
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
     * @param string $data la donnée que l'on souhaite corriger
     * @return string la donnée que l'on a corrigée
     */
    public static function correction(string $data): string {
        //supprime les caractères invisibles en début et fin de chaine
        $data = trim($data);
        // supprime les antislashs de la chaine
        $data = stripslashes($data);
        // converti les caractères spéciaux en entité HTML
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * @param ?string $data donnée que l'on souhaite vérifier
     * @return bool true si $data ok false sinon
     */
    public static function verification(?string $data): bool {
        return $data != null;
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la page d'accueil en fonction des circonstances
     */
    public function createAccount(PDO $pdo): View {
        $nom = HttpHelper::getParam("nom");
        if ($nom != null) $nom = htmlspecialchars($nom);

        $prenom = HttpHelper::getParam("prenom");
        if ($prenom != null) $prenom = htmlspecialchars($prenom);

        $email = HttpHelper::getParam("mail");
        if ($email != null) $email = htmlspecialchars($email);

        $MDP = HttpHelper::getParam("MDP");
        if ($MDP != null) $MDP = htmlspecialchars($MDP);

        $MDPC = HttpHelper::getParam("MDPC");
        if ($MDPC != null) $MDPC = htmlspecialchars($MDPC);

        $datenais = HttpHelper::getParam("datenais");
        if ($datenais != null) $datenais = htmlspecialchars($datenais);

        $genre = HttpHelper::getParam("genre");
        if ($genre != null) $genre = htmlspecialchars($genre);

        if ($datenais == '') {
            $datenais = null;
        }

        if ($MDP != $MDPC || ($MDP != null && strlen($MDP) < 8)) {
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
