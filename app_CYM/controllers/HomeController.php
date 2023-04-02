<?php
    namespace controllers;

    use PDO;
    use services\GenderService;
    use yasmf\View;
    
    class HomeController
    {
        //instance du service des genres
        private GenderService $genderService;

        /**
         * Créer et initialise un objet HomeController 
         */
        public function __construct(GenderService $genderService) {
            $this->genderService = $genderService;
        }

        /**
         * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
         * @return View la vue de la page d'accueil non authentifié
         */
        public function index(PDO $pdo): View{
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
         * @return View la vue de la page d'accueil authentifié
         */
        public function homeForAccount(): View {
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