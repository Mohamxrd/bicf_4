<?php

session_start();
@include('../page/config.php');

if (!isset($_SESSION['id_agent'])) {
    header('location: ../page/auth/adlogin.php');

}

$id_admin = $_SESSION['id_agent']


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
    <!-- <script src="assets/static/js/initTheme.js"></script> -->
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

                        <li class="sidebar-item active has-sub">
                            <a href="#" class='sidebar-link'>
                            <i class="bi bi-card-heading"></i>
                                <span>Produit et service</span>
                            </a>

                            <ul class="submenu ">

                                <li class="submenu-item  ">
                                    <a href="listprod.php" class="submenu-link">Liste produit</a>

                                </li>

                                <li class="submenu-item  active">
                                    <a href="listserv.php" class="submenu-link">Liste service</a>

                                </li>


                            </ul>


                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                            <i class="bi bi-box2-fill"></i>
                                <span>Consommation</span>
                            </a>

                            <ul class="submenu ">

                                <li class="submenu-item  ">
                                    <a href="listconprodA.php" class="submenu-link">Produit</a>

                                </li>

                                <li class="submenu-item  ">
                                    <a href="listconservA.php" class="submenu-link">Service</a>

                                </li>


                            </ul>


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
            <header>
                <nav class="navbar navbar-expand navbar-light navbar-top p-0">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block d-xl-none">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </nav>
            </header>

            <div class="page-heading d-flex justify-content-between">
                <h3>Produits</h3>
            </div>

            <div class="page-content">
                <div class="card">
                <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title">
                           Liste des Produits
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="table1_consprod">
                            <!-- ... Votre contenu pour la consommation en produit ... -->

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
                                        <th>Nom client</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                   $recupUsers = $conn->prepare('
                                   SELECT servUser.*, user.nom_user 
                                   FROM servUser 
                                   LEFT JOIN user ON servUser.id_user = user.id_user
                                   LEFT JOIN adminTable ON user.id_admin = adminTable.id_admin
                                   WHERE adminTable.id_admin = :id_admin
                                   ORDER BY servUser.date_ajout DESC');

                                   $recupUsers->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
                                   $recupUsers->execute();

                                    while ($user = $recupUsers->fetch()) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $user['nomMet']; ?>
                                            </td>
                                            <td>
                                                <?= $user['qalifServ']; ?>
                                            </td>
                                            <td>
                                                <?= $user['sepServ']; ?>
                                            </td>
                                            <td>
                                                <?= $user['qteServ']; ?>
                                            </td>
                                            <td>
                                                <?= $user['PrixServ']; ?>
                                            </td>
                                            <td>
                                                <?= $user['paymodServ']; ?>
                                            </td>
                                            <td>
                                                <?= $user['zonecoServ']; ?>
                                            </td>
                                            <td>
                                                <?= $user['nom_user']; ?>
                                            </td>
                                            
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </table>
                    </div>
                </div>

                
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
    <!-- <script src="assets/static/js/components/dark.js"></script> -->
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