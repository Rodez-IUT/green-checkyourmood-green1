<?php
use yasmf\HttpHelper;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="css/CSS_principal.css">
        <script src="https://kit.fontawesome.com/9f5b052c0b.js" crossorigin="anonymous"></script>
        <script src="../js/Main.js"></script>
        <title>CheckYourMood - Accueil</title>
    </head>
    <body>
        <!-- début du container-fluid (navbar) -->
        <div class="container-fluid d-flex flex-column">

            <!-- Debut navbar -->
            <div class="row navbar">
                <!-- Logo CYM -->
                <div class="col-md-1 col-sm-2 col-xs-3">
                    <a href="index.php">
                        <img class="logo_cym" src="images\logo_CYM.png" alt="Logo">
                    </a>
                </div>
                <!-- Espace entre les éléments du menu et le logo -->
                <!--<div class="col-md-8 col-sm-6 col-xs-4"></div>-->
                <!-- Debut Menu -->
                <div class="col-md-3 col-sm-4 col-xs-5">
                    <div class="row">
                        <form action="index.php" method="post" class="col-md-6">
                            <button type="submit" class="menu_element active">
                                <i class="fa-solid fa-house"></i> 
                                <span>Accueil</span> 
                            </button>
                        </form>
                        <form action="index.php" methos="post" class="col-md-6">
                            <input hidden name="controller" value="Connexion">
                            <button type="submit" class="menu_element">
                                <i class="fa-solid fa-circle-user"></i>
                                <span>Se connecter</span>
                            </button>
                        </form>
                    </div>
                <!-- Fin Menu -->
                </div>
            <!-- Fin navbar -->
            </div>
        <!-- Fin container-fluid -->
        </div>

        <!-- début du container (contenu de la page) -->
        <div class="container">

            <!-- entete de la page qui explique ce qu'est check your mood -->
            <div class="row">
                <div class="col-md-6">
                    <div class="row separateur">
                        <div class="col-md-12">
                            <h1 class="title-lvl2"> CheckYourMood c'est quoi ?</h1>
                            <p> CheckYourMood est un site web permettant de rentrer des humeurs tout au long de la journée. </p>
                            <h2 class="title-lvl2"> Pourquoi </h2>
                            <p> Pour pouvoir étudier les humeurs qui ressortent le plus chaques jours, chaques semaines </p>
                        </div>
                    </div>
                </div>
                <div class="body-container col-md-6 separateur-right">
                    <!-- Titre du formulaire -->
                    <div>
                        <h2 class="title-lvl2"> Pour nous rejoindre </h2>
                    </div>
                    <!-- formulaire -->
                    <form action="../index.php" method="post" class="body-container-form">
                        <input hidden name="controller" value="account">
                        <input hidden name="action" value="createAccount">
                        <span>les champs suivit d'un * sont obligatoires</span>

                        <!-- Message d'erreur si il y a des erreurs -->
                        <!-- Message d'erreur si un des champs obligatoires est vide ou incorrect -->
                        <div
                        <?php 
                        if (!isset($errSignInAccount) || !$errSignInAccount) {
                            echo " hidden";
                        }
                        ?> class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10 erreurAccount">
                                <span>Un des champs obligatoires est vide ou incorrect !</span>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <!-- Message d'erreur si l'adresse mail correspond deja a un compte existant -->
                        <div
                        <?php 
                        if (!isset($errDuplicateAccount) || !$errDuplicateAccount) {
                            echo " hidden";
                        }
                        ?> class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10 erreurAccount">
                                <span>Un compte est deja existant avec cette adresse mail ou l'email est invalide</span>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <!-- Message d'erreur si le mot de pas n'est pas identique à la confirmation -->
                        <div
                        <?php 
                        if (!isset($errSignInMdp) || !$errSignInMdp) {
                            echo " hidden";
                        }
                        ?> class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10 erreurAccount">
                                <span>Le mot de passe doit avoir au moins 8 caractères et les deux mots de passe doivent être identique</span>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div>
                            <p>Nom *</p>
                            <input type="text" name="nom" placeholder="Silvestre" class="formulaire1 form-control"
                            <?php 
                            if(isset($_POST['nom'])){
                                echo " value=\"".$_POST['nom']."\"";
                            }
                            ?>
                            >
                        </div>

                        <div>
                            <p>Prenom *</p>
                            <input type="text" name="prenom" placeholder="Franck" class="formulaire1 form-control"
                            <?php 
                            if(isset($_POST['prenom'])){
                                echo " value=\"".$_POST['prenom']."\"";
                            }
                            ?>
                            >
                        </div>

                        <div>
                            <p>Adresse Mail *</p>
                            <input type="text" name="mail" placeholder="franck.silvestre@iut-rodez.fr" class="formulaire1 form-control"
                            <?php 
                            if(isset($_POST['mail'])){
                                echo " value=\"".$_POST['mail']."\"";
                            }
                            ?>
                            >
                        </div>

                        <div>
                            <p>Mot de passe *</p>
                            <input type="password" id="pass" name="MDP" placeholder="••••••••••••" class="formulaire1 form-control"
                            <?php 
                            if(isset($_POST['MDP'])){
                                echo " value=\"".$_POST['MDP']."\"";
                            }
                            ?>
                            >
                        </div>

                        <div>
                            <p>Confirmer mot de passe *</p>
                            <input type="password" id="pass2" name="MDPC" placeholder="••••••••••••" class="formulaire1 form-control"
                            <?php 
                            if(isset($_POST['MDPC'])){
                                echo " value=\"".$_POST['MDPC']."\"";
                            }
                            ?>
                            >
                        </div>

                        <div>
                            <p>Date de naissance</p>
                            <input type="date" min="1900-01-01"
                            <?php
                                echo " max=\"".date("Y-m-d")."\"";
                            ?>
                            name="datenais" class="formulaire1 form-control"/>
                        </div>

                        <div>
                            <p>Genre</p>
                            <select name="genre" class="formulaire1 form-select">
                                <option value=null>Aucun</option>
                                <?php
                                    while ($row = $genres->fetch()) {
                                        echo "<option value=".$row["ID_Gen"];
                                        if (HttpHelper::getParam("genre") == $row["ID_Gen"]) {
                                            echo " selected";
                                        }
                                        echo">".$row["Nom"]."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="row center-form">
                            <div class="passInput col-md-6">
                                <input class="center-form" type="checkbox" id="check" name="valide" onclick="changerLogIn()"/>
                                <label for="afficher">Afficher le mot de passe</label>
                            </div>
                        </div>
                        <a class="button-form"><input type="submit" class="button" values="Valider"></a>
                    </form>
                </div>
            <!-- fin du formulaire -->
            </div>

        <!-- fin du container -->
        </div>
    </body>
</html>