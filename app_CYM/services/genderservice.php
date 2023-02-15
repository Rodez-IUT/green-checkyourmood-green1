<?php

namespace services;

class GenderService {

    /**
     * @param $pdo instance de PDO afin de rechercher dans la base de données
     * @return $stmt tous les genres inséré dans la base de données 
     */
    public function findAllGenders($pdo) {
        $sql = "SELECT ID_Gen, Nom FROM genre";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    //instance static de ce service
    private static $defaultGenderService;
    /**
     * @return mixed instance static de ce service 
     */
    public static function getDefaultGenderService() {
        if (GenderService::$defaultGenderService == null) {
            GenderService::$defaultGenderService = new GenderService();
        }
        return GenderService::$defaultGenderService;
    }
}