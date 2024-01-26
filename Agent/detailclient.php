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
                                <i class="bi bi-collection-fill"></i>
                                <span>Liste des clients</span>
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
                                               Aucun produit enregistré
                                            </div>
                                            <div class="tab-pane fade" id="contact" role="tabpanel"
                                                aria-labelledby="contact-tab">
                                               Aucune donnée enregistré
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