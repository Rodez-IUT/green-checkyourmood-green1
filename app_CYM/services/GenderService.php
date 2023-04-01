<?php

namespace services;

use PDO;
use PDOStatement;

class GenderService {

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @return PDOStatement $stmt tous les genres insérés dans la base de données
     */
    public function findAllGenders(PDO $pdo): PDOStatement{
        $sql = "SELECT ID_Gen, Nom FROM genre";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    //instance static de ce service
    private static GenderService $defaultGenderService;
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