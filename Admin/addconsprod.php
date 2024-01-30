<?php
session_start();
@include('../page/config.php');

if (!isset($_SESSION['username'])) {
    header('location: ../page/auth/adlogin.php');
}
$errorMsg = '';
$successMsg = ''; 

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_user = $_GET['id'];

    // Récupérer les informations de l'utilisateur
    $recupUser = $conn->prepare('SELECT * FROM user WHERE id_user = :id_user');
    $recupUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $recupUser->execute();

    if ($client = $recupUser->fetch()) {
        $nom_client = $client['nom_user'];
    }

    if(isset($_POST['submit'])){
        $nom_produit = htmlspecialchars($_POST['pname']);
        $type_produit = htmlspecialchars($_POST['typep']);
        $conditionnalite = htmlspecialchars($_POST['cond']);
        $format = htmlspecialchars($_POST['format']);
        $quantite = htmlspecialchars($_POST['qte_prod']);
        $prix = htmlspecialchars($_POST['prix']);
        $frequence = htmlspecialchars($_POST['fqce']);
        $jour_achat = htmlspecialchars($_POST['jourAch']);
        $zone_economique = htmlspecialchars($_POST['zone_eco']);
        
        if(empty($nom_produit) || empty($conditionnalite) || empty($prix)){
            $errorMsg = "Veuillez remplir tous les champs obligatoires";
        } else {
            $inserConsprod = $conn->prepare("INSERT INTO consprodUser (nom_art, type_prov, cond_cons, format_cons, qte_cons, prix_cons, frqce_conse, jourAch_cons, zoneAct, id_user) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $inserConsprod->execute(array($nom_produit, $type_produit, $conditionnalite, $format, $quantite, $prix, $frequence, $jour_achat, $zone_economique, $id_user));
            
            $successMsg = "Consommation ajoutée avec succès !";
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
                <div class="page-title mb-4">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Ajouter une consommation en produit pour <?= $nom_client ?></h3>

                        </div>

                    </div>
                </div>
                <section class="section">

                    <div class="col-12">
                        <div class="card">

                       

                            <div class="card-content">
                                <div class="card-body" >
                                    <form class="form form-vertical" method="post">

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
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="first-name-vertical">Nom du produit *</label>
                                                        <input type="text" id="first-name-vertical" class="form-control"
                                                            name="pname" placeholder="Nom du produit">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="email-id-vertical">Type (Locale/importer)</label>
                                                        <input type="text" id="email-id-vertical" class="form-control"
                                                            name="typep" placeholder="type">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="contact-info-vertical">Conditionnalité *</label>
                                                        <input type="text" id="contact-info-vertical"
                                                            class="form-control" name="cond"
                                                            placeholder="Conditionnalité">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="password-vertical">Format</label>
                                                        <input type="text" id="password-vertical" class="form-control"
                                                            name="format" placeholder="Format">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="password-vertical">Quantité</label>
                                                        <input type="number" id="password-vertical" class="form-control"
                                                            name="qte_prod" placeholder="Quantité">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="password-vertical">Prix *</label>
                                                        <input type="number" id="password-vertical" class="form-control"
                                                            name="prix" placeholder="Prix">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="password-vertical">Frequence</label>
                                                        <input type="text" id="password-vertical" class="form-control"
                                                            name="fqce" placeholder="Frequence">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="password-vertical">Jour(Achat)</label>
                                                        <input type="text" id="password-vertical" class="form-control"
                                                            name="jourAch" placeholder="Jour(Achat)">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="zone-economique">Zone économique</label>
                                                        <select id="zone-economique" class="form-control"
                                                            name="zone_eco">
                                                            
                                                            <option value="local" selected>Local</option>
                                                            <option value="proximite">Proximité</option>
                                                            <option value="international">International</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="submit" name="submit"
                                                        class="btn btn-primary me-1 mb-1">Ajouter</button>
                                                    <button type="reset"
                                                        class="btn btn-light-secondary me-1 mb-1">Effacer</button>
                                                </div>
                                            </div>
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