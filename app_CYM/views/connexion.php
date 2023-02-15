<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="../css/CSS_principal.css">
        <script src="https://kit.fontawesome.com/9f5b052c0b.js" crossorigin="anonymous"></script>
        <script src="../js/Main.js"></script>
        <title>CheckYourMood - Connexion</title>
    </head>
    <body>
        <div class="container-fluid">
            <!-- Debut navbar -->
            <div class="row navbar">
                <!-- Logo CYM -->
                <div class="col-md-1 col-sm-1 col-xs-1">
                    <a href="../index.php">
                        <img class="logo_cym" src="../images\logo_CYM.png" alt="Logo">
                    </a>
                </div>
                <!-- Espace entre les éléments du menu et le logo -->
                <div class="col-md-5 hidden-sm hidden-xs"></div>
                <!-- Debut Menu -->
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>
                        <a href="../index.php" class="col-md-3">
                            <div class="menu_element">
                                <i class="fa-solid fa-house"></i> 
                                <span class="navbar-title">Accueil</span> 
                            </div>
                        </a>
                        <a href="connexion.php" class="col-md-3">
                            <div class="menu_element active">
                                <i class="fa-solid fa-circle-user"></i>
                                <span class="navbar-title">Se connecter</span>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Debut Menu -->
            </div>
            <!-- Fin navbar -->
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 body-container-padding">
                    <!-- debut formulaire -->
                    <form action="../index.php" method="post">
                        <input hidden name="action" value="logInAction">
                        <input hidden name="controller" value="Connexion">
                        <h2 class="title-lvl2">Se connecter</h2><br/><br/>
                        <!-- Message d'erreur si un des champs obligatoires est vide ou incorrect -->
                        <div
                        <?php 
                        if (!isset($errLogInAccount) || !$errLogInAccount) {
                            echo " hidden";
                        }
                        ?> class="row center-form">
                            <div class="col-md-7 erreurAccount">
                                <span>L'addresse mail ou le mot de passe est incorrect !</span>
                            </div>
                        </div>  
                        <div class="row center-form">
                            <!-- champs pour adresse mail -->
                            <div class="top-form col-md-7">
                                <label class="text-form" for="Mail">Adresse Mail :</label><br/>
                                <input placeholder="antoine.dupont@gmail.com" class="formulaire1 form-control" type="text" name="Email"/><br/>
                            </div>
                            <!-- champs pour password -->
                            <div class="bottom-form col-md-7 ">
                                <label class="text-form" for="mdp">Mot de passe :</label><br/>
                                <input placeholder="••••••••••••" class="formulaire1 form-control" type="password" id="pass" name="mot_de_passe"/><br/>
                            </div>
                            <!-- champs pour afficher password -->
                            <div class="passInput col-md-6">
                                <input type="checkbox" id="check" name="valide" onclick="changer()"/>
                                <label for="afficher">Afficher le mot de passe</label>
                            </div>
                        </div>
                        <!-- boutton -->
                        <button type="submit" class="button">Valider</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>