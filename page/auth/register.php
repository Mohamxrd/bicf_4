<?php


session_start();

$conn = new PDO('mysql:host=localhost;dbname=bicf;charset=utf8;', 'root', 'root');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$errorMsg = '';

if (isset($_POST['submit'])) {



    $username = htmlspecialchars($_POST['username']);

    $checkUsername = $conn->prepare('SELECT * FROM user WHERE username = ?');
    $checkUsername->execute([$username]);

    if ($checkUsername->rowCount() > 0) {
        // Le nom d'utilisateur existe déjà, afficher un message d'erreur
        $errorMsg = "Le nom d'utilisateur existe déjà !";
    } else {
        try {
            $nom_user = htmlspecialchars($_POST['nom_user']);
            $prenom_user = htmlspecialchars($_POST['prenom']);
            $username = htmlspecialchars($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $cpassword = password_hash($_POST['cpassword'], PASSWORD_DEFAULT);


            $actorType = $_POST['user_type'];
            $sexe_user = $_POST['user_sexe'];
            $age_user = $_POST['user_age'];
            $socialStatus_user = $_POST['user_status'];
            $entreSize = $_POST['user_comp_size'];
            $Servtype = $_POST['user_serv'];
            $orgaType = $_POST['user_orgtyp1'];
            $orgaType2 = $_POST['user_orgtyp2'];
            $comType = $_POST['user_com'];
            $menaType = $_POST['user_mena1'];
            $menaStat = $_POST['user_mena2'];
            $activSector_user = $_POST['sector_activity'];
            $indus_user = $_POST['industry'];
            $bat_user = $_POST['building_type'];
            $comm_user = $_POST['commerce_sector'];
            $serv_user = $_POST['transport_sector'];
            $pays_user = $_POST['country'];
            $tel_user = $_POST['phone'];
            $local_user = $_POST['local'];
            $adress_user = $_POST['adress_geo'];
            $email_user = $_POST['email'];
            $ActivZone_user = $_POST['proximity'];

            // $entreSize = $socialStatus_user = $age_user = $sexe_user  = $Servtype = $orgaType = $orgaType2 = $comType = $menaType = $menaStat = null;

            if ($actorType == "Personne physique") {
                $entreSize = $Servtype = $orgaType = $orgaType2 = $comType = $menaType = $menaStat = null;
            } elseif ($actorType == "Personne morale") {
                $socialStatus_user = $age_user = $sexe_user = $Servtype = $orgaType = $orgaType2 = $comType = $menaType = $menaStat = null;
            } elseif ($actorType == "Service public") {
                $entreSize = $socialStatus_user = $age_user = $sexe_user = $orgaType = $orgaType2 = $comType = $menaType = $menaStat = null;
            } elseif ($actorType == "Organisme") {
                $entreSize = $socialStatus_user = $age_user = $sexe_user = $comType = $menaType = $menaStat = null;
            } elseif ($actorType == "Comunauté") {
                $entreSize = $socialStatus_user = $age_user = $sexe_user = $Servtype = $orgaType = $orgaType2 = $menaType = $menaStat = null;
            } elseif ($actorType == "Menage") {
                $entreSize = $socialStatus_user = $age_user = $sexe_user = $Servtype = $orgaType = $orgaType2 = null;
            }



            if ($activSector_user == "Industrie") {
                $bat_user = $comm_user = $serv_user = null;
            } elseif ($activSector_user == "Construction") {
                $indus_user = $comm_user = $serv_user = null;
            } elseif ($activSector_user == "Commerce") {
                $indus_user = $bat_user = $serv_user = null;
            } elseif ($activSector_user == "Service") {
                $indus_user = $bat_user = $comm_user = null;
            } elseif ($activSector_user == "Autre") {
                $indus_user = $bat_user = $comm_user = $serv_user = null;
            }





            $insertUser = $conn->prepare('INSERT INTO user(nom_user, prenom_user, username, password, actorType, sexe_user, age_user, socialStatus_user, entreSize, Servtype, orgaType, orgaType2, comType, menaType, menaStat, activSector_user, indus_user, bat_user, comm_user, serv_user, pays_user, tel_user, local_user, adress_user, email_user, ActivZone_user, id_admin) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
            $insertUser->execute(array($nom_user, $prenom_user, $username, $password, $actorType, $sexe_user, $age_user, $socialStatus_user, $entreSize, $Servtype, $orgaType, $orgaType2, $comType, $menaType, $menaStat, $activSector_user, $indus_user, $bat_user, $comm_user, $serv_user, $pays_user, $tel_user, $local_user, $adress_user, $email_user, $ActivZone_user, null));

            header('location: login.php');
            exit();
        } catch (PDOException $e) {
            $errorMsg = "Erreur lors de l'insertion dans la base de données : " . $e->getMessage();
        }
    }
}


?>




<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Créer un compte</title>
    <link rel="stylesheet" type="text/css" href="../../css/step2.css" />



    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
</head>

<body>
    <div class="wrapper">
        <h1>Créer un compte</h1>
        <div class="header">
            <ul>
                <li class="active form_1_progessbar">
                    <div>
                        <p>1</p>
                    </div>
                </li>
                <li class="form_2_progessbar">
                    <div>
                        <p>2</p>
                    </div>
                </li>

                <li class="form_4_progessbar">
                    <div>
                        <p>3</p>
                    </div>
                </li>
                <li class="form_5_progessbar">
                    <div>
                        <p>4</p>
                    </div>
                </li>
            </ul>
        </div>
        <form action="" method="post">
            <div class="form_wrap">
                <div class="form_1 data_info">
                    <h2>Information personnel</h2>

                    <div class="form_container">
                        <div class="input_wrap">
                            <label for="nom_user">Nom (ou raison social) <span>*</span></label>
                            <input type="text" name="nom_user" class="input" id="nom_user" required />
                        </div>
                        <div class="input_wrap">
                            <label for="prenom">Prenoms (Optionnel)</label>
                            <input type="text" name="prenom" class="input" id="prenom" />
                        </div>
                        <div class="input_wrap">
                            <label for="username">Nom d'utilisateur <span>*</span></label>
                            <input type="text" name="username" class="input" id="username" required />
                        </div>
                        <div class="input_wrap">
                            <label for="password">Mot de passe <span>*</span></label>
                            <input type="password" name="password" class="input" id="password" required />
                        </div>
                        <div class="input_wrap">
                            <label for="cpassword">confirmer mot de passe <span>*</span></label>
                            <input type="password" name="cpassword" class="input" id="cpassword" required />
                        </div>
                        <div class="pass_error">Les mots de passe ne correspondent pas</div>
                        <div class="pass_lengh">Votre mot de passe doit contenir au moins 8 carractères et une majuscule
                        </div>
                        <div class="error-msg">Veillez remplire tout les champs obligatoires</div>

                        <?php

                        if (!empty($errorMsg)) {
                            echo '<div class="error-message">' . $errorMsg . '</div>';
                        }
                        ?>

                    </div>

                </div>
                <div class="form_2 data_info" style="display: none">
                    <h2>Nature d'acteur</h2>

                    <div class="form_container">
                        <div class="input_wrap">
                            <label for="user_type">Type d'acteur</label>
                            <select name="user_type" id="user_type" class="input">
                                <option selected value="Personne physique">Personne physique</option>
                                <option value="Personne morale">Personne morale</option>
                                <option value="Service public">Service public</option>
                                <option value="Organisme">Organisme</option>
                                <option value="Comunauté">Comunauté</option>
                                <option value="Menage">Menage</option>
                            </select>
                        </div>

                        <div class="input_wrap" id="user_sexe_input">
                            <label for="user_sexe">Sexe</label>
                            <select name="user_sexe" id="user_sexe" class="input">
                                <option selected value="Masculin">Masculin</option>
                                <option value="Feminin">Feminin</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_age_input">
                            <label for="user_age">Tranche d'age</label>
                            <select name="user_age" id="user_age" class="input">
                                <option selected value="Adolescent">Adolescent</option>
                                <option value="Jeune">Jeune</option>
                                <option value="3ème Age">3ème Age</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_status_input">
                            <label for="user_status">Status social</label>
                            <select name="user_status" id="user_staus" class="input">
                                <option selected value="Salarié">Salarié</option>
                                <option value="Travailleur">Travailleur</option>
                                <option value="Autonome">Autonome</option>
                                <option value="Etudiant">Etudiant</option>
                                <option value="Sans emploi">Sans emploi</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_comp_size_input" style="display: none">
                            <label for="user_comp_size">Taille d'entreprise</label>
                            <select name="user_comp_size" id="user_comp_size" class="input">
                                <option selected value="Grande entreprise">Grande entreprise</option>
                                <option value="Moyenne entreprise">Moyenne entreprise</option>
                                <option value="Petite entreprise">Petite entreprise</option>
                                <option value="Mini entreprise">Mini entreprise</option>
                                <option value="Micro entreprise">Micro entreprise</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_serv_input" style="display: none">
                            <label for="user_serv">Type de service</label>
                            <select name="user_serv" id="user_serv" class="input">
                                <option selected value="Service ministeriel">Service ministeriel</option>
                                <option value="dministration publique">Administration publique</option>
                                <option value="Collectivité territoriale">Collectivité territoriale</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_orgtyp1_input" style="display: none">
                            <label for="user_orgtyp1">Type d'organimes</label>
                            <select name="user_orgtyp1" id="user_orgtyp1" class="input">
                                <option selected value="National">National</option>
                                <option value="International">International</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_orgtyp2_input" style="display: none">
                            <label for="user_orgtyp2">Choisir</label>
                            <select name="user_orgtyp2" id="user_orgtyp2" class="input">
                                <option selected value="ONG">ONG</option>
                                <option value="Institution">Institution</option>
                                <option value="Programme">Programme</option>
                                <option value="Projet">Projet</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_com_input" style="display: none">
                            <label for="user_com">Type de communauté</label>
                            <select name="user_com" id="user_com" class="input">
                                <option selected value="Localité">Localité</option>
                                <option value="Communauté">Communauté</option>
                                <option value="Syndicat">Syndicat</option>
                                <option value="Mutuelle">Mutuelle</option>
                                <option value="Association">Association</option>
                                <option value="Club">Club</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_mena1_input" style="display: none">
                            <label for="user_mena1">Type de menage</label>
                            <select name="user_mena1" id="user_mena1" class="input">
                                <option selected value="Urbain">Urbain</option>
                                <option value="Rural">Rural</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_mena2_input" style="display: none">
                            <label for="user_mena2">Status</label>
                            <select name="user_mena2" id="user_mena2" class="input">
                                <option selected value="Salarié">Salarié</option>
                                <option value="Entreprise">Entreprise</option>
                                <option value="Commerçant">Commerçant</option>
                                <option value="Producteur agricole">Producteur agricole</option>
                                <option value="Artisant">Artisant</option>
                                <option value="Ouvrier">Ouvrier</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                    </div>

                </div>


                <div class="form_4 data_info" style="display: none">
                    <h2>Secteur d'activité</h2>

                    <div class="form_container">
                        <div class="input_wrap" id="sector_activity_selector">
                            <label for="sector_activity">Choisissez votre secteur d'activité</label>
                            <select name="sector_activity" id="sector_activity" class="input">
                                <option selected value="Industrie">Industrie</option>
                                <option value="Construction">Construction</option>
                                <option value="Commerce">Commerce</option>
                                <option value="Service">Service</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>

                        <div class="input_wrap" id="industry_selector">
                            <label for="industry">Choisissez votre industrie</label>
                            <select name="industry" id="industry" class="input">
                                <option selected value="Alimentaires">Alimentaires</option>
                                <option value="Boissons">Boissons</option>
                                <option value="Tabac">Tabac</option>
                                <option value="Bois">Bois</option>
                                <option value="Papier">Papier</option>
                                <option value="Imprimerie">Imprimerie</option>
                                <option value="Chimique">Chimique</option>
                                <option value="Pharmaceutique">Pharmaceutique</option>
                                <option value="Caoutchouc et plastique">Caoutchouc et plastique</option>
                                <option value="Produits non métalliques">Produits non métalliques</option>
                                <option value="Métallurgie et produits métalliques">
                                    Métallurgie et produits métalliques
                                </option>
                                <option value="Machines et équipements">Machines et équipements</option>
                                <option value="Matériels de transport">Matériels de transport</option>
                                <option value="Réparation et installation de machines et d'équipements">
                                    Réparation et installation de machines et d'équipements
                                </option>
                                <option value="Distribution d'électricité">Distribution d'électricité</option>
                                <option value="istribution de gaz">Distribution de gaz</option>
                            </select>
                        </div>

                        <div class="input_wrap" id="building_type_input">
                            <label for="building_type">Choisissez le type de bâtiment</label>
                            <select name="building_type" id="building_type" class="input">
                                <option selected value="Habitation">Habitation</option>
                                <option value="Usine">Usine</option>
                                <option value="Pont & Chaussée">Pont & Chaussée</option>
                            </select>
                        </div>

                        <div class="input_wrap" id="commerce_sector_selector">
                            <label for="commerce_sector">Choisissez votre secteur d'activité</label>
                            <select name="commerce_sector" id="commerce_sector" class="input">
                                <option selected value="Commerce">Commerce</option>
                                <option value="Réparation d'automobiles et de motocycles">
                                    Réparation d'automobiles et de motocycles
                                </option>
                            </select>
                        </div>

                        <div class="input_wrap" id="transport_sector_selector">
                            <label for="transport_sector">Choisissez votre secteur d'activité</label>
                            <select name="transport_sector" id="transport_sector" class="input">
                                <option selected value="Transports et entreposage">
                                    Transports et entreposage
                                </option>
                                <option value="Hébergement et restauration">Hébergement et restauration</option>
                                <option value="Activités financières et d'assurance">
                                    Activités financières et d'assurance
                                </option>
                                <option value="Activités immobilières">Activités immobilières</option>
                                <option value="Service juridiques">Service juridiques</option>
                                <option value="Service comptables">Service comptables</option>
                                <option value="Service de gestion">Service de gestion</option>
                                <option value="Service d'architecture">Service d'architecture</option>
                                <option value="Service d'ingénierie">Service d'ingénierie</option>
                                <option value="Service de contrôle et d'analyses techniques">
                                    Service de contrôle et d'analyses techniques
                                </option>
                                <option value="Autres activités spécialisées, scientifiques et techniques">
                                    Autres activités spécialisées, scientifiques et techniques
                                </option>
                                <option value="Services administratifs">Services administratifs</option>
                                <option value="Service de soutien">Service de soutien</option>
                                <option value="Administration publique">Administration publique</option>
                                <option value="Enseignement">Enseignement</option>
                                <option value="Service santé humaine">Service santé humaine</option>
                                <option value="Arts, spectacles et activités récréatives">
                                    Arts, spectacles et activités récréatives
                                </option>
                                <option value="Autres activités de services">Autres activités de services</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="form_5 data_info" style="display: none">
                    <h2>Contact</h2>

                    <div class="form_container">
                        <div class="input_wrap" id="country_selector">
                            <label for="country">Choisissez un pays</label>
                            <select name="country" id="country" class="input"></select>
                        </div>

                        <div class="input_wrap" style="width: 500px; max-width: 100%; margin: 0 auto 20px">
                            <label for="phone">Téléphone</label>
                            <div>
                                <input type="tel" name="phone" class="input" id="phone"
                                    placeholder="Numéro de téléphone" required />
                            </div>
                        </div>
                        <div class="input_wrap">
                            <label for="local">Localité</label>
                            <input type="text" name="local" class="input" id="local" required />
                        </div>
                        <div class="input_wrap">
                            <label for="adress_geo">Adress geographique</label>
                            <input type="text" name="adress_geo" class="input" id="adress_geo" required />
                        </div>
                        <div class="input_wrap">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="input" id="email" required />
                        </div>
                        <div class="input_wrap">
                            <label for="proximity">Zone d'activité</label>
                            <select name="proximity" id="proximity" class="input">
                                <option selected value="Proximité">Proximité</option>
                                <option value="Locale">Locale</option>
                                <option value="Nationale">Nationale</option>
                                <option value="Sous Régionale">Sous Régionale</option>
                                <option value="Continentale">Continentale</option>
                                <option value="Internationale">Internationale</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="btns_wrap">
                <div class="common_btns form_1_btns">
                    <button type="button" class="btn_next">
                        Suivant
                        <span class="icon"><ion-icon name="arrow-forward-sharp"></ion-icon></span>
                    </button>
                </div>
                <div class="common_btns form_2_btns" style="display: none">
                    <button type="button" class="btn_back">
                        <span class="icon"><ion-icon name="arrow-back-sharp"></ion-icon></span>Retour
                    </button>
                    <button type="button" class="btn_next">
                        Suivant
                        <span class="icon"><ion-icon name="arrow-forward-sharp"></ion-icon></span>
                    </button>
                </div>
                <div class="common_btns form_3_btns" style="display: none">
                    <button type="button" class="btn_back">
                        <span class="icon"><ion-icon name="arrow-back-sharp"></ion-icon></span>Retour
                    </button>
                    <button type="button" class="btn_next">
                        Suivant
                        <span class="icon"><ion-icon name="arrow-forward-sharp"></ion-icon></span>
                    </button>
                </div>
                <div class="common_btns form_4_btns" style="display: none">
                    <button type="button" class="btn_back">
                        <span class="icon"><ion-icon name="arrow-back-sharp"></ion-icon></span>Retour
                    </button>
                    <button type="button" class="btn_next">
                        Suivant
                        <span class="icon"><ion-icon name="arrow-forward-sharp"></ion-icon></span>
                    </button>
                </div>
                <div class="common_btns form_5_btns" style="display: none">
                    <button type="button" class="btn_back">
                        <span class="icon"><ion-icon name="arrow-back-sharp"></ion-icon></span>Retour
                    </button>

                    <button type="submit" class="btn_done" name="submit">Terminer</button>
                </div>
            </div>
        </form>


        <div class="text-center">
            <span class="txt2"> Vous avec deja un compte ? </span>
            <a href="login.php">Se connecter</a>
        </div>
    </div>

    <div class="modal_wrapper">
        <div class="shadow"></div>
        <div class="success_wrap">
            <span class="modal_icon"><ion-icon name="checkmark-sharp"></ion-icon></span>
            <p>Votre compte a été créer avec succès !</p>
        </div>
    </div>

    <script type="text/javascript" src="../../js/step.js"></script>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


</body>

</html>