<?php
session_start();
@include('../page/config.php');

if (!isset($_SESSION['username'])) {
    header('location: ../page/auth/adlogin.php');
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_user = $_GET['id'];

    // Récupérer les informations de l'utilisateur
    $recupUser = $conn->prepare('SELECT * FROM user WHERE id_user = :id_user');
    $recupUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $recupUser->execute();

    if ($client = $recupUser->fetch()) {
        $nom_client = $client['nom_user'];
        $username_client = $client['username'];
        $phonenumber = $client['tel_user'];
        $id_agent = $client['id_admin'];
        $actorType = $client['actorType'];
        $activSector_user = $client['activSector_user'];
        $adress_user = $client['adress_user'];
        $email_user = $client['email_user'];

        // Maintenant, récupérez les informations de l'agent
        $recupAgent = $conn->prepare('SELECT admintable.nom_admin FROM admintable WHERE id_admin = :id_admin');
        $recupAgent->bindParam(':id_admin', $id_agent, PDO::PARAM_INT);
        $recupAgent->execute();

        if ($agent = $recupAgent->fetch()) {
            $nom_agent = $agent['nom_admin'];
        } else {
            // L'agent avec l'ID spécifié n'existe pas
            exit();
        }
    } else {
        // L'utilisateur avec l'ID spécifié n'existe pas
        exit();
    }
} else {
    // Aucun ID d'utilisateur spécifié dans la requête GET
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>



    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>

    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-straight/css/uicons-solid-straight.css'>

    <link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">


    <link rel="stylesheet" href="./assets/compiled/css/table-datatable.css">


    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./assets/compiled/css/iconly.css">
    <link rel="stylesheet" href="./assets/compiled/css/style.css">
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

                        <li class="sidebar-item  ">
                            <a href="addclient.php" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Ajouter client</span>
                            </a>


                        </li>

                        <li class="sidebar-item  ">
                            <a href="listclient.php" class='sidebar-link'>
                            <i class="bi bi-people-fill"></i>
                                <span>Liste des clients</span>
                            </a>


                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                            <i class="bi bi-box2-fill"></i>
                                <span>Produit et service</span>
                            </a>

                            <ul class="submenu ">

                                <li class="submenu-item  ">
                                    <a href="listprod.php" class="submenu-link">Liste produit</a>

                                </li>

                                <li class="submenu-item  ">
                                    <a href="listserv.php" class="submenu-link">Liste service</a>

                                </li>


                            </ul>


                        </li>
                        <li class="sidebar-item  ">
                            <a href="listconso.php" class='sidebar-link'>
                                <i class="bi bi-card-heading"></i>
                                <span>Consommation</span>
                            </a>

                        </li>

                        <li class="sidebar-item  ">
                            <a href="profil.php" class='sidebar-link'>
                                <i class="bi bi-person-circle"></i>
                                <span>Profils</span>
                            </a>


                        </li>

                        <li class="sidebar-item">
                            <a href="#" class='sidebar-link' id="logoutBtn">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Se deconnecter</span>
                            </a>
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

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last mb-2">
                            <h3>Detail client</h3>

                        </div>

                    </div>
                </div>
                <section class="section">
                    <div class="row">
                    <div class="col-12">
                            <div class="card">
                                <div class="card-body ">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xxl me-3">
                                                <img src="assets/static/images/faces/2.jpg" alt=""
                                                    style="width: 100px; height: 100px;">
                                            </div>

                                            <div class="d-flex flex-column">
                                                <h5 class="mb-0">
                                                    <?= $nom_client ?>
                                                </h5>
                                                <p class="text-small mb-0">
                                                    <?= '@'.$username_client ?>
                                                </p>
                                            </div>
                                        </div>

                                      
                                    </div>

                                    <div class="row">
                                        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                                    href="#home" role="tab" aria-controls="home"
                                                    aria-selected="true">Information personel</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                                    href="#profile" role="tab" aria-controls="profile"
                                                    aria-selected="false">Produit et service</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                                    href="#contact" role="tab" aria-controls="contact"
                                                    aria-selected="false">Consomation</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="histoire-tab" data-bs-toggle="tab"
                                                    href="#histoire" role="tab" aria-controls="contact"
                                                    aria-selected="false">Historique de demande</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                                aria-labelledby="home-tab">

                                                <div class="row">
                                                    <div class="col-lg-6 col-12">
                                                        <div class="form-group">
                                                            <h6>Nom du client</h6>
                                                            <p>
                                                                <?= $nom_client ?>
                                                            </p>

                                                        </div>
                                                        <div class="form-group">
                                                            <h6>Username</h6>
                                                            <p>
                                                                <?= $username_client ?>
                                                            </p>

                                                        </div>
                                                        <div class="form-group">
                                                            <h6>Téléphone</h6>
                                                            <p>
                                                                <?= $phonenumber ?>
                                                            </p>

                                                        </div>
                                                        <div class="form-group">
                                                            <h6>Type d'acteur</h6>
                                                            <p>
                                                                <?= $actorType ?>
                                                            </p>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-12">
                                                        <div class="form-group">
                                                            <h6>Secteur d'activité</h6>
                                                            <p>
                                                                <?= $activSector_user ?>
                                                            </p>

                                                        </div>
                                                        <div class="form-group">
                                                            <h6>Adress</h6>
                                                            <p>
                                                                <?= $adress_user ?>
                                                            </p>

                                                        </div>
                                                        <div class="form-group">
                                                            <h6>Email</h6>
                                                            <p>
                                                                <?= $email_user ?>
                                                            </p>

                                                        </div>
                                                        <div class="form-group">
                                                            <h6>Agent rataché</h6>
                                                            <p>
                                                                <?= $nom_agent ?>
                                                            </p>

                                                        </div>
                                                    </div>
                                                </div>






                                            </div>
                                            <div class="tab-pane fade" id="profile" role="tabpanel"
                                                aria-labelledby="profile-tab">
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between">
                                                        <h5 class="card-title">
                                                            Produit du client
                                                        </h5>

                                                        <a href="addprod.php?id=<?= $id_user ?>"
                                                            class="btn btn-success">Ajouter</a>
                                                    </div>
                                                    <div class="card-body">
                                                        <table class="table table-striped" id="table1">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nom</th>
                                                                    <th>Type (local/importé)</th>
                                                                    <th>Conditionnement</th>
                                                                    <th>Format</th>
                                                                    <th>Quantité</th>
                                                                    <th>Prix</th>
                                                                    <th>Mode de paiement</th>
                                                                    <th>Capacité de livré</th>
                                                                    <th>Zone d'activité</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                // Utilisez une jointure LEFT JOIN avec une clause WHERE pour filtrer par id_user
                                                                $recupUsers = $conn->prepare('SELECT prodUser.*, user.nom_user FROM prodUser 
                                                                
                                                                 LEFT JOIN user ON prodUser.id_user = user.id_user
                                                                 WHERE prodUser.id_user = :id_user
                                                                 ORDER BY date_ajout DESC');

                                                                $recupUsers->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                                                                $recupUsers->execute();

                                                                while ($user = $recupUsers->fetch()) {
                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?= $user['nomArt']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['typeProd']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['condProd']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['formatProd']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['qteProd']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['PrixProd']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['paymodProd']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['LivreCapProd']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['zonecoProd']; ?>
                                                                        </td>

                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>


                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between">
                                                        <h5 class="card-title">
                                                            Service du client
                                                        </h5>

                                                        <a href="addserv.php?id=<?= $id_user ?>"
                                                            class="btn btn-success">Ajouter</a>
                                                    </div>
                                                    <div class="card-body">
                                                        <table class="table table-striped" id="table1">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nom du service</th>
                                                                    <th>Qualification</th>
                                                                    <th>Specialité</th>
                                                                    <th>Quantité</th>
                                                                    <th>Prix</th>
                                                                    <th>Mode de paiement</th>
                                                                    <th>Zone d'activité</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php

                                                                // Utilisez une jointure LEFT JOIN avec une clause WHERE pour filtrer par id_user
                                                                
                                                                $recupServ = $conn->prepare('SELECT servUser.*, user.nom_user FROM servUser 
                                                                
                                                                LEFT JOIN user ON servUser.id_user = user.id_user
                                                                WHERE servUser.id_user = :id_user
                                                                ORDER BY date_ajout DESC');

                                                                $recupServ->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                                                                $recupServ->execute();

                                                                while ($serv = $recupServ->fetch()) {
                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?= $serv['nomMet']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $serv['qalifServ']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $serv['sepServ']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $serv['qteServ']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $serv['PrixServ']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $serv['paymodServ']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $serv['zonecoServ']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $serv['nom_user']; ?>
                                                                        </td>

                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="contact" role="tabpanel"
                                                aria-labelledby="contact-tab">
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between">
                                                        <h5 class="card-title">
                                                            Consommation en produit du client
                                                        </h5>

                                                        <a href="addconsprod.php?id=<?= $id_user ?>"
                                                            class="btn btn-success">Ajouter</a>
                                                    </div>
                                                    <div class="card-body">
                                                        <table class="table table-striped" id="table1">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nom</th>
                                                                    <th>Type (local/importé)</th>
                                                                    <th>Conditionnement</th>
                                                                    <th>Format</th>
                                                                    <th>Quantité</th>
                                                                    <th>Prix</th>
                                                                    <th>Frequence</th>
                                                                    <th>Jour (achat)</th>
                                                                    <th>Zone d'activité</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                // Utilisez une jointure LEFT JOIN avec une clause WHERE pour filtrer par id_user
                                                                $recupUsers = $conn->prepare('SELECT consprodUser.*, user.nom_user FROM consprodUser 
                    LEFT JOIN user ON consprodUser.id_user = user.id_user
                    WHERE consprodUser.id_user = :id_user
                    ORDER BY date_ajout DESC');

                                                                $recupUsers->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                                                                $recupUsers->execute();

                                                                while ($user = $recupUsers->fetch()) {
                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?= $user['nom_art']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['type_prov']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['cond_cons']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['format_cons']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['qte_cons']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['prix_cons']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['frqce_conse']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['jourAch_cons']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $user['zoneAct']; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between">
                                                        <h5 class="card-title">
                                                            Consommation en service du client
                                                        </h5>

                                                        <a href="addconserv.php?id=<?= $id_user ?>"
                                                            class="btn btn-success">Ajouter</a>
                                                    </div>
                                                    <div class="card-body">
                                                        <table class="table table-striped" id="table1">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nom metier</th>
                                                                    <th>Qualification</th>
                                                                    <th>Specialité</th>
                                                                    <th>Prix</th>
                                                                    <th>Frequence</th>
                                                                    <th>Quantité</th>
                                                                    <th>Zone d'activité</th>
                                                                    <th>Nom client</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                // Utilisez une jointure LEFT JOIN avec une clause WHERE pour filtrer par id_user
                                                                $recupServ = $conn->prepare('SELECT consservUser.*, user.nom_user FROM consservUser 
    LEFT JOIN user ON consservUser.id_user = user.id_user
    WHERE consservUser.id_user = :id_user
    ORDER BY date_ajout DESC');

                                                                $recupServ->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                                                                $recupServ->execute();

                                                                while ($conserv = $recupServ->fetch()) {
                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?= $conserv['nom_met']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $conserv['qalif_user']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $conserv['spetia_user']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $conserv['prix_cons']; ?>
                                                                        </td>

                                                                        <td>
                                                                            <?= $conserv['frqce_conse']; ?>
                                                                        </td>

                                                                        <td>
                                                                            <?= $conserv['qte_cons']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $conserv['zoneAct']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $conserv['nom_user']; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="histoire" role="tabpanel"
                                                aria-labelledby="histoire-tab">
                                                Historique vide
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

            </div>



        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    // Ajouter un écouteur d'événement de clic au bouton "Supprimé client"
    document.getElementById('deleteClientBtn').addEventListener('click', function (event) {
        // Prevent the form from submitting normally
        event.preventDefault();

        // Afficher l'alerte SweetAlert
        Swal.fire({
            title: "Êtes-vous sûr?",
            text: "Vous ne pourrez pas revenir en arrière!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, supprimer!",
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                // Afficher une autre alerte si l'utilisateur confirme
                Swal.fire({
                    title: "Supprimé!",
                    text: "Votre fichier a été supprimé.",
                    icon: "success"
                }).then(() => {
                    // Submit the form after the success alert is closed
                    this.closest('form').submit();
                });
            }
        });
    });
</script>
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script>
<script src="assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
<script src="assets/static/js/pages/simple-datatables.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    document.getElementById('logoutBtn').addEventListener('click', function (event) {
        // Empêcher le comportement par défaut du lien
        event.preventDefault();

        // Afficher l'alerte SweetAlert2
        Swal.fire({
            title: "Êtes-vous sûr de vous déconnecter?",

            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui",
            cancelButtonText: 'Non',
        }).then((result) => {
            if (result.isConfirmed) {
                // Rediriger vers la page de déconnexion après confirmation
                window.location.href = "logout.php";
            }
        });
    });
</script>



</body>

</html>