<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="css/CSS_principal.css">
        <script src="https://kit.fontawesome.com/9f5b052c0b.js" crossorigin="anonymous"></script>
        <script src="bootstrap/js/bootstrap.js"></script>
        <title>CheckYourMood - Mon compte</title>
    </head>
    <body>
        <?php date_default_timezone_set('Europe/Paris');?>
        <div class="container-fluid">
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
                                <button type="submit" class="menu_element">
                                    <i class="fa-solid fa-house"></i> 
                                    <span>Accueil</span> 
                                </button>
                            </form>
                        </div>
                        <div class="menu_element">
                            <form action="index.php" method="post">
                                <input hidden name="controller" value="Moods">
                                <button type="submit" class="menu_element active">
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

            <!-- Titre principale -->
            <div class="row">
                <div class="col-md-12 title-lvl1">
                    <span>Mes humeurs :</span>
                </div>
            </div>

            <!-- message d'erreur si une saisie d'humeur a ete mal réalisé -->
            <div
            <?php 
            if (!isset($erreur) || !$erreur) {
                echo " hidden";
            }
            ?> class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 erreur">
                    <span>Veuillez renseigner une humeur</span>
                </div>
                <div class="col-md-1"></div>
            </div>

            <!-- bouton ajout d'une humeur -->
            <div class="row align">
                <div class="col-md-12 align">
                    <button class="button_add_mood" data-bs-toggle="modal" data-bs-target="#ajoutHumeur">
                        <span class="fa-sharp fa-solid fa-circle-plus icon_button_add"></span>
                        <div class="add_mood_separator"></div>
                        <span class="title-lvl4">Ajouter une humeur</span>
                    </button>
                </div>
            </div><br>
        </div>

        <!-- Modal ajout humeur -->
        <div class="modal fade" id="ajoutHumeur" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content cadre_modal">
                    <span class="title-lvl3">Nouvelle humeur</span>
                    <form action="index.php">
                        <input type="hidden" name="controller" value="Moods">
                        <input type="hidden" name="action" value="addMood">
                        <span class="title-lvl4">Libellé :</span>
                        <select name="humeur" class="form-select input_modif_text">
                            <option value="">Sélectionner une humeur</option>
                            <?php
                                foreach($humeurs as $hum) {
                                    echo "<option value=".$hum["ID_Hum"].">".$hum["Libelle"]."</option>";
                                }
                            ?>
                        </select>
                        <br>
                        <span class="title-lvl4">Date et heure :</span>
                        <input class="form-control input_modif_text" type="datetime-local" name="dateHum" 
                        value="<?php $now = new DateTime();
                                echo $now->format("Y-m-d\TH:i");
                                ?>" 
                        max="<?php echo $now->format("Y-m-d\TH:i");?>" 
                        min="<?php $dateInterval = new DateInterval("P1D");
                                $now->sub($dateInterval);
                                echo $now->format("Y-m-d\TH:i");?>">
                        <br>
                        <span class="title-lvl4">Informations :</span>
                        <textarea name="info" class="form-control input_modif_text" placeholder="Vous pouvez saisir des informations complémentaires ici"></textarea>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <button type=button class="button_annuler" data-bs-dismiss="modal">Annuler</button>
                            </div>
                            <div class="col-md-6 droite">
                                <input type="submit" class="button_valider" value="Valider">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Fin Modal ajout humeur -->

        <div class="container space">
            <div class="col-md-12 title-lvl3-left">
                <span>Les dernieres humeurs :</span>
            </div>
            <!-- Liste des humeurs -->
            <div class="row align">
                <?php for ($i = 1 ; ($hum = $histoHum->fetch()) && $i <= 9 ; $i++) { ?>
                    <div class="col-md-4 align">
                        <button class="humeurElement" data-bs-toggle="modal" data-bs-target="#info<?php echo $hum["ID_Histo"]?>">
                            <span class="emojiListe"><?php echo $hum["Emoji"]?></span>
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
                                    <form action="index.php" method="post">
                                        <input type="hidden" name="controller" value="Moods">
                                        <input type="hidden" name="idHisto" value="<?php echo $hum["ID_Histo"];?>">
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
        </div><br>
        
        <!-- debut visualisation -->
        <div class="container">
            <div class="col-md-12 title-lvl3-left">
                <span>Camembert :</span>
            </div>
            <div class="row">
                <div class="col-md-12 title-lvl4-left">
                    <span>Période :</span>
                </div>
                <form action="index.php">
                    <!-- Select pour visualiser camembert -->
                    <input type="hidden" name="controller" value="Moods">
                    <select name="form-search" class="col-md-2 align periode-search">
                        <option value="today" <?php if ($daySelect) { echo " selected";}?>>Aujourd'hui</option>
                        <option value="last-day" <?php if ($lastDaySelect) { echo " selected";}?>>Hier</option>
                        <option value="last-week" <?php if ($lastWeekSelect) { echo " selected";}?>>7 dernier jours</option>
                        <option value="last-month" <?php if ($lastMonthSelect) { echo " selected";}?>>30 dernier jours</option>
                    </select>
                    <!-- boutton -->
                    <button class="periode-search" type="submit">Rechercher</button>
                </form>
            </div>
        </div>

        <?php if (count($tabHum) != 0) { ?>
            <!-- Camembert -->
        <div>
            <canvas id="camenbert"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Insertion données Camembert -->
        <script type="text/javascript">
            <?php echo "var tabHum = '".implode("<>", $tabHum)."'.split('<>');";?>
            <?php echo "var tabNombre = '".implode("<>", $tabNb)."'.split('<>');";?>
            const ctx = document.getElementById('camenbert');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: [
                        <?php 
                            for ($i = 0 ; $i < count($tabHum); $i++) {
                                echo "tabHum[".$i."],";
                            }
                        ?>
                        
                    ],
                    datasets: [{
                        data: [
                            <?php 
                            for ($i = 0 ; $i < count($tabNb); $i++) {
                                echo "tabNombre[".$i."],";
                            }
                            ?>
                        ],
                        backgroundColor: [
                            '#00ff7f',
                            '#dc143c',
                            '#00bfff',
                            '#0000ff',
                            '#8b008b',
                            '#b03060',
                            '#ff0000',
                            '#ffd700',
                            '#ff00ff',
                            '#1e90ff',
                            '#eee8aa',
                            '#00ffff',
                            '#b0e0e6',
                            '#ff1493',
                            '#ee82ee',
                            '#ffb6c1',
                            '#00008b',
                            '#556b2f',
                            '#0000ff',
                            '#8b4513',
                            '#483d8b',
                            '#3cb371',
                            '#b8860b',
                            '#7fff00',
                            '#8a2be2',
                            '#ff7f50',
                            '#008b8b',
                            '#9acd32',
                            '#00bfff',
                        ],
                        hoverOffset: 4
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        </script>
        <?php } else { ?>
            <div class="col-md-12 title-lvl4-center ">
                <span class="contourNoir"> Il n'y a pas d'humeur sur cette période </span>
            </div>
            
        <?php } ?>
        
        <!-- Calendrier -->
        <div class="container">
            <div class="col-md-12 title-lvl3-left">
                <span>Calendrier :</span>
            </div>
            <div class="col-md-12 title-lvl4-left">
                <span>Période :</span>
            </div>
            <!-- input pour choisir visualisation Calendrier -->
            <form action="index.php">
                <input type="hidden" name="controller" value="Moods">
                <input type="month" name="dateSelect" class="col-md-2 align periode-search" value="<?php echo $curdate;?>">
                <button class="periode-search" type="submit">Rechercher</button>
            </form>
            <div class="col-md-12 title-lvl4-left">
                <span><?php echo $anneeCourant."/".$moisCourant?></span>
            </div>
            <!-- Debut Calendrier -->
            <div class="row space">
                <?php   for($i = 1; $i < $number+1 ; $i++) {
                            if ($i == 1 || $i == 11 || $i == 21 || $i == 31) {
                                echo'<div class=" title-lvl4-left schedule-container space">';
                            }
                                echo '<div class="schedule-card">';
                                echo '<div class="image-center">';
                                if ($emojiJour[$i - 1] != null) {
                                    echo "<img title=\"".$emojiJour[$i - 1]["Libelle"]."\" class=\"image-card\" src=\"".$emojiJour[$i - 1]["Emoji"]."\" alt=\"Emoji\">";
                                }
                                echo '</div>';
                                echo '<span>'.$i.'</span>';
                                echo '</div>';

                            if ($i == 10 || $i == 20 || $i == 30 || $i == 32) {
                                echo '</div>';
                            }
                        } ?>
            </div>
        </div>
    </body>
</html>