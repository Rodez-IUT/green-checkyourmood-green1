<?php

namespace controllers;

use PDOException;
use services\MoodsService;
use yasmf\View;
use yasmf\HttpHelper;

class MoodsController {

    //instance du service des humeurs
    private $moodsService;

    /**
     * Créer et initialise un objet MoodsController 
     */
    public function __construct() {
        $this->moodsService = MoodsService::getDefaultMoodsService();
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $view vue ou l'on souhaite set les variables pemettant de définir les périodes
     * @param $idCompte id du compte dont on souhaite les données
     */
    private function setDiagramData($pdo, $view, $idCompte) {
        $select = HttpHelper::getParam("form-search");
        $select = $select == null ? "today" : htmlspecialchars($select); 
        $daySelect = $select == "today";
        $lastDaySelect = $select == "last-day";
        $lastWeekSelect = $select == "last-week";
        $lastMonthSelect = $select == "last-month";
        $view->setVar("daySelect", $daySelect);
        $view->setVar("lastDaySelect", $lastDaySelect);
        $view->setVar("lastWeekSelect", $lastWeekSelect);
        $view->setVar("lastMonthSelect", $lastMonthSelect);
        $tab = $this->moodsService->getDiagramData($pdo, $idCompte, $select);
        $tabHum = $tab[0];
        $tabNb = $tab[1];
        $view->setVar("tabHum", $tabHum);
        $view->setVar("tabNb", $tabNb);
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @param $view vue ou l'on souhaite set les variables pemettant de faire le calendrier
     * @param $idCompte id du compte dont on souhaite les données
     */
    private function setCalenderData($pdo, $view, $idCompte) {
        $month = HttpHelper::getParam("dateSelect");
        $month = $month == null ? date("Y-m") : htmlspecialchars($month);
        $curdate = strtotime($month);
        $moisCourant = idate("m", $curdate);
        $anneeCourant = idate("Y", $curdate);
        $curdate = $moisCourant >=10 ? $anneeCourant."-".$moisCourant : $anneeCourant."-0".$moisCourant;
        $number = cal_days_in_month(CAL_GREGORIAN, $moisCourant, $anneeCourant);
        $view->setVar("moisCourant", $moisCourant);
        $view->setVar("anneeCourant", $anneeCourant);
        $view->setVar("curdate", $curdate);
        $view->setVar("number", $number);
        $emojiJour = $this->moodsService->getCalenderData($pdo, $idCompte, $curdate, $number);
        $view->setVar("emojiJour", $emojiJour);
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge des humeurs
     */
    public function index($pdo) {
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        $view = new View("/views/mood");
        $view->setVar("idCompte", $idCompte);
        $stmt = $this->moodsService->getAllMoods($pdo);
        $view->setVar("humeurs", $stmt);
        $stmt = $this->moodsService->getLastMoodsByIdCompte($pdo, $idCompte, 9);
        $view->setVar("histoHum", $stmt);
        $this->setDiagramData($pdo, $view, $idCompte);
        $this->setCalenderData($pdo, $view, $idCompte);
        return ($view);
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge des humeurs
     */
    public function addMood($pdo) {
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        $idHum = HttpHelper::getParam("humeur");
        $dateHum = htmlspecialchars(HttpHelper::getParam("dateHum"));
        $info = htmlspecialchars(HttpHelper::getParam("info"));
        try {
            $this->moodsService->addMood($pdo, $idCompte, $idHum, $dateHum, $info);
            header("Location: index.php?controller=Moods");
            exit();
        } catch (PDOException $e) {
            $view = $this->index($pdo);
            $view->setVar("erreur", true);
        }
        return $view;
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge des humeurs
     */
    public function editMood($pdo) {
        $idHisto = HttpHelper::getParam("idHisto");
        $idHum = HttpHelper::getParam("humeurModif");
        $dateHum = htmlspecialchars(HttpHelper::getParam("dateHum"));
        $info = htmlspecialchars(HttpHelper::getParam("info"));
        $page = HttpHelper::getParam("page");
        try {
            $this->moodsService->editMoodByIdHisto($pdo, $idHisto, $idHum, $dateHum, $info);
            if ($page == null) {
                $view = $this->index($pdo);
            } else {
                $view = $this->moodsList($pdo);
            }
        } catch (PDOException $e) {
            if ($page == null) {
                $view = $this->index($pdo);
                $view->setVar("erreur", true);
            } else {
                $view = $this->moodsList($pdo);
            }
        }
        return $view;
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge des humeurs
     */
    public function deleteMood($pdo) {
        $idHisto = HttpHelper::getParam("idHisto");
        $page = HttpHelper::getParam("page");
        try {
            $this->moodsService->deleteMoodByIdHisto($pdo, $idHisto);
            if ($page == null) {
                $view = $this->index($pdo);
            } else {
                $view = $this->moodsList($pdo);
            }
        } catch (PDOException $e) {
            if ($page == null) {
                $view = $this->index($pdo);
                $view->setVar("deleteError", true);
            } else {
                $view = $this->moodsList($pdo);
            }
        }
        return $view;
    }

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données 
     * @return View la vue en charge de la liste des humeurs
     */
    public function moodsList($pdo) {
        session_start();
        if (isset($_SESSION["idCompte"])) {
            $idCompte = $_SESSION["idCompte"];
        } else {
            header("Location: index.php");
        }
        session_write_close();
        $view = new View("/views/moodslist");
        $view->setVar("idCompte", $idCompte);
        $page = HttpHelper::getParam("page") == null ? 1 : HttpHelper::getParam("page");
        $view->setVar("page", $page);
        $stmt = $this->moodsService->getAllMoods($pdo);
        $view->setVar("humeurs", $stmt);
        $nbHumPage = 18;
        $stmt = $this->moodsService->getMoodsByIdCompteByPage($pdo, $idCompte, $page, $nbHumPage);
        $view->setVar("histoHum", $stmt);
        $nbHum = $this->moodsService->getNbHum($pdo, $idCompte);
        $nbPage = $nbHum % $nbHumPage == 0 ? intdiv($nbHum, $nbHumPage) : intdiv($nbHum, $nbHumPage) + 1;
        $view->setVar("nbPage", $nbPage);
        return ($view);
    }
}