<?php

use Services\GenderService;
use yasmf\DataSource;

class GenderServiceTest extends TestCase {

    private function getPDOTest() {
        try {
            $dataSource = new DataSource(
                $host = 'localhost',
                $port = '3306', # to change with the port your mySql server listen to
                $db = 'cym_test', # to change with your db name
                $user = 'root', # to change with your db user name
                $pass = 'Mn7kXWr4', # to change with your db password
                $charset = 'utf8mb4'
            ); 
            return $dataSource->getPDO();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
            return null;
        }
    }

    public function testFindAllGenders() {
        $pdoTest = $this->getPDOTest();
        $genderService = new GenderService();

        $gendersExpected = $pdoTest->query("SELECT ID_Gen, Nom FROM genre");
        $result = $genderService->findAllGenders($pdoTest);
        $this->assertEquals($gendersExpected, $result);
        while ($rowTest = $gendersExpected->fetch()) {
            $row = $result->fetch();
            $this->assertEquals($rowTest, $row);
        }
    }

    public function testGetDefaultGenderService() {
        $result = GenderService::getDefaultGenderService();
        $this->assertInstanceOf(GenderService::class, $result);
    }
   
}