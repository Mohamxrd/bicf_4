<?php
@include('../page/config.php');


session_start();

if (!isset($_SESSION['id_admin'])) {
    header('location: ../page/auth/adlogin.php');
}


$errorMsg = '';
$sucessMdg = '';

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
                $socialStatus_user = $age_user = $sexe_user  = $Servtype = $orgaType = $orgaType2 = $comType = $menaType = $menaStat = null;
            } elseif ($actorType == "Service public") {
                $entreSize = $socialStatus_user = $age_user = $sexe_user  = $orgaType = $orgaType2 = $comType = $menaType = $menaStat = null;
            } elseif ($actorType == "Organisme") {
                $entreSize = $socialStatus_user = $age_user = $sexe_user  = $comType = $menaType = $menaStat = null;
            } elseif ($actorType == "Comunauté") {
                $entreSize = $socialStatus_user = $age_user = $sexe_user  = $Servtype = $orgaType = $orgaType2  = $menaType = $menaStat = null;
            } elseif ($actorType == "Menage") {
                $entreSize = $socialStatus_user = $age_user = $sexe_user  = $Servtype = $orgaType = $orgaType2    = null;
            }



            if ($activSector_user == "Industrie") {
                $bat_user = $comm_user = $serv_user = null;
            } elseif ($activSector_user == "Construction") {
                $indus_user = $comm_user = $serv_user = null;
            } elseif ($activSector_user == "Commerce") {
                $indus_user = $bat_user = $serv_user = null;
            } elseif ($activSector_user == "Service") {
                $indus_user = $bat_user = $comm_user  = null;
            } elseif ($activSector_user == "Autre") {
                $indus_user = $bat_user = $comm_user = $serv_user  = null;
            }

            $adminId = $_SESSION['id_admin'];


            $insertUser = $conn->prepare('INSERT INTO user(nom_user, prenom_user, username, password, actorType, sexe_user, age_user, socialStatus_user, entreSize, Servtype, orgaType, orgaType2, comType, menaType, menaStat, activSector_user, indus_user, bat_user, comm_user, serv_user, pays_user, tel_user, local_user, adress_user, email_user, ActivZone_user, id_admin) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
            $insertUser->execute(array($nom_user, $prenom_user, $username, $password, $actorType, $sexe_user, $age_user, $socialStatus_user, $entreSize, $Servtype, $orgaType, $orgaType2, $comType, $menaType, $menaStat,  $activSector_user, $indus_user, $bat_user, $comm_user, $serv_user, $pays_user, $tel_user, $local_user, $adress_user, $email_user, $ActivZone_user, $adminId));

            $sucessMdg = "Le compte a été creer avec success !";
        } catch (PDOException $e) {
            $errorMsg = "Erreur lors de l'insertion dans la base de données : " . $e->getMessage();
        }
    }
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>



    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <!-- <link rel="shortcut icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC" type="image/png"> -->

    <link rel="stylesheet" href="./assets/compiled/css/step.css">

    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./assets/compiled/css/iconly.css">
</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>


    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="index.html"><img src="./assets/compiled/svg/logo.svg" alt="Logo" srcset=""></a>
                        </div>
                        <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                </path>
                            </svg>
                        </div>
                        <div class="sidebar-toggler  x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item  ">
                            <a href="index.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>


                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Agent</span>
                            </a>

                            <ul class="submenu  ">

                                <li class="submenu-item  ">
                                    <a href="addagent.php" class="submenu-link">Ajouter Agents</a>

                                </li>

                                <li class="submenu-item  ">
                                    <a href="listagent.php" class="submenu-link">Liste des agents</a>

                                </li>


                            </ul>


                        </li>

                        <li class="sidebar-item active has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Client</span>
                            </a>

                            <ul class="submenu active">

                                <li class="submenu-item  active">
                                    <a href="addclient.php" class="submenu-link">Ajouter Client</a>

                                </li>

                                <li class="submenu-item  ">
                                    <a href="listclient.php" class="submenu-link">Liste des clients</a>

                                </li>


                            </ul>


                        </li>



                    </ul>
                </div>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading d-flex justify-content-between">
                <h3>Ajouter un client</h3>

                <a href="logout.php" class="btn btn-outline-primary">Se deconnecter</a>
            </div>
            <div class="page-content">


                <div class="wrapper">
                    <!-- <div class="alert alert-light-success alert-dismissible show fade">
                        Compte créer avec success 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div> -->

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
                                    <div class="error-msg">Veillez remplire tout les champs obligatoires</div>

                                    <?php

                                    if (!empty($errorMsg)) {
                                        echo '<div class="error-message">' . $errorMsg . '</div>';
                                    }
                                    ?>

                                    <?php

                                    if (!empty($sucessMdg)) {
                                        echo '<div class="successMsg">' . $sucessMdg . '</div>';
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
                                            <option selected value="Proximité">Proximité</option>
                                            <option value="Locale">Locale</option>
                                            <option value="Nationale">Nationale</option>
                                            <option value="Sous Régionale">Sous Régionale</option>
                                            <option value="Continentale">Continentale</option>
                                            <option value="Internationale">Internationale</option>
                                            <option value="Mondiale">Mondiale</option>
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



                </div>


            </div>


        </div>
    </div>



    <script src="../js/step3.js"></script>
    <script src="../js/step4.js"></script>

   
  <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
     <script src="assets/static/js/components/dark.js"></script>
  


    <script src="assets/compiled/js/app.js"></script>


    <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="assets/static/js/pages/dashboard.js"></script> 

</body>

</html>