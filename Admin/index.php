<?php

session_start();
@include('../page/config.php');




if (!isset($_SESSION['username'])) {
    header('location: ../page/auth/adlogin.php');
}

$countUsers = $conn->query('SELECT COUNT(*) FROM user')->fetchColumn();

$countAgents = $conn->prepare('SELECT COUNT(*) FROM adminTable WHERE admin_type = :admin_type');
$countAgents->bindValue(':admin_type', 'agent', PDO::PARAM_STR);
$countAgents->execute();
$numberOfAgents = $countAgents->fetchColumn();





?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>



    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    

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

                        <li class="sidebar-item active ">
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
                                <i class="bi bi-collection-fill"></i>
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

            <div class="page-heading d-flex justify-content-between">
                <h3>Statistique</h3>

                
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 ">
                        <div class="row">
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon purple mb-2">
                                                    <i class="iconly-boldAdd-User"></i>


                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Clients</h6>
                                                <h6 class="font-extrabold mb-0"><?= $countUsers ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon blue mb-2">
                                                    <i class="iconly-boldUser1"></i>

                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Agents</h6>
                                                <h6 class="font-extrabold mb-0"><?= $numberOfAgents ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon green mb-2">
                                                    <i class="iconly-boldArrow---Down"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Demande</h6>
                                                <h6 class="font-extrabold mb-0">80,000</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon red mb-2">
                                                    <i class="iconly-boldArrow---Up"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Ofrre</h6>
                                                <h6 class="font-extrabold mb-0">112</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="row d-flex">



                                    <div class="card p-3 d-flex flex-column">
                                        <div class="row justify-content-around">
                                            <?php
                                            $recupAdmins = $conn->prepare('SELECT nom_admin FROM admintable WHERE admin_type = :admin_type ORDER BY date_creation DESC LIMIT 3');
                                            $recupAdmins->bindValue(':admin_type', 'agent', PDO::PARAM_STR);
                                            $recupAdmins->execute();

                                            $numAgent = 1;

                                            while ($admin = $recupAdmins->fetch()) {
                                            ?>

                                                <div class="col-6 col-md-3 d-flex flex-column align-items-center ">
                                                    <div class="avatar me-7 align-items-center mb-3">
                                                        <img src="./assets/compiled/jpg/<?= $numAgent ?>.jpg" alt="" srcset="" class="img-fluid rounded-circle" style="width: 100px; height: 100px;">
                                                    </div>
                                                    <h6 class="text-center"><?= $admin['nom_admin']; ?></h6>
                                                    <p>Agent <?= $numAgent; ?></p>
                                                </div>

                                            <?php
                                                $numAgent++;
                                            }
                                            ?>
                                            <div class="col-6 col-md-3 d-flex flex-column align-items-center mb-3">
                                                <a href="addagent.php"><i class="fi fi-ss-plus" style="font-size: 100px; color: #999;"></i></a>
                                                <h6 class="text-center">Ajouter</h6>
                                            </div>
                                        </div>
                                    </div>





                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-12 col-xl-8">
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h4 class="card-title">Client recement ajouté</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-lg">
                                                    <thead>
                                                        <tr>
                                                            <th>Nom</th>
                                                            <th>Username</th>
                                                            <th>Telephone</th>
                                                            <th>Agent</th>
                                                            <th>Details</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        // Utilizez l'agent_id dans la clause WHERE de votre requête SQL
                                                        $recupUsers = $conn->prepare('SELECT user.*, admintable.nom_admin FROM user LEFT JOIN admintable ON user.id_admin = admintable.id_admin AND admintable.admin_type = "agent" ORDER BY date_creation DESC LIMIT 3');

                                                        $recupUsers->execute();

                                                        while ($user = $recupUsers->fetch()) {
                                                        ?>
                                                            <tr>
                                                                <td><?= $user['nom_user']; ?></td>
                                                                <td><?= $user['username']; ?></td>
                                                                <td><?= $user['tel_user']; ?></td>
                                                                <td><?= $user['nom_admin']; ?></td>
                                                                <td><a href="detailclient.php?id=<?= $user['id_user']; ?>">Details</a></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                                <div class="text-center">
                                                    <a href="listclient.php">Voir plus</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xl-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Rapide</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-7">
                                                <div class="d-flex align-items-center">
                                                    <svg class="bi text-success" width="32" height="32" fill="blue" style="width:10px">
                                                        <use xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                                    </svg>
                                                    <h5 class="mb-0 ms-3">Avocat</h5>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <h5 class="mb-0 text-end">375</h5>
                                            </div>
                                            <div class="col-12">
                                                <div id="chart-america"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-7">
                                                <div class="d-flex align-items-center">
                                                    <svg class="bi text-danger" width="32" height="32" fill="blue" style="width:10px">
                                                        <use xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                                    </svg>
                                                    <h5 class="mb-0 ms-3">Lait</h5>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <h5 class="mb-0 text-end">1025</h5>
                                            </div>
                                            <div class="col-12">
                                                <div id="chart-indonesia"></div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="custom-border" onclick="redirectToPage()">
                                               
                                                <h6 class="ms-3 mb-0">+ Ajouter un client</h6>
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

    <script>
    function redirectToPage() {
        // Rediriger vers la page souhaitée
        window.location.href = 'addclient.php';
    }
  </script>

</body>

</html>