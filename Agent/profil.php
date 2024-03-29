<?php

session_start();
@include('../page/config.php');

if (!isset($_SESSION['id_agent'])) {
    header('location: ../page/auth/adlogin.php');
}

$errorMsg = '';
$successMsg = '';
$errorMsg2 = '';
$successMsg2 = '';
$id_admin = $_SESSION['id_agent'];

$get_admin_info = $conn->prepare('SELECT nom_admin, username_admin, phonenumber FROM adminTable WHERE id_admin = :id_admin');
$get_admin_info->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
$get_admin_info->execute();
$admin_info = $get_admin_info->fetch(PDO::FETCH_ASSOC);



if (isset($_POST['submit'])) {


    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $errorMsg = 'Veuillez remplir tous les champs.';
    } else {
        // Vérifie si le nouveau mot de passe correspond à la confirmation
        if ($new_password !== $confirm_password) {
            $errorMsg = 'Les mots de passe ne correspondent pas.';
        } else {
            // Vérifie le mot de passe actuel dans la base de données
            $check_password = $conn->prepare('SELECT password_admin FROM adminTable WHERE id_admin = :id_admin');
            $check_password->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
            $check_password->execute();
            $hashed_password = $check_password->fetchColumn();

            // Vérifie si le mot de passe actuel est correct
            if (password_verify($current_password, $hashed_password)) {
                // Met à jour le mot de passe dans la base de données
                $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password = $conn->prepare('UPDATE adminTable SET password_admin = :new_password WHERE id_admin = :id_admin');
                $update_password->bindParam(':new_password', $new_password_hashed, PDO::PARAM_STR);
                $update_password->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
                $update_password->execute();

                $successMsg = 'Le mot de passe a été changé avec succès.';
            } else {
                $errorMsg = 'Mot de passe actuel incorrect.';
            }
        }
    }
}

if (isset($_POST['submit-info'])) {
    $new_name = $_POST['name'];
    $new_username = $_POST['username'];
    $new_phone = $_POST['phone'];

    // Vérifier si le nouveau nom d'utilisateur existe déjà
    $check_username = $conn->prepare('SELECT id_admin FROM adminTable WHERE username_admin = :new_username AND id_admin != :id_admin');
    $check_username->bindParam(':new_username', $new_username, PDO::PARAM_STR);
    $check_username->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
    $check_username->execute();

    if ($check_username->fetchColumn()) {
        $errorMsg2 = 'Le nom d\'utilisateur existe déjà.';
    } else {
        // Mettre à jour les informations dans la base de données
        $update_info = $conn->prepare('UPDATE adminTable SET nom_admin = :new_name, username_admin = :new_username, phonenumber = :new_phone WHERE id_admin = :id_admin');
        $update_info->bindParam(':new_name', $new_name, PDO::PARAM_STR);
        $update_info->bindParam(':new_username', $new_username, PDO::PARAM_STR);
        $update_info->bindParam(':new_phone', $new_phone, PDO::PARAM_STR);
        $update_info->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);

        if ($update_info->execute()) {
            $successMsg2 = 'Les informations ont été mises à jour avec succès.';
            // Rafraîchir les informations de l'administrateur après la mise à jour
            $admin_info = array(
                'nom_admin' => $new_name,
                'username_admin' => $new_username,
                'phonenumber' => $new_phone
            );
        } else {
            $errorMsg2 = 'Une erreur s\'est produite lors de la mise à jour des informations.';
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
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>

    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-straight/css/uicons-solid-straight.css'>


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
                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                            <i class="bi bi-card-heading"></i>
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

                        <li class="sidebar-item  active">
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
                <div class="page-title mb-4">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Profile</h3>

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
                                                <img src="assets/static/images/faces/4.jpg" alt=""
                                                    style="width: 100px; height: 100px;">
                                            </div>

                                            <div class="d-flex flex-column">
                                                <h5 class="mb-0">
                                                    <?php echo $admin_info['nom_admin']; ?>
                                                </h5>
                                                <p class="text-small mb-0">
                                                    <?php echo '@' . $admin_info['username_admin'] ?>
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
                                                    aria-selected="false">Modifier le profile</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                                    href="#contact" role="tab" aria-controls="contact"
                                                    aria-selected="false">Modifier Mot de passe</a>
                                            </li>

                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                                aria-labelledby="home-tab">

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <h6>Nom de l'agent</h6>
                                                            <p>
                                                                <?php echo $admin_info['nom_admin']; ?>
                                                            </p>

                                                        </div>
                                                        <div class="form-group">
                                                            <h6>Username</h6>
                                                            <p>
                                                                <?php echo '@' . $admin_info['username_admin'] ?>
                                                            </p>

                                                        </div>
                                                        <div class="form-group">
                                                            <h6>Téléphone</h6>
                                                            <p>
                                                                <?php echo $admin_info['phonenumber'] ?>
                                                            </p>

                                                        </div>

                                                    </div>

                                                </div>






                                            </div>
                                            <div class="tab-pane fade" id="profile" role="tabpanel"
                                                aria-labelledby="profile-tab">
                                                <div class="col-12 col-lg-8">

                                                    <div class="card">



                                                        <div class="card-body">

                                                            <?php

                                                            if (!empty($successMsg2)) {

                                                                echo '
<div class="alert alert-light-success alert-dismissible show fade">
' . $successMsg2 . '
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
';
                                                            }

                                                            ?>
                                                            <?php

                                                            if (!empty($errorMsg2)) {

                                                                echo '
<div class="alert alert-light-danger alert-dismissible show fade">
' . $errorMsg2 . '
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
';
                                                            }

                                                            ?>

                                                            <form action="#" method="post">
                                                                <div class="form-group">
                                                                    <label for="name" class="form-label">Nom</label>
                                                                    <input type="text" name="name" id="name"
                                                                        class="form-control" placeholder="Your Name"
                                                                        value="<?php echo $admin_info['nom_admin']; ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="username"
                                                                        class="form-label">Username</label>
                                                                    <input type="text" name="username" id="username"
                                                                        class="form-control" placeholder="username"
                                                                        value="<?php echo $admin_info['username_admin']; ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="phone" class="form-label">Numero de
                                                                        téléphone</label>
                                                                    <input type="text" name="phone" id="phone"
                                                                        class="form-control" placeholder="Your Phone"
                                                                        value="<?php echo $admin_info['phonenumber']; ?>">
                                                                </div>

                                                                <div class="form-group">
                                                                    <button name="submit-info" type="submit"
                                                                        class="btn btn-primary">Modifier</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="contact" role="tabpanel"
                                                aria-labelledby="contact-tab">
                                                <div class="col-12">

                                                    <div class="card-header">
                                                        <h5 class="card-title">Changer votre mot de passe</h5>
                                                    </div>
                                                    <?php

                                                    if (!empty($successMsg)) {

                                                        echo '
<div class="alert alert-light-success alert-dismissible show fade">
' . $successMsg . '
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
';
                                                    }

                                                    ?>
                                                    <?php

                                                    if (!empty($errorMsg)) {

                                                        echo '
<div class="alert alert-light-danger alert-dismissible show fade">
' . $errorMsg . '
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
';
                                                    }

                                                    ?>
                                                    <div class="card-body">
                                                        <form action="#" method="post">
                                                            <div class="form-group my-2">
                                                                <label for="current_password" class="form-label">Mot de
                                                                    passe actuel</label>
                                                                <input type="password" name="current_password"
                                                                    id="current_password" class="form-control"
                                                                    placeholder="Mot de passe actuel" value="">
                                                            </div>
                                                            <div class="form-group my-2">
                                                                <label for="new_password" class="form-label">Nouveau Mot
                                                                    de passe</label>
                                                                <input type="password" name="new_password"
                                                                    id="new_password" class="form-control"
                                                                    placeholder="Entrer nouveau mot de passe" value="">
                                                            </div>
                                                            <div class="form-group my-2">
                                                                <label for="confirm_password"
                                                                    class="form-label">Confirmer mot de
                                                                    passe</label>
                                                                <input type="password" name="confirm_password"
                                                                    id="confirm_password" class="form-control"
                                                                    placeholder="Confirmer mot de passe" value="">
                                                            </div>

                                                            <div class="form-group my-2 d-flex justify-content-end">
                                                                <button name="submit" type="submit"
                                                                    class="btn btn-primary">Sauvegarder</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
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
    <!-- <script src="assets/static/js/components/dark.js"></script> -->
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