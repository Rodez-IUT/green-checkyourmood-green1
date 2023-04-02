<?php

function authentification() {
    if(isset($_SERVER["HTTP_APIKEYDEMONAPI"])){
        $apiKey = $_SERVER["HTTP_APIKEYDEMONAPI"];

        try{
            $pdo = getPDO();
            $sql = "SELECT ID_Compte FROM compte WHERE APIKEY = :APIKEY";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['APIKEY' => $apiKey]);
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
        $sql = "SELECT APIKEY FROM compte WHERE Email = :email AND Mot_de_passe = :mdp";
        $stmt = $pdo->prepare($sql);
        $mdp = md5($mdp);
        $stmt->execute(['email' => $email, 'mdp' => $mdp]);
        $nb = $stmt->rowCount();
        $resultat = $stmt->fetch();

        if($nb == 1){
            sendJSON($resultat, 200);
        }else{
            $infos['Status'] = "KO";
            $infos['message'] = "Logins incorects.";
            sendJSON($infos,401);
            die();
        }
    }catch(Exception $e){

    }
}
