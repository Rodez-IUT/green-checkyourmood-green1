<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="../css/CSS_principal.css">
        <script src="https://kit.fontawesome.com/9f5b052c0b.js" crossorigin="anonymous"></script>
        <script src="../bootstrap/js/bootstrap.js"></script>
        <title>CheckYourMood - Mon compte</title>
    </head>
    <body>
        <?php date_default_timezone_set('Europe/Paris');?>
        <div class="container-fluid">
            <!-- Debut navbar -->
            <div class="row navbar">
                <!-- Logo CYM -->
                <div class="col-md-1">
                    <form action="../index.php"  method="post">
                        <input hidden name="action" value="homeForAcCount">
                        <button type="submit">
                            <img class="logo_cym" src="../images/logo_CYM.png" alt="Logo">
                        </button>
                </form>
                </div>
                <!-- Espace entre les éléments du menu et le logo -->
                <div class="col-md-4"></div>
                <!-- Debut Menu -->
                <div class="col-md-7">
                    <div class="menu">
                        <div class="menu_element">
                            <form action="../index.php">
                                <input hidden name="action" value="homeForAcCount">
                                <button type="submit" class="menu_element">
                                    <i class="fa-solid fa-house"></i> 
                                    <span>Accueil</span> 
                                </button>
                            </form>
                        </div>
                        <div class="menu_element">
                            <form action="../index.php" method="post">
                                <input hidden name="controller" value="Moods">
                                <button type="submit" class="menu_element">
                                    <i class="fa-solid fa-sun"></i>
                                    <span>Tableau de bord</span> 
                                </button>
                            </form>
                        </div>
                        <div class="menu_element">
                            <form action="../index.php" method="post">
                                <input type="hidden" name="controller" value="Moods">
                                <input type="hidden" name="action" value="moodsList">
                                <button type="submit" class="menu_element active">
                                    <span class="fa-solid fa-eye"></span>
                                    <span class="title-lvl4">Mes humeurs</span>
                                </button>
                            </form>
                        </div>
                        <div class="menu_element">
                            <form action="../index.php" method="post">
                                <input hidden name="controller" value="Account">
                                <button type="submit" class="menu_element">
                                    <i class="fa-solid fa-circle-user"></i>
                                    <span>Compte</span>
                                </button>
                            </form>
                        </div>
                        <div class="menu_element">
                            <form action="../index.php">
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

            <!-- Titre principale -->
            <div class="row">
                <div class="col-md-12 title-lvl1">
                    <span>Historique de toutes mes humeurs :</span>
                </div>
            </div>
        </div>
        <div class="container">
            <!-- Liste des humeurs -->
            <div class="row align">
                <?php
                    while (($hum = $histoHum->fetch())) { 
                ?>
                    <div class="col-md-4 align">
                        <button class="humeurElement" data-bs-toggle="modal" data-bs-target="#info<?php echo $hum["ID_Histo"]?>">
                            <img class="emojiListe" src="<?php echo $hum["Emoji"];?>" alt="Emoji">
                            <div class="humeurElementText">
                                <span class="title-lvl4"><?php echo $hum["Libelle"]?></span><br>
                                <span class="title-lvl4"><?php echo date("d/m/Y H:i", strtotime($hum["Date_Hum"]));?></span>
                            </div>
                        </button>
                    </div>
                    <!-- Modal infos complémentaires et options modifier et supprimer si possible -->
                    <div class="modal fade" id="info<?php echo $hum["ID_Histo"]?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content cadre_modal">
                                <span class="title-lvl3">Informations complémentaires</span>
                                <span class="infosComplement title-lvl4">
                                    <?php echo $hum["Informations"] == null ? "Cette humeur ne comporte pas d'informations complémentaires" : $hum["Informations"]?>
                                </span><br>
                                <?php 
                                $dateHum = new DateTime($hum["Date_Hum"]);
                                $now = new DateTime();
                                $oneDayAgo = new DateTime();
                                $dateInterval = new DateInterval("P1D");
                                $oneDayAgo->sub($dateInterval);
                                if ($dateHum <= $now && $dateHum >= $oneDayAgo) { 
                                ?>
                                    <form action="../index.php" method="post">
                                        <input type="hidden" name="controller" value="Moods">
                                        <input type="hidden" name="idHisto" value="<?php echo $hum["ID_Histo"];?>">
                                        <input type="hidden" name="page" value="<?php echo $page;?>">
                                        <div class="separateurModalHum"></div>
                                        <span class="title-lvl3">Vous pouvez modifier cette humeur</span><br><br>
                                        <span class="title-lvl4 gauche">Libellé :</span>
                                        <select name="humeurModif" class="form-select input_modif_text">
                                            <option value="">Sélectionner une humeur</option>
                                            <?php
                                                foreach($humeurs as $h) {
                                                    echo "<option value=".$h["ID_Hum"];
                                                    if ($h["ID_Hum"] == $hum["ID_Hum"]) {
                                                        echo " selected";
                                                    }
                                                    echo ">".$h["Libelle"]."</option>";
                                                }
                                            ?>
                                        </select>
                                        <br>
                                        <span class="title-lvl4 gauche">Date et heure :</span>
                                        <input class="form-control input_modif_text" type="datetime-local" name="dateHum" 
                                        value="<?php echo date("Y-m-d\TH:i", strtotime($hum["Date_Hum"]));?>" 
                                        max="<?php echo date("Y-m-d\TH:i", strtotime($hum["Date_Ajout"]));?>" 
                                        min="<?php $dateInterval = new DateInterval("P1D");
                                                $dateAjout = new DateTime($hum["Date_Ajout"]);
                                                $dateAjout->sub($dateInterval);
                                                echo $dateAjout->format("Y-m-d\TH:i");?>">
                                        <br>
                                        <span class="title-lvl4 gauche">Informations :</span>
                                        <textarea name="info" class="form-control input_modif_text" placeholder="Vous pouvez saisir des informations complémentaires ici"><?php echo $hum["Informations"]?></textarea>
                                        <br>
                                        <div class="row ">
                                            <div class="col-md-4 align">
                                                <button type=button class="button_fermer" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                            <div class="col-md-4 align">
                                                <button type=submit class="button_modif_hum" name="action" value="editMood">Modifier</button>
                                            </div>
                                            <div class="col-md-4 align">
                                            <button type=submit class="button_annuler" name="action" value="deleteMood">Supprimer</button>
                                            </div>
                                        </div>
                                    </form>
                                <?php } else { ?>
                                    <div class="align">
                                        <button type=button class="button_fermer" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                    <!-- Fin modal infos complémentaires et options modifier et supprimer si possible -->
                <?php }?>
            </div>
            <!-- Bouton de navigation -->
            <div class="row nav_moodsList">
                <?php
                    if ($page != 1) {
                ?>
                <!-- Bouton debut -->
                <form class="nav_element" action="../index.php" method="post">
                    <input type="hidden" name="controller" value="Moods">
                    <input type="hidden" name="action" value="moodsList">
                    <input type="hidden" name="page" value="1">
                    <button type="submit">
                        <i class="fa-solid fa-backward icon_nav_moodsList"></i>
                    </button>
                </form>
                <!-- Bouton retour -->
                <form class="nav_element" action="../index.php" method="post">
                    <input type="hidden" name="controller" value="Moods">
                    <input type="hidden" name="action" value="moodsList">
                    <input type="hidden" name="page" value="<?php echo $page - 1?>">
                    <button type="submit">
                        <i class="fa-solid fa-caret-left icon_nav_moodsList"></i>
                    </button>
                </form>
                <?php
                    }
                ?>
                <?php
                    if ($page > 3) {
                ?>
                <!-- 3 petits points -->
                ...
                <?php
                    }
                ?>
                <?php
                    if ($page > 2) {
                ?>
                <!-- Bouton num -->
                <form class="nav_element" action="../index.php" method="post">
                    <input type="hidden" name="controller" value="Moods">
                    <input type="hidden" name="action" value="moodsList">
                    <input type="hidden" name="page" value="<?php echo $page - 2?>">
                    <button type="submit">
                        <span class="icon_nav_moodsList"><?php echo $page -2?></span>
                    </button>
                </form>
                <?php
                    }
                ?>
                <?php
                    if ($page > 1) {
                ?>
                <!-- Bouton num -->
                <form class="nav_element" action="../index.php" method="post">
                    <input type="hidden" name="controller" value="Moods">
                    <input type="hidden" name="action" value="moodsList">
                    <input type="hidden" name="page" value="<?php echo $page - 1?>">
                    <button type="submit">
                        <span class="icon_nav_moodsList"><?php echo $page - 1?></span>
                    </button>
                </form>
                <?php
                    }
                ?>
                <!-- Bouton num active -->
                <form class="nav_element" action="../index.php" method="post">
                    <input type="hidden" name="controller" value="Moods">
                    <input type="hidden" name="action" value="moodsList">
                    <input type="hidden" name="page" value="<?php echo $page?>">
                    <button type="submit">
                        <span class="icon_nav_moodsList_active"><?php echo $page?></span>
                    </button>
                </form>
                <?php
                    if ($page + 1 <= $nbPage) {
                ?>
                <!-- Bouton num -->
                <form class="nav_element" action="../index.php" method="post">
                    <input type="hidden" name="controller" value="Moods">
                    <input type="hidden" name="action" value="moodsList">
                    <input type="hidden" name="page" value="<?php echo $page + 1?>">
                    <button type="submit">
                        <span class="icon_nav_moodsList"><?php echo $page + 1?></span>
                    </button>
                </form>
                <?php
                    }
                ?>
                <?php
                    if ($page + 2 <= $nbPage) {
                ?>
                <!-- Bouton num -->
                <form class="nav_element" action="../index.php" method="post">
                    <input type="hidden" name="controller" value="Moods">
                    <input type="hidden" name="action" value="moodsList">
                    <input type="hidden" name="page" value="<?php echo $page + 2?>">
                    <button type="submit">
                        <span class="icon_nav_moodsList"><?php echo $page + 2?></span>
                    </button>
                </form>
                <?php
                    }
                ?>
                <?php
                    if ($page + 3 <= $nbPage) {
                ?>
                <!-- 3 petits points -->
                ...
                <?php
                    }
                ?>
                <?php
                    if ($page < $nbPage) {
                ?>
                <!-- Bouton avancer -->
                <form class="nav_element" action="../index.php" method="post">
                    <input type="hidden" name="controller" value="Moods">
                    <input type="hidden" name="action" value="moodsList">
                    <input type="hidden" name="page" value="<?php echo $page + 1?>">
                    <button type="submit">
                        <i class="fa-solid fa-caret-right icon_nav_moodsList"></i>
                    </button>
                </form>
                <!-- Bouton fin -->
                <form class="nav_element" action="../index.php" method="post">
                    <input type="hidden" name="controller" value="Moods">
                    <input type="hidden" name="action" value="moodsList">
                    <input type="hidden" name="page" value="<?php echo $nbPage;?>">
                    <button type="submit">
                        <i class="fa-solid fa-forward icon_nav_moodsList"></i>
                    </button>
                </form>
                <?php
                    }
                ?>
            </div>
        </div>
    </body>
</html>