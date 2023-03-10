<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="css/CSS_principal.css">
        <script src="https://kit.fontawesome.com/9f5b052c0b.js" crossorigin="anonymous"></script>
        <title>CheckYourMood - Accueil</title>
    </head>
    <body>
        <!-- début du container-fluid (navbar) -->
        <div class="container-fluid d-flex flex-column">

            <!-- Debut navbar -->
            <div class="row navbar">
                <!-- Logo CYM -->
                <div class="col-md-1">
                    <form action="index.php"  method="post">
                        <input hidden name="action" value="homeForAcCount">
                        <button type="submit">
                            <img class="logo_cym" src="images/logo_CYM.png" alt="Logo">
                        </button>
                </form>
                </div>
                <!-- Espace entre les éléments du menu et le logo -->
                <div class="col-md-4"></div>
                <!-- Debut Menu -->
                <div class="col-md-7">
                    <div class="menu">
                        <div class="menu_element">
                            <form action="index.php">
                                <input hidden name="action" value="homeForAcCount">
                                <button type="submit" class="menu_element active">
                                    <i class="fa-solid fa-house"></i> 
                                    <span>Accueil</span> 
                                </button>
                            </form>
                        </div>
                        <div class="menu_element">
                            <form action="index.php" method="post">
                                <input hidden name="controller" value="Moods">
                                <button type="submit" class="menu_element">
                                    <i class="fa-solid fa-sun"></i>
                                    <span>Tableau de bord</span> 
                                </button>
                            </form>
                        </div>
                        <div class="menu_element">
                            <form action="index.php" method="post">
                                <input type="hidden" name="controller" value="Moods">
                                <input type="hidden" name="action" value="moodsList">
                                <button type="submit" class="menu_element">
                                    <span class="fa-solid fa-eye"></span>
                                    <span class="title-lvl4">Mes humeurs</span>
                                </button>
                            </form>
                        </div>
                        <div class="menu_element">
                            <form action="index.php" method="post">
                                <input hidden name="controller" value="Account">
                                <button type="submit" class="menu_element">
                                    <i class="fa-solid fa-circle-user"></i>
                                    <span>Compte</span>
                                </button>
                            </form>
                        </div>
                        <div class="menu_element">
                            <form action="index.php">
                                <button type="submit" class="menu_element">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                    <span>Déconnection</span>
                                </button>
                            </form>
                        </div>
                    </div>
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
                <div class="body-container col-md-12">
                    <h1 class="title-lvl2"> CheckYourMood c'est quoi ?</h1>
                    <p> CheckYourMood est un site web permettant de rentrer des humeurs tout au long de la journée. </p>
                    <h2 class="title-lvl2"> Pourquoi </h2>
                    <p> Pour pouvoir étudier les humeurs qui ressortent le plus chaques jours, chaques semaines </p>
                </div>
            </div>
        </div>
        <!-- fin du container -->
        </div>
    </body>
</html>