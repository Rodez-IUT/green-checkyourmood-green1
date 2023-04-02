<?php

namespace services;

use DateTime;
use PDO;
use PDOException;
use PDOStatement;

class MoodsService {

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @return array<int, mixed> $humeurs toutes les humeurs enregistrées dans la base de données
     */
    public function getAllMoods(PDO $pdo) : array {
        $sql = "SELECT ID_Hum, Libelle, Emoji FROM humeur";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $humeurs = [];
        while ($row = $stmt->fetch()) {
            $humeurs[] = $row;
        }
        return $humeurs;
    }

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on souhaite obtenir les humeurs
     * @param int $nbHum le nombre d'humeurs souhaité
     * @return PDOStatement $stmt toutes les humeurs enregistrées pour le compte ayant
     *         l'id $idCompte dans la base de données
     */
    public function getLastMoodsByIdCompte(PDO $pdo, int $idCompte, int $nbHum): PDOStatement {
        $sql = "SELECT ID_Histo, ID_Hum, Libelle, Emoji, Date_Hum, Date_Ajout, Informations
                FROM historique
                JOIN humeur
                ON Code_hum = ID_Hum
                WHERE Code_Compte = :idCompte
                ORDER BY Date_Hum DESC
                LIMIT :nbHum";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idCompte" => $idCompte,
                        "nbHum" => $nbHum]);
        return $stmt;
    }

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on souhaite obtenir les humeurs
     * @param string|int $page numéro de la page correspondante
     * @param int $nbHumPage nombre d'humeurs que l'on veut sur la page
     * @return PDOStatement $stmt toutes les humeurs enregistrées pour le compte ayant
     *                      l'id $idCompte dans la base de données
     */
    public function getMoodsByIdCompteByPage(PDO $pdo, int $idCompte, string|int $page, int $nbHumPage): PDOStatement {
        $offset = ((int)$page - 1) * $nbHumPage;
        $sql = "SELECT ID_Histo, ID_Hum, Libelle, Emoji, Date_Hum, Date_Ajout, Informations
                FROM historique
                JOIN humeur
                ON Code_hum = ID_Hum
                WHERE Code_Compte = :idCompte
                ORDER BY Date_Hum DESC
                LIMIT :nbHumPage
                OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idCompte" => $idCompte,
                        "nbHumPage" => $nbHumPage,
                        "offset" => $offset]);
        return $stmt;
    }

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on souhaite obtenir le nombre d'humeurs
     * @return mixed nombre d'humeurs enregistrées pour le compte ayant l'id $idCompte dans la base de données
     */
    public function getNbHum(PDO $pdo, int $idCompte): mixed {
        $sql = "SELECT COUNT(*) AS nbHum FROM historique WHERE Code_Compte = :idCompte";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idCompte" => $idCompte]);
        $result = $stmt->fetch();
        return $result["nbHum"];
    }

    /**
     * Ajoute une humeur pour un compte
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on souhaite ajouter une humeur
     * @param string $idHum id de l'humeur que l'on souhaite attribuer
     * @param string $dateHum la date de l'humeur que l'on souhaite attribuer
     * @param string $info les infos complémentaires sur l'humeur
     */
    public function addMood(PDO $pdo, int $idCompte, string $idHum, string $dateHum, string $info): void {
        $sql = "INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                VALUES (:idCompte, :idHum, :dateHum, :dateAjout, :info)";
        try {
        $stmt = $pdo->prepare($sql);
        date_default_timezone_set('Europe/Paris');
        $now = new DateTime();
        $stmt->execute(["idCompte" => $idCompte,
                        "idHum" => $idHum,
                        "dateHum" => $dateHum,
                        "dateAjout" => $now->format("Y-m-d H:i"),
                        "info" => $info]);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Modifie une humeur pour un compte
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param string $idHisto id de l'humeur dans l'historique de toute les humeurs que l'on souhaite modifier
     * @param string $idHum id de l'humeur que l'on souhaite attribuer
     * @param string $dateHum la date de l'humeur que l'on souhaite attribuer
     * @param string $info les infos complémentaires sur l'humeur
     */
    public function editMoodByIdHisto(PDO $pdo, string $idHisto, string $idHum, string $dateHum, string $info): void {
        $sql = "UPDATE historique 
                SET Code_hum = :idHum, Date_Hum = :dateHum, Informations = :info
                WHERE ID_Histo = :idHisto";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["idHum" => $idHum,
                            "dateHum" => $dateHum,
                            "info" => $info,
                            "idHisto" => $idHisto]);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Modifie une humeur pour un compte
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param string $idHisto id de l'humeur dans l'historique de toutes les humeurs que l'on souhaite modifier
     */
    public function deleteMoodByIdHisto(PDO $pdo, string $idHisto): void {
        $sql = "DELETE FROM historique
                WHERE ID_Histo = :idHisto";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idHisto" => $idHisto]);
    }

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on recherche les données
     * @param string $period periode ou l'on cherche les humeurs
     * @return array<int, mixed> tableau des humeurs et tableau de leur occurence
     */
    public function getDiagramData(PDO $pdo, int $idCompte, string $period): array {
        $stmt = $pdo->query("SELECT ID_Hum, Libelle FROM humeur");
        date_default_timezone_set('Europe/Paris');
        $now = new DateTime();
        $date = new DateTime();
        if ($period == "today") {
            $now = $now->format("Y-m-d H-i-s");
            $date = $date->format("Y-m-d 00:00:00");
        }else if ($period == "last-day") {
            $date->modify("-1 day");
            $now = $now->format("Y-m-d 00:00:00");
            $date = $date->format("Y-m-d 00:00:00");
        } else if ($period == "last-week") {
            $date->modify("-7 day");
            $now = $now->format("Y-m-d H-i-s");
            $date = $date->format("Y-m-d 00:00:00");
        } else if ($period == "last-month") {
            $date->modify("-1 month");
            $now = $now->format("Y-m-d H-i-s");
            $date = $date->format("Y-m-d 00:00:00");
        }
        $tabHum = [];
        $tabNb = [];

        if ($stmt != false) {
            while ($row = $stmt->fetch()) {
                $search = $pdo->prepare("SELECT COUNT(*) AS nb FROM historique 
                                     WHERE Code_hum = :idHum AND Code_Compte = :idCompte
                                     AND Date_Hum BETWEEN :date AND :now");
                $search->execute([
                    "idHum" => $row["ID_Hum"],
                    "idCompte" => $idCompte,
                    "date" => $date,
                    "now" => $now
                ]);
                $result = $search->fetch();
                if ($result["nb"] != 0) {
                    $tabHum[] = $row["Libelle"];
                    $tabNb[$row["ID_Hum"]] = $result["nb"];
                }
            }
        }
        return [$tabHum, $tabNb];
    }

    /**
     * @param PDO $pdo instance de PDO afin de rechercher dans la base de données
     * @param int $idCompte id du compte dont l'on recherche les données
     * @param string $month le mois ou l'on recherche les humeurs
     * @param int $number le nombre de jour dans le mois
     * @return array<int, mixed> de l'humeur la plus présente pour chaque jour
     */
    public function getCalenderData(PDO $pdo, int $idCompte, string $month, int $number): array {
        $emojiJour = [];
        for ($i = 1; $i <= $number; $i++) {
            if ($i < 10) {
                $dayStart = $month."-0".$i." 00:00:00";
                $dayEnd = $month."-0".($i + 1)." 00:00:00";
            } else if ($i == $number) {
                $dayStart = $month."-0".$i." 00:00:00";
                $dayEnd = $month."-0".$i." 23:59:59";
            } else {                
                $dayStart = $month."-".$i." 00:00:00";
                $dayEnd = $month."-".($i + 1)." 00:00:00";
            }
            $stmt = $pdo->prepare("SELECT Code_Hum, COUNT(*) as nb FROM historique
                                   WHERE Code_Compte = :idCompte
                                   AND Date_Hum BETWEEN :dayStart AND :dayEnd
                                   GROUP BY Code_Hum");
            $stmt->execute([
                "idCompte" => $idCompte,
                "dayStart" => $dayStart,
                "dayEnd" => $dayEnd
            ]);
            $emoji = $stmt->fetch();
            while ($row = $stmt->fetch()) {
                $emoji = $emoji["nb"] < $row["nb"] ? $row : $emoji;
            }
            if ($emoji != null) {
                $stmt = $pdo->prepare("SELECT * FROM humeur WHERE ID_Hum = :idHum");
                $stmt->execute(["idHum" => $emoji["Code_Hum"]]);
                $emoji = $stmt->fetch();
                $emojiJour[] = $emoji;
            } else {
                $emojiJour[] = null;
            }
        }
        return $emojiJour;
    }

    //instance static de ce service
    private static MoodsService $defaultMoodsService;
    /**
     * @return MoodsService instance static de ce service
     */
    public static function getDefaultMoodsService() {
        if (MoodsService::$defaultMoodsService == null) {
            MoodsService::$defaultMoodsService = new MoodsService();
        }
        return MoodsService::$defaultMoodsService;
    }
}