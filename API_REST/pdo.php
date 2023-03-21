<?php

function getPDO(){
    // Retourne un objet connexion à la BD
    $host='localhost';	// Serveur de BD
    $db='cym';		// Nom de la BD
    $port = '3306'; # to change with the port your mySql server listen to
    $user='root';		// User
    $pass='root';		// Mot de passe
    $charset='utf8mb4';	// charset utilisé

    // Constitution variable DSN
    $dsn="mysql:host=$host;port=$port;dbname=$db;charset=$charset";

    // Réglage des options
    $options=[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => true];

    try{	// Bloc try bd injoignable ou si erreur SQL
        $pdo=new PDO($dsn,$user,$pass,$options);
        return $pdo ;
    } catch(PDOException $e){
        //Il y a eu une erreur de connexion
        $infos['Statut']="KO";
        $infos['message']="Problème connexion base de données";
        sendJSON($infos, 500) ;
        die();
    }
}