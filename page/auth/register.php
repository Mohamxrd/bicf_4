<?php

@include '../config.php';



if (isset($_POST['submit'])) {

    $nom_user = $_POST['nom_user'];
    $prenom_user = $_POST['prenom'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
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
    $user_type = $_POST['account_type'];
    $actorStatu_user = $_POST['actor_type'];
    $budget_user = $_POST['actor_budg'];
    $agentType_user = $_POST['agent_account'];
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

    $select = "SELECT * FROM user WHERE username = '$username' && password = '$password' ";

    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {

        $error[] = "Ce nom d'utlisateur exite déja !";
    } else {
        if ($password != $cpassword) {
            $error[] = "Les deux mot de passes de correspondent pas !";
        } else {
            $insert = "INSERT INTO user (nom_user, prenom_user, username, password, actorType, sexe_user, age_user, socialStatus_user, entreSize, Servtype, orgaType, orgaType2, comType,  menaType, menaStat, user_type, actorStatu_user, budget_user, agentType_user, activSector_user, indus_user, bat_user, comm_user, serv_user, pays_user, tel_user, local_user, adress_user, email_user, ActivZone_user) 
            VALUES ('$nom_user', '$prenom_user', '$username', '$password', '$actorType', '$sexe_user', '$age_user', '$socialStatus_user', '$entreSize', '$Servtype', '$orgaType', '$orgaType2', '$comType',  '$menaType', '$menaStat', '$user_type', '$actorStatu_user', '$budget_user', '$agentType_user', '$activSector_user', '$indus_user', '$bat_user', '$comm_user', '$serv_user', '$pays_user', '$tel_user', '$local_user', '$adress_user', '$email_user', '$ActivZone_user')";
            mysqli_query($conn, $insert);
            header('location:login.php');
        };
    }
};


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
                <li class="form_3_progessbar">
                    <div>
                        <p>3</p>
                    </div>
                </li>
                <li class="form_4_progessbar">
                    <div>
                        <p>4</p>
                    </div>
                </li>
                <li class="form_5_progessbar">
                    <div>
                        <p>5</p>
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
                        <div class="pass_lengh">Votre mot de passe doit contenir au moins 8 carractères et une majuscule</div>
                        <div class="error_msg">Veillez remplire tout les champs obligatoires</div>

                    </div>

                </div>
                <div class="form_2 data_info" style="display: none">
                    <h2>Nature d'acteur</h2>

                    <div class="form_container">
                        <div class="input_wrap">
                            <label for="user_type">Type d'acteur</label>
                            <select name="user_type" id="user_type" class="input">
                                <option selected value="option1">Personne physique</option>
                                <option value="option2">Personne morale</option>
                                <option value="option3">Service public</option>
                                <option value="option4">Organisme</option>
                                <option value="option5">Comunauté</option>
                                <option value="option6">Menage</option>
                            </select>
                        </div>

                        <div class="input_wrap" id="user_sexe_input">
                            <label for="user_sexe">Sexe</label>
                            <select name="user_sexe" id="user_type" class="input">
                                <option selected value="option1">Masculin</option>
                                <option value="option2">Feminin</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_age_input">
                            <label for="user_age">Tranche d'age</label>
                            <select name="user_age" id="user_age" class="input">
                                <option selected value="option1">Adolescent</option>
                                <option value="option2">Jeune</option>
                                <option value="option3">3ème Age</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_status_input">
                            <label for="user_status">Status social</label>
                            <select name="user_status" id="user_staus" class="input">
                                <option selected value="option1">Salarié</option>
                                <option value="option2">Travailleur</option>
                                <option value="option3">Autonome</option>
                                <option value="option4">Etudiant</option>
                                <option value="option5">Sans emploi</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_comp_size_input" style="display: none">
                            <label for="user_comp_size">Taille d'entreprise</label>
                            <select name="user_comp_size" id="user_comp_size" class="input">
                                <option selected value="option1">Grande entreprise</option>
                                <option value="option2">Moyenne entreprise</option>
                                <option value="option3">Petite entreprise</option>
                                <option value="option4">Mini entreprise</option>
                                <option value="option5">Micro entreprise</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_serv_input" style="display: none">
                            <label for="user_serv">Type de service</label>
                            <select name="user_serv" id="user_serv" class="input">
                                <option selected value="option1">Service ministeriel</option>
                                <option value="option2">Administration publique</option>
                                <option value="option3">Collectivité territoriale</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_orgtyp1_input" style="display: none">
                            <label for="user_orgtyp1">Type d'organimes</label>
                            <select name="user_orgtyp1" id="user_orgtyp1" class="input">
                                <option selected value="option1">National</option>
                                <option value="option2">International</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_orgtyp2_input" style="display: none">
                            <label for="user_orgtyp2">Choisir</label>
                            <select name="user_orgtyp2" id="user_orgtyp2" class="input">
                                <option selected value="option1">ONG</option>
                                <option value="option2">Institution</option>
                                <option value="option3">Programme</option>
                                <option value="option4">Projet</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_com_input" style="display: none">
                            <label for="user_com">Type de communauté</label>
                            <select name="user_com" id="user_com" class="input">
                                <option selected value="option1">Localité</option>
                                <option value="option2">Communauté</option>
                                <option value="option3">Syndicat</option>
                                <option value="option4">Mutuelle</option>
                                <option value="option5">Association</option>
                                <option value="option6">Club</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_mena1_input" style="display: none">
                            <label for="user_mena1">Type de menage</label>
                            <select name="user_mena1" id="user_mena1" class="input">
                                <option selected value="option1">Urbain</option>
                                <option value="option2">Rural</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="user_mena2_input" style="display: none">
                            <label for="user_mena2">Status</label>
                            <select name="user_mena2" id="user_mena2" class="input">
                                <option selected value="option1">Salarié</option>
                                <option value="option2">Entreprise</option>
                                <option value="option3">Commerçant</option>
                                <option value="option4">Producteur agricole</option>
                                <option value="option5">Artisant</option>
                                <option value="option6">Ouvrier</option>
                                <option value="option7">Autre</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="form_3 data_info" style="display: none">
                    <h2>Type de compte</h2>

                    <div class="form_container">
                        <div class="input_wrap">
                            <label for="account_type">Type d'acteur</label>
                            <select name="account_type" id="account_type" class="input">
                                <option selected value="option1">Demandeur</option>
                                <option value="option2">Fournisseur</option>
                                <option value="option3">Livreur</option>
                                <option value="option4">investisseur</option>
                                <option value="option5">Agent</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="actor_type_input">
                            <label for="actor_type">Status acteur</label>
                            <select name="actor_type" id="actor_type" class="input">
                                <option selected value="option1">Facbricant</option>
                                <option value="option2">Producteur</option>
                                <option value="option3">Importateur</option>
                                <option value="option4">Grossiste</option>
                                <option value="option5">Semi-grossiste</option>
                                <option value="option6">Detaillant</option>
                            </select>
                        </div>

                        <div class="input_wrap" id="actor_type_input2">
                            <label for="actor_type2">Status acteur</label>
                            <select name="actor_type2" id="actor_type2" class="input">
                                <option selected value="option1">Importateur</option>
                                <option value="option2">Grossiste</option>
                                <option value="option3">Semi-grossiste</option>
                                <option value="option4">Detaillant</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="actor_budg_input">
                            <label for="actor_budg">Budget (Fcfa)</label>
                            <select name="actor_budg" id="actor_bidg" class="input">
                                <option selected value="option1">10 000 - 100 000</option>
                                <option value="option2">101 000 - 1 000 000</option>
                                <option value="option3">1 001 000 - 10 000 000</option>
                                <option value="option4">10 001 000 - 100 000 000</option>
                                <option value="option5">100 001 000 - 1 Milliard</option>
                                <option value="option6">1 Milliard et plus</option>
                            </select>
                        </div>
                        <div class="input_wrap" id="agent_account_input">
                            <label for="agent_account">Type d'Agent</label>
                            <select name="agent_account" id="agent_account" class="input">
                                <option selected value="option1">Commercial</option>
                                <option value="option2">Superviseur principale</option>
                                <option value="option3">Superviseur generale</option>
                                <option value="option4">Fondé de pouvoir</option>
                                <option value="option5">Super adminitrateur</option>
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
                                <option selected value="option1">Industrie</option>
                                <option value="option2">Construction</option>
                                <option value="option3">Commerce</option>
                                <option value="option4">Service</option>
                                <option value="option5">Autre</option>
                            </select>
                        </div>

                        <div class="input_wrap" id="industry_selector">
                            <label for="industry">Choisissez votre industrie</label>
                            <select name="industry" id="industry" class="input">
                                <option selected value="option1">Alimentaires</option>
                                <option value="option2">Boissons</option>
                                <option value="option3">Tabac</option>
                                <option value="option4">Bois</option>
                                <option value="option5">Papier</option>
                                <option value="option6">Imprimerie</option>
                                <option value="option7">Chimique</option>
                                <option value="option8">Pharmaceutique</option>
                                <option value="option9">Caoutchouc et plastique</option>
                                <option value="option10">Produits non métalliques</option>
                                <option value="option11">
                                    Métallurgie et produits métalliques
                                </option>
                                <option value="option12">Machines et équipements</option>
                                <option value="option13">Matériels de transport</option>
                                <option value="option14">
                                    Réparation et installation de machines et d'équipements
                                </option>
                                <option value="option15">Distribution d'électricité</option>
                                <option value="option16">Distribution de gaz</option>
                            </select>
                        </div>

                        <div class="input_wrap" id="building_type_input">
                            <label for="building_type">Choisissez le type de bâtiment</label>
                            <select name="building_type" id="building_type" class="input">
                                <option selected value="option1">Habitation</option>
                                <option value="option2">Usine</option>
                                <option value="option3">Pont & Chaussée</option>
                            </select>
                        </div>

                        <div class="input_wrap" id="commerce_sector_selector">
                            <label for="commerce_sector">Choisissez votre secteur d'activité</label>
                            <select name="commerce_sector" id="commerce_sector" class="input">
                                <option selected value="option1">Commerce</option>
                                <option value="option2">
                                    Réparation d'automobiles et de motocycles
                                </option>
                            </select>
                        </div>

                        <div class="input_wrap" id="transport_sector_selector">
                            <label for="transport_sector">Choisissez votre secteur d'activité</label>
                            <select name="transport_sector" id="transport_sector" class="input">
                                <option selected value="option1">
                                    Transports et entreposage
                                </option>
                                <option value="option2">Hébergement et restauration</option>
                                <option value="option3">
                                    Activités financières et d'assurance
                                </option>
                                <option value="option4">Activités immobilières</option>
                                <option value="option5">Service juridiques</option>
                                <option value="option6">Service comptables</option>
                                <option value="option7">Service de gestion</option>
                                <option value="option8">Service d'architecture</option>
                                <option value="option9">Service d'ingénierie</option>
                                <option value="option10">
                                    Service de contrôle et d'analyses techniques
                                </option>
                                <option value="option11">
                                    Autres activités spécialisées, scientifiques et techniques
                                </option>
                                <option value="option12">Services administratifs</option>
                                <option value="option13">Service de soutien</option>
                                <option value="option14">Administration publique</option>
                                <option value="option15">Enseignement</option>
                                <option value="option16">Service santé humaine</option>
                                <option value="option17">
                                    Arts, spectacles et activités récréatives
                                </option>
                                <option value="option18">Autres activités de services</option>
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
                                <input type="tel" name="phone" class="input" id="phone" placeholder="Numéro de téléphone" required />
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
                                <option selected value="option1">Proximité</option>
                                <option value="option2">Locale</option>
                                <option value="option3">Nationale</option>
                                <option value="option4">Sous Régionale</option>
                                <option value="option5">Continentale</option>
                                <option value="option6">Internationale</option>
                                <option value="option7">Mondiale</option>
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

    <script type="text/javascript" src="../../js/setp.js"></script>
</body>

</html>