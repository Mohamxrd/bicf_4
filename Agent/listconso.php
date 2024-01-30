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
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png">
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

                        <li class="sidebar-item active ">
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
                <h3>Consommation</h3>
            </div>

            <div class="page-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title">
                            Consommation en produit
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="table1_consprod">
                            <!-- ... Votre contenu pour la consommation en produit ... -->

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
                                        <th>Nom client</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Utilisez une jointure LEFT JOIN pour obtenir les informations de consprodUser et user
                                    $recupUsers = $conn->prepare('
                                    SELECT consprodUser.*, user.nom_user 
                                    FROM consprodUser 
                                    LEFT JOIN user ON consprodUser.id_user = user.id_user
                                    LEFT JOIN adminTable ON user.id_admin = adminTable.id_admin
                                    WHERE adminTable.id_admin = :id_admin
                                    ORDER BY consprodUser.date_ajout DESC');

                                    $recupUsers->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
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

                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title">
                            Consommation en service
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="table1_consserv">
                            <!-- ... Votre contenu pour la consommation en service ... -->

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
                                    // Utilisez une jointure LEFT JOIN pour obtenir les informations de consprodUser et user
                                    $recupServ = $conn->prepare('
                                        SELECT consservUser.*, user.nom_user 
                                        FROM consservUser 
                                        LEFT JOIN user ON consservUser.id_user = user.id_user
                                        LEFT JOIN adminTable ON user.id_admin = adminTable.id_admin
                                        WHERE adminTable.id_admin = :id_admin
                                        ORDER BY consservUser.date_ajout DESC');

                                    $recupServ->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
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