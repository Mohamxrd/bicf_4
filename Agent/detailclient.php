<?php
session_start();
@include('../page/config.php');

if (!isset($_SESSION['username'])) {
    header('location: ../page/auth/adlogin.php');
}

if (isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_user = $_GET['id'];

    $recupUser = $conn->prepare('SELECT * FROM user WHERE id_user = :id_user');
    $recupUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $recupUser->execute();

    if ($client = $recupUser->fetch()){
        $nom_client = $client['nom_user'];
        $username_client = $client['username'];
        $phonenumber = $client['tel_user'];
        $id_agent = $client['id_admin'];
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

                        <li class="sidebar-item ">
                            <a href="logout.php" class='sidebar-link'>
                            <i class="bi bi-box-arrow-right "></i>
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
                                            <?= $nom_client ?>
                                        </h5>
                                        <p class="text-small">Client</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8">

                            <div class="card">



                                <div class="card-body">



                                    <form action="#" method="post">

                                        <div class="form-group">
                                            <h5>Username</h5>
                                            <p>
                                                <?= $username_client ?>
                                            </p>

                                        </div>
                                        <div class="form-group">
                                            <h5>Téléphone</h5>
                                            <p>
                                                <?= $phonenumber ?>
                                            </p>

                                        </div>
                                        

                                        <div class="form-group">
                                            <button name="submit-info" type="submit" class="btn btn-danger"
                                                >Supprimé client</button>
                                        </div>
                                    </form>

                                   
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



</body>

</html>