<?php

function authentification() {
    if(isset($_SERVER["HTTP_APIKEYDEMONAPI"])){
        $apiKey = $_SERVER["HTTP_APIKEYDEMONAPI"];

        try{
            $pdo = getPDO();
            $sql = "SELECT * FROM compte WHERE Mot_de_passe = :mdp";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['mdp' => $apiKey]);
            $nb = $stmt->rowCount();

            if($nb < 1){
                $infos["Status"] = "KO";
                $infos["message"] = "APIKEY invalide.";
                sendJSON($infos, 403);
                die();
            }
        }catch(Exception $e){

        }
    }else{
        $infos['Status'] = "KO";
        $infos['message'] = "Authentification necessaire par APIKEY";
        sendJSON($infos, 401);
        die();
    }
}

function verifConnexion($email, $mdp){
    try{
        $pdo = getPDO();
        $sql = "SELECT Mot_de_passe, ID_Compte FROM compte WHERE Email = :email AND Mot_de_passe = :mdp";
        $stmt = $pdo->prepare($sql);
        $mdp = md5($mdp);
        $stmt->execute(['email' => $email, 'mdp' => $mdp]);
        $nb = $stmt->rowCount();
        $resultat = $stmt->fetchAll();

        if($nb == 1){
            foreach ($resultat as $key => $value) {
                $infos['APIKEYDEMONAPPLI'] = $value["Mot_de_passe"];
                $infos['ID'] = $value["ID_Compte"];
            }
            sendJSON($infos, 200);
        }else{
            $infos['Status'] = "KO";
            $infos['message'] = "Logins incorects.";
            sendJSON($infos,401);
            die();
        }
    }catch(Exception $e){

    }
}