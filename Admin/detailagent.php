<?php

session_start();
@include('../page/config.php');



if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_agent = $_GET['id'];

    // Récupérer les informations de l'agent spécifié
    $recupAgent = $conn->prepare('SELECT * FROM adminTable WHERE id_admin = :id_admin');
    $recupAgent->bindParam(':id_admin', $id_agent, PDO::PARAM_INT);
    $recupAgent->execute();

    $recupUsers = $conn->prepare('SELECT * FROM user WHERE id_admin = :id_admin');
    $recupUsers->bindParam(':id_admin', $id_agent, PDO::PARAM_INT);
    $recupUsers->execute();


    $nombreClient = $recupUsers->rowCount();

    $nombreClient = $recupUsers->rowCount();

    if ($agent = $recupAgent->fetch()) {
        // Les informations de l'agent sont maintenant dans $agent
        $id_agent = $agent['id_admin'];
        $nom_agent = $agent['nom_admin'];
        $username_agent = $agent['username_admin'];
        $phonenumber = $agent['phonenumber'];
        $admin_type = $agent['admin_type'];
        $date_creation = $agent['date_creation'];

    } else {

        exit();
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
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>

    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-straight/css/uicons-solid-straight.css'>


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

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Agent</span>
                            </a>

                            <ul class="submenu ">

                                <li class="submenu-item  ">
                                    <a href="addagent.php" class="submenu-link">Ajouter Agents</a>

                                </li>

                                <li class="submenu-item  active">
                                    <a href="listagent.php" class="submenu-link">Liste des agents</a>

                                </li>


                            </ul>


                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                            <i class="bi bi-people-fill"></i>
                                <span>Client</span>
                            </a>

                            <ul class="submenu ">

                                <li class="submenu-item  ">
                                    <a href="addclient.php" class="submenu-link">Ajouter Client</a>

                                </li>

                                <li class="submenu-item  ">
                                    <a href="listclient.php" class="submenu-link">Liste des clients</a>

                                </li>


                            </ul>


                        </li>

                        <li class="sidebar-item  ">
                            <a href="conso.php" class='sidebar-link'>
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
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Profile</h3>

                        </div>

                    </div>
                </div>
                <section class="section">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <div class="avatar avatar-xxl me-3">
                                            <img src="assets/static/images/faces/2.jpg" alt="" srcset=""
                                                style="width: 180px; height: 180px;">
                                        </div>

                                        <h5 class="mt-3">
                                            <?= $nom_agent ?>
                                        </h5>
                                        <p class="text-small">Agent</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8">

                            <div class="card">



                                <div class="card-body">





                                    <div class="form-group">
                                        <h5>Username</h5>
                                        <p>
                                            <?= $username_agent ?>
                                        </p>

                                    </div>
                                    <div class="form-group">
                                        <h5>Téléphone</h5>
                                        <p>
                                            <?= $phonenumber ?>
                                        </p>

                                    </div>
                                    <div class="form-group">
                                        <h5>Nombre de client enregistré</h5>
                                        <p>
                                            <?= $nombreClient ?>
                                        </p>

                                    </div>

                                    <form method="post" action="delete_agent.php">

                                        <input type="hidden" name="id_admin" value="<?= $id_agent ?>">
                                        <button type="submit" class="btn btn-outline-danger"
                                            id="deleteClientBtn">Supprimé agent</button>
                                    </form>



                                </div>
                            </div>
                        </div>

                    </div>
                </section>
                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title">
                                Tout les clients enregistrés
                            </h5>


                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Username</th>
                                        <th>Telephone</th>
                                        <th>Details</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Utilizez l'agent_id dans la clause WHERE de votre requête SQL
                                    $recupUsers = $conn->prepare('SELECT * FROM user WHERE id_admin = :id_admin');
                                    $recupUsers->bindParam(':id_admin', $id_agent, PDO::PARAM_INT);
                                    $recupUsers->execute();

                                    while ($user = $recupUsers->fetch()) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $user['nom_user']; ?>
                                            </td>
                                            <td>
                                                <?= $user['username']; ?>
                                            </td>
                                            <td>
                                                <?= $user['tel_user']; ?>
                                            </td>
                                            <td><a href="detailclient.php?id=<?= $user['id_user']; ?>">Details</a></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                </tbody>
                            </table>
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


    <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="assets/static/js/pages/dashboard.js"></script>

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