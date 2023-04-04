<?php

use PHPUnit\Framework\TestCase;
use services\MoodsService;
use yasmf\DataSource;

class MoodsServiceTest extends TestCase {

    private function getPDOTest() {
        try {
            $dataSource = new DataSource(
                $host = '127.0.0.1',
                $port = '3306', # to change with the port your mySql server listen to
                $db = 'cym_test', # to change with your db name
                $user = 'root', # to change with your db username
                $pass = '', # to change with your db password
                $charset = 'utf8mb4'
            ); 
            return $dataSource->getPDO();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
            return null;
        }
    }

    public function testGetAllMoods() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();

        $result = $moodsService->getAllMoods($pdoTest);
        $stmt = $pdoTest->query("SELECT ID_Hum, Libelle, Emoji FROM humeur");
        $moodsExpected = [];
        while ($row = $stmt->fetch()) {
            $moodsExpected[] = $row;
        }
        $this->assertEquals($moodsExpected, $result);
    }

    public function testGetMoodsByIdCompte() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();
        
        $result = $moodsService->getLastMoodsByIdCompte($pdoTest, 1, 10);
        $moodsExpected = $pdoTest->query("SELECT ID_Histo, ID_Hum, Libelle, Emoji, Date_Hum, Date_Ajout, Informations
                                          FROM historique
                                          JOIN humeur
                                          ON Code_hum = ID_Hum
                                          WHERE Code_Compte = 1
                                          ORDER BY Date_Hum DESC
                                          LIMIT 10");
        $this->assertInstanceOf(PDOStatement::class, $result);
        while ($moodExpected = $moodsExpected->fetch()) {
            $rowResult = $result->fetch();
            $this->assertEquals($moodExpected, $rowResult);
        }
        $rowResult = $result->fetch();
        $this->assertEmpty($rowResult);

        $result = $moodsService->getLastMoodsByIdCompte($pdoTest, 2, 1);
        $moodsExpected = $pdoTest->query("SELECT ID_Histo, ID_Hum, Libelle, Emoji, Date_Hum, Date_Ajout, Informations
                                          FROM historique
                                          JOIN humeur
                                          ON Code_hum = ID_Hum
                                          WHERE Code_Compte = 2
                                          ORDER BY Date_Hum DESC
                                          LIMIT 1");
        $this->assertInstanceOf(PDOStatement::class, $result);
        while ($moodExpected = $moodsExpected->fetch()) {
            $rowResult = $result->fetch();
            $this->assertEquals($moodExpected, $rowResult);
        }
        $rowResult = $result->fetch();
        $this->assertEmpty($rowResult);

        $result = $moodsService->getLastMoodsByIdCompte($pdoTest, 3, 2);
        $moodsExpected = $pdoTest->query("SELECT ID_Histo, ID_Hum, Libelle, Emoji, Date_Hum, Date_Ajout, Informations
                                          FROM historique
                                          JOIN humeur
                                          ON Code_hum = ID_Hum
                                          WHERE Code_Compte = 3
                                          ORDER BY Date_Hum DESC
                                          LIMIT 2");
        $this->assertInstanceOf(PDOStatement::class, $result);
        while ($moodExpected = $moodsExpected->fetch()) {
            $rowResult = $result->fetch();
            $this->assertEquals($moodExpected, $rowResult);
        }
        $rowResult = $result->fetch();
        $this->assertEmpty($rowResult);

        $result = $moodsService->getLastMoodsByIdCompte($pdoTest, 4, 2);
        $moodsExpected = $pdoTest->query("SELECT ID_Histo, ID_Hum, Libelle, Emoji, Date_Hum, Date_Ajout, Informations
                                          FROM historique
                                          JOIN humeur
                                          ON Code_hum = ID_Hum
                                          WHERE Code_Compte = 4
                                          ORDER BY Date_Hum DESC
                                          LIMIT 2");
        $this->assertInstanceOf(PDOStatement::class, $result);
        while ($moodExpected = $moodsExpected->fetch()) {
            $rowResult = $result->fetch();
            $this->assertEquals($moodExpected, $rowResult);
        }
        $rowResult = $result->fetch();
        $this->assertEmpty($rowResult);
    }
   
    public function testAddMood() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();

        $pdoTest->beginTransaction();

        date_default_timezone_set('Europe/Paris');
        $dateHum = new DateTime();
        $moodsService->addMood($pdoTest, 1, 15, $dateHum->format("Y-m-d H:i"), "");
        $idHisto = $pdoTest->lastInsertId();
        $result = $pdoTest->query("SELECT Code_Compte, Code_hum, Date_Hum, Informations
                                   FROM historique
                                   WHERE ID_Histo = $idHisto");
        $result = $result->fetch();
        $moodExpected = [
            "Code_Compte" => 1,
            "Code_hum" => 15,
            "Date_Hum" => $dateHum->format("Y-m-d H:i:00"),
            "Informations" => ""
        ];
        $this->assertEquals($moodExpected, $result);

        $dateHum = new DateTime();
        $moodsService->addMood($pdoTest, 4, 27, $dateHum->format("Y-m-d H:i"), "Test");
        $idHisto = $pdoTest->lastInsertId();
        $result = $pdoTest->query("SELECT Code_Compte, Code_hum, Date_Hum, Informations
                                   FROM historique
                                   WHERE ID_Histo = $idHisto");
        $result = $result->fetch();
        $moodExpected = [
            "Code_Compte" => 4,
            "Code_hum" => 27,
            "Date_Hum" => $dateHum->format("Y-m-d H:i:00"),
            "Informations" => "Test"
        ];
        $this->assertEquals($moodExpected, $result);

        $dateHum = new DateTime();
        $moodsService->addMood($pdoTest, 3, 1, $dateHum->format("Y-m-d H:i"), "Test");
        $idHisto = $pdoTest->lastInsertId();
        $result = $pdoTest->query("SELECT Code_Compte, Code_hum, Date_Hum, Informations
                                   FROM historique
                                   WHERE ID_Histo = $idHisto");
        $result = $result->fetch();
        $moodExpected = [
            "Code_Compte" => 3,
            "Code_hum" => 1,
            "Date_Hum" => $dateHum->format("Y-m-d H:i:00"),
            "Informations" => "Test"
        ];
        $this->assertEquals($moodExpected, $result);

        $pdoTest->rollBack();
    }

    public function testAddMoodDateException() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();

        $pdoTest->beginTransaction();

        $this->expectException(PDOException::class);
        $moodsService->addMood($pdoTest, 3, 10, "1990-01-01 12:00", "Test");

        $pdoTest->rollBack();
    }

    public function testAddMoodIdCompteException() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();

        $pdoTest->beginTransaction();

        date_default_timezone_set('Europe/Paris');
        $dateHum = new DateTime();
        $this->expectException(PDOException::class);
        $moodsService->addMood($pdoTest, 30, 10, $dateHum->format("Y-m-d H:i"), "Test");

        $pdoTest->rollBack();
    }

    public function testAddMoodIdHumException() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();

        $pdoTest->beginTransaction();

        date_default_timezone_set('Europe/Paris');
        $dateHum = new DateTime();
        $this->expectException(PDOException::class);
        $moodsService->addMood($pdoTest, 1, 28, $dateHum->format("Y-m-d H:i"), "Test");
        
        $pdoTest->rollBack();
    }

    public function testEditMoodByIdHisto() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();

        $pdoTest->beginTransaction();
        date_default_timezone_set('Europe/Paris');
        
        $idHisto = 1;
        $moodsService->editMoodByIdHisto($pdoTest, $idHisto, 1, "2022-11-09 08:31", "Test");
        $result = $pdoTest->query("SELECT ID_Histo, Code_Compte, Code_hum, Date_Hum, Informations
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
        $this->assertEquals($moodExpected, $result);

        $idHisto = 4;
        $moodsService->editMoodByIdHisto($pdoTest, $idHisto, 12, "2022-11-08 10:06", "");
        $result = $pdoTest->query("SELECT ID_Histo, Code_Compte, Code_hum, Date_Hum, Informations
                                   FROM historique
                                   WHERE ID_Histo = $idHisto");
        $result = $result->fetch();
        $moodExpected = [
            "ID_Histo" => $idHisto,
            "Code_Compte" => 4,
            "Code_hum" => 12,
            "Date_Hum" => "2022-11-08 10:06:00",
            "Informations" => ""
        ];
        $this->assertEquals($moodExpected, $result);

        $idHisto = 5;
        $moodsService->editMoodByIdHisto($pdoTest, $idHisto, 27, "2022-11-09 08:00", "TEST");
        $result = $pdoTest->query("SELECT ID_Histo, Code_Compte, Code_hum, Date_Hum, Informations
                                   FROM historique
                                   WHERE ID_Histo = $idHisto");
        $result = $result->fetch();
        $moodExpected = [
            "ID_Histo" => $idHisto,
            "Code_Compte" => 3,
            "Code_hum" => 27,
            "Date_Hum" => "2022-11-09 08:00:00",
            "Informations" => "TEST"
        ];
        $this->assertEquals($moodExpected, $result);

        $pdoTest->rollBack();
    }

    public function testEditMoodByIdHistoDateException() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();

        $pdoTest->beginTransaction();

        $this->expectException(PDOException::class);
        $moodsService->editMoodByIdHisto($pdoTest, 1, 10, "1990-10-10 10:10", "Test");
        
        $pdoTest->rollBack();
    }

    public function testEditMoodByIdHistoIdHumException() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();

        $pdoTest->beginTransaction();

        date_default_timezone_set('Europe/Paris');
        $dateHum = new DateTime();
        $this->expectException(PDOException::class);
        $moodsService->editMoodByIdHisto($pdoTest, 1, -1, $dateHum->format("Y-m-d H:i"), "Test");
        
        $pdoTest->rollBack();
    }

    public function testDeleteMoodByIdHisto() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();

        $pdoTest->beginTransaction();

        $moodsService->deleteMoodByIdHisto($pdoTest, 1);
        $verif = $pdoTest->query("SELECT COUNT(*) as nbRow FROM historique WHERE ID_Histo = 1");
        $verif = $verif->fetch();
        $this->assertTrue($verif["nbRow"] == 0);

        $moodsService->deleteMoodByIdHisto($pdoTest, 4);
        $verif = $pdoTest->query("SELECT COUNT(*) as nbRow FROM historique WHERE ID_Histo = 4");
        $verif = $verif->fetch();
        $this->assertTrue($verif["nbRow"] == 0);
        
        $pdoTest->rollBack();
    }

    public function testGetDiagramData() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();

        $pdoTest->beginTransaction();

        date_default_timezone_set('Europe/Paris');
        $now = new DateTime();
        $pdoTest->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                        VALUES(2, 14, \"".$now->format("Y-m-d H:i")."\", \"".$now->format("Y-m-d H:i")."\", NULL)");
        $pdoTest->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                        VALUES(2, 4, \"".$now->format("Y-m-d H:i")."\", \"".$now->format("Y-m-d H:i")."\", NULL)");
        $pdoTest->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                        VALUES(2, 4, \"".$now->format("Y-m-d H:i")."\", \"".$now->format("Y-m-d H:i")."\", NULL)");
        $pdoTest->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                        VALUES(2, 7, \"".$now->format("Y-m-d H:i")."\", \"".$now->format("Y-m-d H:i")."\", NULL)");
        $result = $moodsService->getDiagramData($pdoTest, 2, "today");
        $resultExpected = [
            ["Amusement", "Calme (sérénité)", "Envie (craving)"],
            [4 => 2, 7 => 1, 14 => 1]
        ];
        $this->assertEquals($resultExpected, $result);

        $yesterday = $now;
        $yesterday->modify("-1 day");
        $pdoTest->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                        VALUES(2, 5, \"".$yesterday->format("Y-m-d H:i")."\", \"".$yesterday->format("Y-m-d H:i")."\", NULL)");
        $pdoTest->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                        VALUES(2, 4, \"".$yesterday->format("Y-m-d H:i")."\", \"".$yesterday->format("Y-m-d H:i")."\", NULL)");
        $pdoTest->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                        VALUES(2, 4, \"".$yesterday->format("Y-m-d H:i")."\", \"".$yesterday->format("Y-m-d H:i")."\", NULL)");
        $pdoTest->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                        VALUES(2, 4, \"".$yesterday->format("Y-m-d H:i")."\", \"".$yesterday->format("Y-m-d H:i")."\", NULL)");
        $pdoTest->query("INSERT INTO historique(Code_Compte, Code_hum, Date_Hum, Date_Ajout, Informations)
                        VALUES(2, 1, \"".$yesterday->format("Y-m-d H:i")."\", \"".$yesterday->format("Y-m-d H:i")."\", NULL)");
        $result = $moodsService->getDiagramData($pdoTest, 2, "last-day");
        $resultExpected = [
            ["Admiration", "Amusement", "Anxiété"],
            [4 => 3, 5 => 1, 1 => 1]
        ];
        $this->assertEquals($resultExpected, $result);

        
        $pdoTest->rollBack();
        
    }

    public function testGetCalenderData() {
        $pdoTest = $this->getPDOTest();
        $moodsService = new MoodsService();

        $pdoTest->beginTransaction();

        $result = $moodsService->getCalenderData($pdoTest, 4, "2022-02", 28);
        $stmt = $pdoTest->query("SELECT * FROM humeur WHERE ID_Hum = 11");
        $humExpected = $stmt->fetch();
        $this->assertEquals($humExpected, $result[4]);

        $pdoTest->rollBack();
    }

}