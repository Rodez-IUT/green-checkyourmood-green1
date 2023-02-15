<?php
    namespace controllers;

    use services\GenderService;
    use yasmf\View;
    
    class HomeController
    {
        //instance du service des genres
        private $genderService;

        /**
         * Créer et initialise un objet HomeController 
         */
        public function __construct() {
            $this->genderService = new GenderService();
        }

        /**
         * @param $pdo instance de PDO afin de rechercher dans la base de données 
         * @return View la vue de la page d'acceuil non authentifié
         */
        public function index($pdo){
            session_start();
            if (isset($_SESSION["idCompte"])) {
                session_unset();
                session_destroy();
            }
            $view = new View("/views/homeview");
            $genres = $this->genderService->findAllGenders($pdo);
            $view->setVar("genres", $genres);
            return $view;
        }

        /**
         * @param $pdo instance de PDO afin de rechercher dans la base de données 
         * @return View la vue de la page d'acceuil authentifié
         */
        public function homeForAccount() {
            session_start();
            if (!isset($_SESSION["idCompte"])) {
                $view = new View("/views/homeview");
            } else {
                $view = new View("/views/homeforaccountview");
            }
            session_write_close();
            return $view;
        }
    }
?>