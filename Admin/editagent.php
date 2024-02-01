<?php
session_start();
@include('../page/config.php');

if (!isset($_SESSION['id_admin'])) {
    header('location: ../page/auth/adlogin.php');
}

$nom_agent = "Aucun agent";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_user = $_GET['id'];

    // Récupérer les informations de l'utilisateur
    $recupUser = $conn->prepare('SELECT * FROM user WHERE id_user = :id_user');
    $recupUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $recupUser->execute();

    if ($client = $recupUser->fetch()) {

        $nom_client = $client['nom_user'];
        $id_agent = $client['id_admin'];

        // Maintenant, récupérez les informations de l'agent
        $recupAgent = $conn->prepare('SELECT admintable.nom_admin FROM admintable WHERE id_admin = :id_admin');
        $recupAgent->bindParam(':id_admin', $id_agent, PDO::PARAM_INT);
        $recupAgent->execute();

        if ($agent = $recupAgent->fetch()) {
            $nom_agent = $agent['nom_admin'];
        }
    }
}

if (isset($_POST['selectionner'])) {
    // Récupérer l'ID de l'agent à partir du formulaire
    $id_agent_selectionne = $_POST['id_agent'];

    // Mettre à jour la table user avec l'ID de l'agent sélectionné
    $updateUser = $conn->prepare('UPDATE user SET id_admin = :id_admin WHERE id_user = :id_user');
    $updateUser->bindParam(':id_admin', $id_agent_selectionne, PDO::PARAM_INT);
    $updateUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $updateUser->execute();

    $_SESSION['agent_modifie'] = true;

    // Rediriger ou afficher un message de succès si nécessaire
    header('Location: detailclient.php?id='.$id_user);
    // exit();
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

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Agent</span>
                            </a>

                            <ul class="submenu ">

                                <li class="submenu-item  ">
                                    <a href="addagent.php" class="submenu-link">Ajouter Agents</a>

                                </li>

                                <li class="submenu-item  ">
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
                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-box2-fill"></i>
                                <span>Produit et service</span>
                            </a>

                            <ul class="submenu ">

                                <li class="submenu-item  ">
                                    <a href="listprod.php" class="submenu-link">Liste produit</a>

                                </li>

                                <li class="submenu-item ">
                                    <a href="listserv.php" class="submenu-link">Liste service</a>

                                </li>


                            </ul>


                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-card-heading"></i>
                                <span>Consommation</span>
                            </a>

                            <ul class="submenu ">

                                <li class="submenu-item  ">
                                    <a href="listConsoProd.php" class="submenu-link">Produits</a>

                                </li>

                                <li class="submenu-item  ">
                                    <a href="listConsoServ.php" class="submenu-link">Services</a>

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
                <h3>Modifier agent pour
                    <?= $nom_client ?>
                </h3>
            </div>

            <div class="page-content">


                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">
                                Selectionné agent
                            </h5>
                           
                        </div>

                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Username</th>
                                        <th>Nombre de client</th>
                                        <th>Selectionné</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $recupAdmins = $conn->prepare('SELECT * FROM adminTable WHERE admin_type = :admin_type ORDER BY date_creation DESC');
                                    $recupAdmins->bindValue(':admin_type', 'agent', PDO::PARAM_STR);
                                    $recupAdmins->execute();

                                    $counter = 0;

                                    while ($admin = $recupAdmins->fetch()) {
                                        // Utilisez une requête pour compter le nombre de clients associés à chaque administrateur
                                        $countClients = $conn->prepare('SELECT COUNT(*) as client_count FROM user WHERE id_admin = :id_admin');
                                        $countClients->bindValue(':id_admin', $admin['id_admin'], PDO::PARAM_INT);
                                        $countClients->execute();
                                        $clientCount = $countClients->fetchColumn();

                                        $counter++;
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $admin['nom_admin']; ?>
                                            </td>
                                            <td>
                                                <?= $admin['username_admin']; ?>
                                            </td>
                                            <td>
                                                <?= $clientCount; ?>
                                            </td>
                                            <td>
                                                <form method="POST" action="" id="agentForm<?= $counter ?>">
                                                    <!-- Ajoutez un champ caché pour stocker l'ID de l'agent -->
                                                    <input type="hidden" name="id_agent" value="<?= $admin['id_admin']; ?>">
                                                    <input type="submit" class="btn btn-primary btn-selectionner"
                                                        name="selectionner" value="selectionner"
                                                        data-agent-id="<?= $admin['id_admin']; ?>"
                                                        id="btnSelectionner<?= $counter ?>">
                                                </form>

                                            </td>
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