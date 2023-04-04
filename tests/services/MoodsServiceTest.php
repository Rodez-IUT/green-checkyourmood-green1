<?php

use PHPUnit\Framework\TestCase;
use services\MoodsService;
use yasmf\DataSource;

class MoodsServiceTest extends TestCase {
    private PDO $pdo;
    private MoodsService $moodsService;

    public function setUp(): void
    {
        parent::setUp();
        // given un pdo pour les tests
        $datasource = new DataSource(
            $host = '127.0.0.1',
            $port = 3306, # to change with the port your mySql server listen to
            $db_name = 'cym_test', # to change with your db name
            $user = 'root', # to change with your db username
            $pass = '', # to change with your db password
            $charset = 'utf8mb4'
        );
        $this->pdo = $datasource->getPdo();
        // et un moodsService
        $this->moodsService = new MoodsService();

    }

    public function testGetAllMoods() {
        // when on récupère tout les moods par la fonction getAllMoods
        $result = $this->moodsService->getAllMoods($this->pdo);
        // et on récupère les données en bouclant sur les lignes de la BDD
        $stmt = $this->pdo->query("SELECT ID_Hum, Libelle, Emoji FROM humeur");
        $moodsExpected = [];
        while ($row = $stmt->fetch()) {
            $moodsExpected[] = $row;
        }
        // Then on a le même résultat
        $this->assertEquals($moodsExpected, $result);
    }

    public function testGetMoodsByIdCompte() {
        // when on récupère les dix dernières humeurs par la fonction getLastMoodsByIdCompte
        $result = $this->moodsService->getLastMoodsByIdCompte($this->pdo, 1, 10);
        // et on récupère les données depuis la BDD
        $moodsExpected = $this->pdo->query("SELECT ID_Histo, ID_Hum, Libelle, Emoji, Date_Hum, Date_Ajout, Informations
                                          FROM historique
                                          JOIN humeur
                                          ON Code_hum = ID_Hum
                                          WHERE Code_Compte = 1
                                          ORDER BY Date_Hum DESC
                                          LIMIT 10");
        // then on vérifie que les dix humeurs récupérées par la fonction soient bien du bon type
        $this->assertInstanceOf(PDOStatement::class, $result);

        // et on teste que chaque ligne des deux méthodes soient égales
        while ($moodExpected = $moodsExpected->fetch()) {
            $rowResult = $result->fetch();
            $this->assertEquals($moodExpected, $rowResult);
        }
        // et on teste que les deux méthodes aient le même nombre de lignes
        $rowResult = $result->fetch();
        $this->assertEmpty($rowResult);

        // when on récupère la dernière humeur par la fonction getLastMoodsByIdCompte
        $result = $this->moodsService->getLastMoodsByIdCompte($this->pdo, 1, 1);
        // et on récupère les données depuis la BDD
        $moodsExpected = $this->pdo->query("SELECT ID_Histo, ID_Hum, Libelle, Emoji, Date_Hum, Date_Ajout, Informations
                                          FROM historique
                                          JOIN humeur
                                          ON Code_hum = ID_Hum
                                          WHERE Code_Compte = 2
                                          ORDER BY Date_Hum DESC
                                          LIMIT 1");
        // then on vérifie que la dernière humeur soit bien récupérer du bon type
        $this->assertInstanceOf(PDOStatement::class, $result);
        while ($moodExpected = $moodsExpected->fetch()) {
            $rowResult = $result->fetch();
            $this->assertEquals($moodExpected, $rowResult);
        }
        $rowResult = $result->fetch();
        $this->assertEmpty($rowResult);
    }

    public function testAddMood() {
        try {
            $this->pdo->beginTransaction();
            // when on essaye d'insérer un mood pour un utilisateur
            date_default_timezone_set('Europe/Paris');
            $dateHum = new DateTime();
            $this->moodsService->addMood($this->pdo, 1, 15, $dateHum->format("Y-m-d H:i"), "");
            $idHisto = $this->pdo->lastInsertId();
            $result = $this->pdo->query("SELECT Code_Compte, Code_hum, Date_Hum, Informations
                                       FROM historique
                                       WHERE ID_Histo = $idHisto");
            $result = $result->fetch();
            // then le mood insérer est le dernier de l'utilisateur
            $moodExpected = [
                "Code_Compte" => 1,
                "Code_hum" => 15,
                "Date_Hum" => $dateHum->format("Y-m-d H:i:00"),
                "Informations" => ""
            ];
            $this->assertEquals($moodExpected, $result);
            $this->pdo->rollBack();
            $this->pdo->beginTransaction();
            try {
                // when on test d'ajouter une humeur invalide, then une erreur est levée
                $this->moodsService->addMood($this->pdo, 1, 15, $dateHum->format("Y-m-d fzgzgzH:i"), "");
            } catch( Exception) {

            }
            $this->pdo->rollBack();
        } catch (Exception) {
            $this->pdo->rollBack();
        }
    }

    public function testEditMoodByIdHisto() {
        try {
            $this->pdo->beginTransaction();
            date_default_timezone_set('Europe/Paris');
            $idHisto = 1;
            // when on test de modifier une humeur
            $this->moodsService->editMoodByIdHisto($this->pdo, $idHisto, 1, "2022-11-09 08:31", "Test");
            $result = $this->pdo->query("SELECT ID_Histo, Code_Compte, Code_hum, Date_Hum, Informations
                                       FROM historique
                                       WHERE ID_Histo = $idHisto");
            $result = $result->fetch();
            $moodExpected = [
                "ID_Histo" => $idHisto,
                "Code_Compte" => 1,
                "Code_hum" => 1,
                "Date_Hum" => "2022-11-09 08:31:00",
                "Informations" => "Test"
            ];
            // alors l'humeur est bien modifiée
            $this->assertEquals($moodExpected, $result);

            $this->pdo->rollBack();
            $this->pdo->beginTransaction();
            try {
                // when on teste de modifier une humeur avec une erreur de date, then une erreur est renvoyée
                $this->moodsService->editMoodByIdHisto($this->pdo, $idHisto, 1, "2022-1seggzg1-09 08:31", "Test");
            } catch( Exception) {

            }
            $this->pdo->rollBack();
        } catch (Exception) {
            $this->pdo->rollBack();
        }
    }

    public function testDeleteMoodByIdHisto() {
        try {
            $this->pdo->beginTransaction();
            // when on teste de supprimer une humeur a une personne qui n'en a qu'une
            $this->moodsService->deleteMoodByIdHisto($this->pdo, 1);
            $verif = $this->pdo->query("SELECT COUNT(*) as nbRow FROM historique WHERE ID_Histo = 1");
            $verif = $verif->fetch();
            // Then il en a plus
            $this->assertTrue($verif["nbRow"] == 0);
            $this->pdo->rollBack();
        } catch (Exception) {
            $this->pdo->rollBack();
        }
    }

    public function testGetDiagramData() {
        try {
            $this->pdo->beginTransaction();
            // when on ajoute 4 humeurs à aujourd'hui
            date_default_timezone_set('Europe/Paris');
            $now = new DateTime();
            $this->pdo->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                            VALUES(2, 14, \"".$now->format("Y-m-d H:i")."\", \"".$now->format("Y-m-d H:i")."\", NULL)");
            $this->pdo->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                            VALUES(2, 4, \"".$now->format("Y-m-d H:i")."\", \"".$now->format("Y-m-d H:i")."\", NULL)");
            $this->pdo->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                            VALUES(2, 4, \"".$now->format("Y-m-d H:i")."\", \"".$now->format("Y-m-d H:i")."\", NULL)");
            $this->pdo->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                            VALUES(2, 7, \"".$now->format("Y-m-d H:i")."\", \"".$now->format("Y-m-d H:i")."\", NULL)");
            // et on récupère les humeurs du jour grâce à la méthode getDiagram data
            $result = $this->moodsService->getDiagramData($this->pdo, 2, "today");
            // then on a les bonnes humeurs d'aujourd'hui
            $resultExpected = [
                ["Amusement", "Calme (sérénité)", "Envie (craving)"],
                [4 => 2, 7 => 1, 14 => 1]
            ];
            $this->assertEquals($resultExpected, $result);

            // when on ajoute 4 humeurs à hier
            $yesterday = $now;
            $yesterday->modify("-1 day");
            $this->pdo->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                            VALUES(2, 5, \"".$yesterday->format("Y-m-d H:i")."\", \"".$yesterday->format("Y-m-d H:i")."\", NULL)");
            $this->pdo->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                            VALUES(2, 4, \"".$yesterday->format("Y-m-d H:i")."\", \"".$yesterday->format("Y-m-d H:i")."\", NULL)");
            $this->pdo->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                            VALUES(2, 4, \"".$yesterday->format("Y-m-d H:i")."\", \"".$yesterday->format("Y-m-d H:i")."\", NULL)");
            $this->pdo->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                            VALUES(2, 4, \"".$yesterday->format("Y-m-d H:i")."\", \"".$yesterday->format("Y-m-d H:i")."\", NULL)");
            $this->pdo->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                            VALUES(2, 1, \"".$yesterday->format("Y-m-d H:i")."\", \"".$yesterday->format("Y-m-d H:i")."\", NULL)");
            // et on récupère les humeurs d'hier grâce à la méthode getDiagram data
            $result = $this->moodsService->getDiagramData($this->pdo, 2, "last-day");
            // then on a les bonnes humeurs d'hier
            $resultExpected = [
                ["Admiration", "Amusement", "Anxiété"],
                [4 => 3, 5 => 1, 1 => 1]
            ];
            $this->assertEquals($resultExpected, $result);
            $this->pdo->rollBack();
        } catch (Exception) {
            $this->pdo->rollBack();
        }
    }

    public function testGetCalenderData() {
        try {
            $this->pdo->beginTransaction();
            // when on récupère la data du calendrier par la méthode et par la base de données
            $result = $this->moodsService->getCalenderData($this->pdo, 4, "2022-02", 28);
            $stmt = $this->pdo->query("SELECT * FROM humeur WHERE ID_Hum = 11");
            $humExpected = $stmt->fetch();
            // then les deux sont égales
            $this->assertEquals($humExpected, $result[4]);

            $this->pdo->rollBack();
        } catch (Exception) {
            $this->pdo->rollBack();
        }
    }

    public function testGetNbHum(){
        // when on récupère le nb d'humeurs par la méthode et par la base de données
        $result = $this->moodsService->getNbHum($this->pdo, 4);
        $stmt = $this->pdo->query("SELECT * FROM historique WHERE Code_Compte = 4");
        $nbLignes = $stmt->rowCount();
        // Then on a le même nombre
        $this->assertEquals($nbLignes, $result);
    }

    public function testGetMoodsByIdComptePage(){
        // when on récupère des humeurs avec deux méthodes différentes
        $idCompte = 4;
        $page = 1;
        $nbHumPage = 3;
        $result = $this->moodsService->getMoodsByIdCompteByPage($this->pdo, $idCompte, $page, $nbHumPage);
        $offset = ((int)$page - 1) * $nbHumPage;
        $sql = "SELECT ID_Histo, ID_Hum, Libelle, Emoji, Date_Hum, Date_Ajout, Informations
                FROM historique
                JOIN humeur
                ON Code_hum = ID_Hum
                WHERE Code_Compte = :idCompte
                ORDER BY Date_Hum DESC
                LIMIT :nbHumPage
                OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        // then les deux méthodes sont égales
        $stmt->execute(["idCompte" => $idCompte,
                        "nbHumPage" => $nbHumPage,
                        "offset" => $offset]);
        $this->assertEquals($stmt, $result);
    }
}
