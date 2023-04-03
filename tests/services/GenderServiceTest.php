<?php

namespace services;

use PDO;
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;

class GenderServiceTest extends TestCase {

    private PDO $pdo;
    private GenderService $genderService;

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
        // et un genderService
        $this->genderService = new GenderService();

    }

    public function testFindAllGenders()
    {
        // when on recupere toutes les humeurs
        $statement = $this->genderService->findAllGenders($this->pdo);
        // then, d'après le fichier d'insertion
        self::assertEquals(4, $statement->rowCount());
        // et le premier genre est celui attendu
        $row = $statement->fetch();
        self::assertEquals(1, $row[ 'ID_Gen' ]);
        self::assertEquals('Homme', $row[ 'Nom' ]);
    }
}