<?php $idCompte = $compte["ID_Compte"]; ?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="css/CSS_principal.css">
        <script src="https://kit.fontawesome.com/9f5b052c0b.js" crossorigin="anonymous"></script>
        <script src="bootstrap/js/bootstrap.js"></script>
        <script src="js/Main.js"></script>
        <title>CheckYourMood - Mon compte</title>
    </head>
    <body>
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
                                <button type="submit" class="menu_element active">
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

            <!-- Infos du compte -->            
            <!-- Titre principale -->
            <div class="row">
                <div class="col-md-12 title-lvl1">
                    <span>Bonjour <?php echo $compte["Prenom"]." ".$compte["Nom"];?></span>
                </div>
            </div>

            <!-- Titre secondaire -->
            <div class="row center-form">
                <div class="col-md-10 title-lvl3">
                    <span>Informations personnelles :</span>
                </div>
            </div>

            <!-- Message d'erreur si il y a des erreurs -->
            <!-- Message d'erreur si un champs est mal renseigné lors de la modification du nom -->
            <div
            <?php 
            if (!isset($errModifNom) || !$errModifNom) {
                echo " hidden";
            }
            ?> class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 erreur">
                    <span>Veuillez renseigner un nom pour le modifier</span>
                </div>
                <div class="col-md-1"></div>
            </div>

            <!-- Message d'erreur si un champs est mal renseigné lors de la modification du prénom -->
            <div
            <?php 
            if (!isset($errModifPrenom) || !$errModifPrenom) {
                echo " hidden";
            }
            ?> class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 erreur">
                    <span>Veuillez renseigner un prénom pour le modifier</span>
                </div>
                <div class="col-md-1"></div>
            </div>

            <!-- Message d'erreur si un champs est mal renseigné lors de la modification de l'email -->
            <div
            <?php 
            if (!isset($errModifEmail) || !$errModifEmail) {
                echo " hidden";
            }
            ?> class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 erreur">
                    <span>Cet email est invalide ou déja pris</span>
                </div>
                <div class="col-md-1"></div>
            </div>

            <!-- Message d'erreur si un champs est mal renseigné lors de la modification du mot de passe -->
            <div
            <?php 
            if (!isset($errModifMdp) || !$errModifMdp) {
                echo " hidden";
            }
            ?> class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 erreur">
                    <span>Le nouveau mot de passe doit contenir au moins 8 caractères et les deux mots de passe renseignés doivent être identique</span>
                </div>
                <div class="col-md-1"></div>
            </div>

            <!-- Message d'erreur si l'ancien mot de passe renseigné est faux lors de la modification du mot de passe -->
            <div
            <?php 
            if (!isset($errMdp) || !$errMdp) {
                echo " hidden";
            }
            ?> class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 erreur">
                    <span>L'ancien mot de passe est incorrect</span>
                </div>
                <div class="col-md-1"></div>
            </div>

            <!-- Message d'erreur si un champs est mal renseigné lors de la modification du genre -->
            <div
            <?php 
            if (!isset($errModifGenre) || !$errModifGenre) {
                echo " hidden";
            }
            ?> class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 erreur">
                    <span>Veuillez renseigner un genre pour le modifier</span>
                </div>
                <div class="col-md-1"></div>
            </div>

            <!-- Info Nom -->
            <div class="row center-form">
                <div class="col-md-8 info_border">
                    <div class="info_container">
                        <!-- Info -->
                        <div>
                            <span class="info_name">Nom :</span></br>
                            <span class="info"><?php echo $compte["Nom"];?></span>
                        </div>
                        <!-- Bouton modifier -->
                            <button class="button_modifier" data-bs-toggle="modal" data-bs-target="#modifNom">
                                Modifier
                            </button>
                    </div>
                </div>
            </div><br>

            <!-- Info Prenom -->
            <div class="row center-form">
                <div class="col-md-8 info_border">
                    <div class="info_container">
                        <!-- Info -->
                        <div>
                            <span class="info_name">Prénom :</span></br>
                            <span class="info"><?php echo $compte["Prenom"];?></span>
                        </div>
                        <!-- Bouton modifier -->
                        <button class="button_modifier" data-bs-toggle="modal" data-bs-target="#modifPrenom">
                            Modifier
                        </button>
                    </div>
                </div>
            </div><br>

            <!-- Info Email -->
            <div class="row center-form">
                <div class="col-md-8 info_border">
                    <div class="info_container">
                        <!-- Info -->
                        <div>
                            <span class="info_name">Email :</span></br>
                            <span class="info"><?php echo $compte["Email"];?></span>
                        </div>
                        <!-- Bouton modifier -->
                            <button class="button_modifier" data-bs-toggle="modal" data-bs-target="#modifEmail">Modifier</button>
                    </div>
                </div>
            </div><br>

            <!-- Info Mot de passe -->
            <div class="row center-form">
                <div class="col-md-8 info_border">
                    <div class="info_container">
                        <!-- Info -->
                        <div>
                            <span class="info_name">Mot de passe :</span></br>
                            <span class="info">••••••••</span>
                        </div>
                        <!-- Bouton modifier -->
                            <button class="button_modifier" data-bs-toggle="modal" data-bs-target="#modifMdp">Modifier</button>
                    </div>
                </div>
            </div><br>

            <!-- Info Date de naissance -->
            <div class="row center-form">
                <div class="col-md-8 info_border">
                    <div class="info_container">
                        <!-- Info -->
                        <div>
                            <span class="info_name">Date de naissance :</span></br>
                            <span class="info"><?php 
                            if ($compte["Date_de_naissance"] == null) {
                                echo "Non défini";
                            } else {
                                echo date("d/m/Y", strtotime($compte["Date_de_naissance"]));
                            }
                            ?></span>
                        </div>
                        <!-- Bouton modifier -->
                            <button class="button_modifier" data-bs-toggle="modal" data-bs-target="#modifDateNaissance">Modifier</button>
                    </div>
                </div>
            </div><br>

            <!-- Info Genre -->
            <div class="row center-form">
                <div class="col-md-8 info_border">
                    <div class="info_container">
                        <!-- Info -->
                        <div>
                            <span class="info_name">Genre :</span></br>
                            <span class="info"><?php echo $compte["Genre"];?></span>
                        </div>
                        <!-- Bouton modifier -->
                            <button class="button_modifier" data-bs-toggle="modal" data-bs-target="#modifGenre">Modifier</button>
                    </div>
                </div>
            </div><br>

            <!-- Boutton désinscription -->
            <div class="row">
                <div class="col-md-12 desinscrire">
                    <button class="button_desinscrire" data-bs-toggle="modal" data-bs-target="#desinscription">Se désinscrire</button>
                </div>
            </div><br>
        </div>

        <!-- Modal modifier nom -->
        <div class="modal fade" id="modifNom" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content cadre_modal">
                    <span class="title-lvl3">Changez votre nom :</span>
                    <div class="cadre_modif">
                        <form action="index.php" method="post">
                            <input hidden name="controller" value="Account">
                            <input hidden name="action" value="updateLastNameById">
                            <span class="title-lvl4">Renseigner le nouveau nom</span><br><br>
                            <input type="text" name="modifNom" class="input_modif_text form-control" placeholder="Ex : Dupond"/><br>
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
        </div>
        <!-- Fin Modal modifier nom -->

        <!-- Modal modifier prénom -->
        <div class="modal fade" id="modifPrenom" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content cadre_modal">
                    <span class="title-lvl3">Changez votre prénom :</span>
                    <div class="cadre_modif">
                        <form action="index.php" method="post">
                            <input hidden name="controller" value="Account">
                            <input hidden name="action" value="updateFirstNameById">
                            <span class="title-lvl4">Renseigner le nouveau prénom</span><br><br>
                            <input type="text" name="modifPrenom" class="input_modif_text form-control" placeholder="Ex : Paul"/><br>
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
        </div>
        <!-- Fin Modal modifier prénom -->

        <!-- Modal modifier email -->
        <div class="modal fade" id="modifEmail" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content cadre_modal">
                    <span class="title-lvl3">Changez votre email :</span>
                    <div class="cadre_modif">
                        <form action="index.php" method="post">
                            <input hidden name="controller" value="Account">
                            <input hidden name="action" value="updateEmailById">
                            <span class="title-lvl4">Renseigner le nouveau email</span><br><br>
                            <input type="text" name="modifEmail" class="input_modif_text form-control" placeholder="Ex : antoine.dupont@gmail.com"/><br>
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
        </div>
        <!-- Fin Modal modifier email -->

        <!-- Modal modifier mot de passe -->
        <div class="modal fade" id="modifMdp" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content cadre_modal">
                    <span class="title-lvl3">Changez votre mot de passe :</span>
                    <div class="cadre_modif">
                        <form action="index.php" method="post">
                            <input hidden name="controller" value="Account">
                            <input hidden name="action" value="updateMDPById">
                            <span class="title-lvl4">Renseigner l'ancien mot de passe</span><br><br>
                            <input type="password" id="pass" name="oldMdp" class="input_modif_text form-control" placeholder="••••••••••••"/><br>
                            <span class="title-lvl4">Renseigner le nouveau mot de passe</span><br><br>
                            <input type="password" id="pass1" name="modifMdp" class="input_modif_text form-control" placeholder="••••••••••••"/><br>
                            <span class="title-lvl4">Confirmer le nouveau mot de passe</span><br><br>
                            <input type="password" id="pass2" name="confirmModifMdp" class="input_modif_text form-control" placeholder="••••••••••••"/><br>
                            <div class="row center-form">
                                <div class="passInput col-md-6">
                                    <input class="center-form" type="checkbox" id="check" name="valide" onclick="changerCompte()"/>
                                    <label for="afficher">Afficher le mot de passe</label>
                                </div>
                            </div>
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
        </div>
        <!-- Fin Modal modifier mot de passe -->

        <!-- Modal modifier date de naissance -->
        <div class="modal fade" id="modifDateNaissance" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content cadre_modal">
                    <span class="title-lvl3">Changez votre date de naissance :</span>
                    <div class="cadre_modif">
                        <form action="index.php" method="post">
                            <input hidden name="controller" value="Account">
                            <input hidden name="action" value="updateDateNaissanceById">
                            <span class="title-lvl4">Renseigner la nouvelle date de naissance</span><br><br>
                            <input type="date" min="1900-01-01"
                            <?php
                                echo " max=\"".date("Y-m-d")."\"";
                            ?>
                            name="modifDateNaissance" class="input_modif_text form-control"/><br>
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
        </div>
        <!-- Fin Modal modifier date de naissance -->

        <!-- Modal modifier genre -->
        <div class="modal fade" id="modifGenre" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content cadre_modal">
                    <span class="title-lvl3">Changez votre genre :</span>
                    <div class="cadre_modif">
                        <form action="index.php" method="post">
                            <input hidden name="controller" value="Account">
                            <input hidden name="action" value="updateGenreById">
                            <span class="title-lvl4">Renseigner le nouveau genre</span><br><br>
                            <select name="modifGenre" class="input_modif_text form-select">
                                <option value="">Sélectionner un genre</option>
                                <?php
                                    while ($genre = $genres->fetch()) {
                                        echo "<option value=\"".$genre["ID_Gen"]."\">".$genre["Nom"]."</option>";
                                    }
                                ?>
                                <option value="Aucun">Aucun</option>
                            </select><br>
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
        </div>
        <!-- Fin Modal modifier genre -->

        <!-- Modal désinscription -->
        <div class="modal fade" id="desinscription" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content cadre_modal">
                    <span class="title-lvl3">Désinscription :</span>
                    <div class="cadre_modif">
                        <form action="index.php" method="post">
                            <input hidden name="controller" value="Account">
                            <input hidden name="action" value="deleteAccountById">
                            <span class="title-lvl4">Souhaitez-vous vraiment vous désinscrire ?</span><br>
                            <span class="title-lvl4">Attention : toutes vos données seront supprimées.</span><br><br>                            
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <button type=button class="button_annuler" data-bs-dismiss="modal">Annuler</button>
                                </div>
                                <div class="col-md-6 droite">
                                    <input type="submit" name="desinscription" class="button_valider" value="Confirmer">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal désinscription -->
    </body>
</html>