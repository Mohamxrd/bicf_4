<?php
session_start();
@include('../../../config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['nom_user'])) {
    header('location: ../../../auth/login.php');
    exit(); // Arrête l'exécution du script après la redirection
}

// Récupérer l'ID de l'utilisateur à partir de la session
$id_user = $_SESSION['id_user'];

// Récupérer les informations de l'utilisateur à partir de la base de données
$recupUser = $conn->prepare('SELECT * FROM user WHERE id_user = :id_user');
$recupUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$recupUser->execute();

if ($client = $recupUser->fetch()) {
    // Récupérer les champs de l'utilisateur
    $nom_client = $client['nom_user'];
    $username_client = $client['username'];
    $phonenumber = $client['tel_user'];
    $id_agent = $client['id_admin'];
    $actorType = $client['actorType'];
    $activSector_user = $client['activSector_user'];
    $adress_user = $client['adress_user'];
    $email_user = $client['email_user'];
    $pays_user = $client['pays_user'];
    $local_user = $client['local_user'];
    $ActivZone_user = $client['ActivZone_user'];
}

// Récupérer le nom de l'agent à partir de son ID
if (isset($id_agent)) {
    $recupAgent = $conn->prepare('SELECT nom_admin FROM admintable WHERE id_admin = :id_admin');
    $recupAgent->bindParam(':id_admin', $id_agent, PDO::PARAM_INT);
    $recupAgent->execute();

    if ($agent = $recupAgent->fetch()) {
        $nom_agent = $agent['nom_admin'];
    }
}

// Récupérer les informations sur le produit à partir de son ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_prod = $_GET['id'];

    $recupprods = $conn->prepare("SELECT * FROM prodUser WHERE id_prod = :id_prod ");
    $recupprods->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
    $recupprods->execute();

    if ($prods = $recupprods->fetch()) {
        // Récupérer les champs du produit
        $nom_prod = $prods['nomArt'];
        $description_prod = $prods['desProd'];
        $prix_prod = $prods['PrixProd'];
        $type_prod = $prods['typeProd'];
        $conditionnement_prod = $prods['condProd'];
        $format_prod = $prods['formatProd'];
        $quantite_prodmin = $prods['qteProd_min'];
        $quatite_promax = $prods['qteProd_max'];
        $livraison_prod = $prods['LivreCapProd'];

        $id_vendeur = $prods['id_user'];
    }
}

$errorMsg = '';
$successMsg = '';

// Traitement du formulaire d'achat direct
if (isset($_POST['submit'])) {
    // Valider les données du formulaire
    $quantite = filter_var($_POST['Quantité'], FILTER_VALIDATE_INT);
    $localite = htmlspecialchars($_POST['local']);
    $description = htmlspecialchars($_POST['desProd']);

    if ($quantite === false || $quantite <= 0) {
        $errorMsg = 'Veuillez saisir une quantité valide.';
    } elseif (empty($localite) || empty($description)) {
        $errorMsg = 'Veuillez remplir tous les champs.';
    } else {
        // Préparer la requête d'insertion avec une requête préparée
        $insertQuery = $conn->prepare("INSERT INTO notifUser (message, localite, quantiteProd, id_user, id_trader, id_prod) VALUES (:description, :localite, :quantite, :id_user, :id_trader , :id_prod)");

        // Associer les valeurs aux paramètres de la requête
        $insertQuery->bindParam(':description', $description, PDO::PARAM_STR);
        $insertQuery->bindParam(':localite', $localite, PDO::PARAM_STR);
        $insertQuery->bindParam(':quantite', $quantite, PDO::PARAM_INT);
        $insertQuery->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $insertQuery->bindParam(':id_trader', $id_vendeur, PDO::PARAM_INT);
        $insertQuery->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);

        // Exécuter la requête
        if ($insertQuery->execute()) {
            $successMsg = "Les données ont été insérées avec succès.";
        } else {
            // Loguer l'erreur dans un fichier de journal
            error_log("Erreur lors de l'insertion des données dans la table notifUser : " . $insertQuery->errorInfo()[2]);

            // Afficher un message d'erreur générique
            $errorMsg = "Une erreur s'est produite. Veuillez réessayer plus tard.";
        }
    }
}


//achat grouypé

if (isset($_POST['submitG'])) {

    $quantite = filter_var($_POST['Quantité'], FILTER_VALIDATE_INT);
    $localite = htmlspecialchars($_POST['local']);

    if ($quantite === false || $quantite <= 0) {
        $errorMsg = 'Veuillez saisir une quantité valide.';
    } elseif (empty($localite)) {
        $errorMsg = 'Veuillez remplir tous les champs.';
    } else {
        $insertGroup = $conn->prepare('INSERT INTO achatGroup (quantiteProd, localite, id_user, id_trader, id_prod) VALUES (:quantite, :localite, :id_user, :id_trader, :id_prod)');



        $insertGroup->bindParam(':localite', $localite, PDO::PARAM_STR);
        $insertGroup->bindParam(':quantite', $quantite, PDO::PARAM_INT);
        $insertGroup->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $insertGroup->bindParam(':id_trader', $id_vendeur, PDO::PARAM_INT);
        $insertGroup->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);

        if ($insertGroup->execute()) {
            $successMsg = "Les données ont été insérées avec succès.";
        } else {
            // Loguer l'erreur dans un fichier de journal
            error_log("Erreur lors de l'insertion des données dans la table notifUser : " . $insertGroup->errorInfo()[2]);

            // Afficher un message d'erreur générique
            $errorMsg = "Une erreur s'est produite. Veuillez réessayer plus tard.";
        }
    }
}

// recuperer le nombre de ligne achat groupé

$recupGroup = $conn->prepare("SELECT DISTINCT id_user FROM achatGroup WHERE id_prod = :id_prod");
$recupGroup->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
$recupGroup->execute();

$nombreGroup = $recupGroup->rowCount();

//Recuper la quantité 

$recupQuantite = $conn->prepare("SELECT SUM(quantiteProd) AS total_quantite FROM achatGroup WHERE id_prod = :id_prod");
$recupQuantite->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
$recupQuantite->execute();

$total_quantite = $recupQuantite->fetchColumn();


// recuperer la date la plus encienne

$recupDatePlusAncienne = $conn->prepare("SELECT MIN(date_ajout) AS date_plus_ancienne FROM achatGroup WHERE id_prod = :id_prod");
$recupDatePlusAncienne->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
$recupDatePlusAncienne->execute();

$resultatDate = $recupDatePlusAncienne->fetch(PDO::FETCH_ASSOC);

$datePlusAncienne = $resultatDate['date_plus_ancienne'];


$dateDuJour = date("Y-m-d H:i:s");
$tempEcoule = date("Y-m-d H:i:s", strtotime($datePlusAncienne . "+5 days"));

// Insérer les données dans la table notifUser
if ($dateDuJour > $tempEcoule) {
    $checkNotification = $conn->prepare("SELECT COUNT(*) FROM notifUser WHERE id_trader = :id_trader AND id_prod = :id_prod");
    $checkNotification->bindParam(':id_trader', $id_vendeur, PDO::PARAM_INT);
    $checkNotification->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
    $checkNotification->execute();

    $count = $checkNotification->fetchColumn();

    // Si aucune notification similaire n'existe, alors insérer la nouvelle notification
    if ($count == 0) {
        $inserNotif =  $conn->prepare("INSERT INTO notifUser (message, quantiteProd, confirm, id_trader, id_prod) VALUES (?, ?, ?, ?, ? )");
        $inserNotif->execute(['Vous avez un achat groupé sur cet article', $total_quantite, 'groupeDirect', $id_vendeur, $id_prod]);


        // Supprimer les données de la table achatGroup avec id_prod = $id_prod
        $supprimerAchatGroup = $conn->prepare("DELETE FROM achatGroup WHERE id_prod = :id_prod");
        $supprimerAchatGroup->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
        $supprimerAchatGroup->execute();
    }
}


// Requête préparée pour récupérer les user_id et les noms correspondants dans la table consproduser pour les consommateur
$sql = "SELECT id_user, nom_art FROM consproduser WHERE nom_art = :nom_prod";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':nom_prod', $nom_prod, PDO::PARAM_STR);
$stmt->execute();

// Initialisation du tableau pour stocker les données récupérées
$data = array();

$countUser = 0;

// Vérification du nombre de lignes retournées par la requête
if ($stmt->rowCount() > 0) {
    // Récupération de tous les user_id et les noms correspondants
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Stockage des données dans le tableau $data
        $data[] = $row;
    }

    // Initialisation du tableau associatif pour stocker les user_id par nom
    $user_ids_by_name = array();

    // Regrouper les user_id par nom
    foreach ($data as $row) {
        $user_id = $row['id_user'];
        $nom_art = $row['nom_art'];
        if ($user_id != $id_user) {
            if (!isset($user_ids_by_name[$nom_art])) {
                $user_ids_by_name[$nom_art] = array();
            }
            $user_ids_by_name[$nom_art][] = $user_id;
        }
    }

    // Initialisation de $countUser à zéro par défaut
   


    // Requête préparée pour compter le nombre d'utilisateurs distincts
    $sql_count = "SELECT COUNT(DISTINCT id_user) AS count FROM consproduser WHERE nom_art = :nom_prod_count";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bindParam(':nom_prod_count', $nom_prod, PDO::PARAM_STR);
    $stmt_count->execute();

    // Récupération du nombre d'utilisateurs distincts
    $countUser = $stmt_count->fetchColumn();


}

// Requête préparée pour récupérer les user_id et les noms correspondants dans la table produser pour les vendeurs du produits
$sql = "SELECT id_user, nomArt FROM produser WHERE nomArt = :nom_prod";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':nom_prod', $nom_prod, PDO::PARAM_STR);
$stmt->execute();

// Initialisation du tableau pour stocker les données récupérées
$data = array();

$countproduser = 0;

// Vérification du nombre de lignes retournées par la requête
if ($stmt->rowCount() > 0) {
    // Récupération de tous les user_id et les noms correspondants
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Stockage des données dans le tableau $data
        $data[] = $row;
    }

    // Initialisation du tableau associatif pour stocker les user_id par nom
    $user_ids_by_name = array();

    // Regrouper les user_id par nom
    foreach ($data as $row) {
        $user_id = $row['id_user'];
        $nomArt = $row['nomArt'];
        if ($user_id != $id_user) {
            if (!isset($user_ids_by_name[$nomArt])) {
                $user_ids_by_name[$nomArt] = array();
            }
            $user_ids_by_name[$nomArt][] = $user_id;
        }
    }

    // Requête préparée pour compter le nombre d'utilisateurs distincts, en excluant l'ID de la session active
    $sql_count = "SELECT COUNT(DISTINCT id_user) AS count FROM produser WHERE nomArt = :nom_prod_count AND id_user != :id_user";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bindParam(':nom_prod_count', $nom_prod, PDO::PARAM_STR);
    $stmt_count->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt_count->execute();

    // Récupération du nombre d'utilisateurs distincts
    $countproduser = $stmt_count->fetchColumn();
}



//
if (isset($_POST['submitO'])) {
    $message = htmlspecialchars($_POST['message']);
    $id_prod = $_GET['id']; // Récupérer l'identifiant du produit

    // Insérer les user_id dans la table notifuser pour chaque utilisateur
    foreach ($user_ids_by_name as $nom_art => $user_ids) {
        foreach ($user_ids as $userid) {
            $sql_insert = "INSERT INTO notifUser (message, confirm, id_user, id_trader, id_prod) VALUES (:message, 'offre', :id_user , :id_trader, :id_prod)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->execute([
                ':message' => $message,
                'id_user' => $id_user,
                ':id_trader' => $userid,
                ':id_prod' => $id_prod
            ]);
        }
    }
}

if (isset($_POST['submitX'])) {
    $message2 = htmlspecialchars($_POST['message2']);
    $id_prod = $_GET['id']; // Récupérer l'identifiant du produit

    // Insérer les user_id dans la table notifuser pour chaque utilisateur
    foreach ($user_ids_by_name as $nom_art => $user_ids) {
        foreach ($user_ids as $userid) {

            $sql_insert = "INSERT INTO notifUser (message, confirm, id_user, id_trader, id_prod) VALUES (:message2, 'offreNegos', :id_user , :id_trader, :id_prod)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->execute([
                ':message2' => $message2,
                ':id_user' => $id_user, // Utilisation de ':id_user' au lieu de '$id_user'
                ':id_trader' => $userid,
                ':id_prod' => $id_prod,
            ]);

            // Ajout du commentaire
            $comment_insert = $conn->prepare("INSERT INTO comment (prixTrade, id_trader, id_prod) VALUES (:prixTrade, :id_trader, :id_prod)");

            $comment_insert->execute([
                ':prixTrade' => null,
                ':id_trader' => $userid,
                ':id_prod' => $id_prod
            ]);
        }
    }
}

if (isset($_POST['submitY'])) {
    $message2 = htmlspecialchars($_POST['message2']);
    $id_prod = $_GET['id']; // Récupérer l'identifiant du produit

    // Insérer les user_id dans la table notifuser pour chaque utilisateur
    foreach ($user_ids_by_name as $nom_art => $user_ids) {
        foreach ($user_ids as $userid) {

            $sql_insert = "INSERT INTO notifUser (message, confirm, id_user, id_trader, id_prod) VALUES (:message2, 'offreGroup', :id_user , :id_trader, :id_prod)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->execute([
                ':message2' => $message2,
                ':id_user' => $id_user, // Utilisation de ':id_user' au lieu de '$id_user'
                ':id_trader' => $userid,
                ':id_prod' => $id_prod
            ]);

            
        }
    }
}

if (isset($_POST['submitZ'])) {
    $message2 = htmlspecialchars($_POST['message2']);
    $id_prod = $_GET['id']; // Récupérer l'identifiant du produit

    // Insérer les user_id dans la table notifuser pour chaque utilisateur
    foreach ($user_ids_by_name as $nom_art => $user_ids) {
        foreach ($user_ids as $userid) {

            $sql_insert = "INSERT INTO notifUser (message, confirm, id_user, id_trader, id_prod) VALUES (:message2, 'offreGroupNegos', :id_user , :id_trader, :id_prod)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->execute([
                ':message2' => $message2,
                ':id_user' => $id_user, // Utilisation de ':id_user' au lieu de '$id_user'
                ':id_trader' => $userid,
                ':id_prod' => $id_prod
            ]);
        }
    }
}






?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link href="assets/images/favicon.png" rel="icon" type="image/png">

    <!-- title and description-->
    <title>Socialite</title>
    <meta name="description" content="Socialite - Social sharing network HTML Template">

    <!-- css files -->
    <link rel="stylesheet" href="assets/css/tailwind.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- google font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<body>

    <div id="wrapper">

        <!-- header -->
        <header class="z-[100] h-[--m-top] fixed top-0 left-0 w-full flex items-center bg-white/80 sky-50 backdrop-blur-xl border-b border-slate-200 dark:bg-dark2 dark:border-slate-800">

            <div class="flex items-center w-full xl:px-6 px-2 max-lg:gap-10">

                <div class="2xl:w-[--w-side] lg:w-[--w-side-sm]">

                    <!-- left -->
                    <div class="flex items-center gap-1">

                        <!-- icon menu -->
                        <button uk-toggle="target: #site__sidebar ; cls :!-translate-x-0" class="flex items-center justify-center w-8 h-8 text-xl rounded-full hover:bg-gray-100 xl:hidden dark:hover:bg-slate-600 group">
                            <ion-icon name="menu-outline" class="text-2xl group-aria-expanded:hidden"></ion-icon>
                            <ion-icon name="close-outline" class="hidden text-2xl group-aria-expanded:block"></ion-icon>
                        </button>
                        <div id="logo">
                            <a href="feed.html">
                                <img src="assets/images/logo.png" alt="" class="w-28 md:block hidden dark:!hidden">
                                <img src="assets/images/logo-light.png" alt="" class="dark:md:block hidden">
                                <img src="assets/images/logo-mobile.png" class="hidden max-md:block w-20 dark:!hidden" alt="">
                                <img src="assets/images/logo-mobile-light.png" class="hidden dark:max-md:block w-20" alt="">
                            </a>
                        </div>

                    </div>

                </div>
                <div class="flex-1 relative">

                    <div class="max-w-[1220px] mx-auto flex items-center">

                        <!-- search -->


                        <!-- header icons -->
                        <div class="flex items-center sm:gap-4 gap-2 absolute right-5 top-1/2 -translate-y-1/2 text-black">
                            <!-- create -->
                            <button type="button" class="sm:p-2 p-1 rounded-full relative sm:bg-secondery dark:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 max-sm:hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15">
                                    </path>
                                </svg>
                                <ion-icon name="add-circle-outline" class="sm:hidden text-2xl "></ion-icon>
                            </button>
                            <div class="hidden bg-white p-4 rounded-lg overflow-hidden drop-shadow-xl dark:bg-slate-700 md:w-[324px] w-screen border2" uk-drop="offset:6;pos: bottom-right; mode: click; animate-out: true; animation: uk-animation-scale-up uk-transform-origin-top-right ">

                                <h3 class="font-bold text-md"> Ajouter </h3>

                                <!-- slider -->
                                <div class="mt-4" tabindex="-1" uk-slider="finite:true;sets: true">

                                    <div class="uk-slider-container pb-1">
                                        <ul class="uk-slider-items grid-small" uk-scrollspy="target: > li; cls: uk-animation-scale-up , uk-animation-slide-right-small; delay: 20 ;repeat: true">
                                            <a href="addprod.php">
                                                <li class="w-40" uk-scrollspy-class="uk-animation-fade">
                                                    <div class="p-3 px-4 rounded-lg bg-teal-100/60 text-teal-600 dark:text-white dark:bg-dark4">
                                                        <ion-icon name="cart" class="text-2xl drop-shadow-md"></ion-icon>
                                                        <div class="mt-1.5 text-sm font-medium"> Produit </div>
                                                    </div>
                                                </li>
                                            </a>
                                            <a href="addcons.php">
                                                <li class="w-40">
                                                    <div class="p-3 px-4 rounded-lg bg-sky-100/60 text-sky-600 dark:text-white dark:bg-dark4">
                                                        <ion-icon name="pricetags" class="text-2xl drop-shadow-md"></ion-icon>

                                                        <div class="mt-1.5 text-sm font-medium"> Consommation </div>
                                                    </div>
                                                </li>
                                            </a>
                                            <a href="appelof.php">
                                                <li class="w-40">
                                                    <div class="p-3 px-4 rounded-lg bg-purple-100/60 text-purple-600 dark:text-white dark:bg-dark4">

                                                        <ion-icon name="logo-capacitor" class="text-2xl drop-shadow-md"></ion-icon>
                                                        <div class="mt-1.5 text-sm font-medium"> Appel d'offre </div>
                                                    </div>
                                                </li>
                                            </a>
                                        </ul>
                                    </div>


                                    <!-- slide nav icons -->
                                    <div class="dark:hidden">
                                        <a class="absolute -translate-y-1/2 top-1/2 -left-4 flex items-center w-8 h-full px-1.5 justify-start bg-gradient-to-r from-white via-white dark:from-slate-600 dark:via-slate-500 dark:from-transparent dark:via-transparent" href="#" uk-slider-item="previous"> <ion-icon name="chevron-back" class="text-xl dark:text-white"></ion-icon> </a>
                                        <a class="absolute -translate-y-1/2 top-1/2 -right-4 flex items-center w-8 h-full px-1.5 justify-end bg-gradient-to-l from-white via-white dark:from-transparent dark:via-transparent" href="#" uk-slider-item="next"> <ion-icon name="chevron-forward" class="text-xl dark:text-white"></ion-icon> </a>
                                    </div>


                                    <!-- slide nav -->
                                    <div class="justify-center mt-2 -mb-2 hidden dark:flex">
                                        <ul class="inline-flex flex-wrap justify-center gap-1 uk-dotnav uk-slider-nav">
                                        </ul>
                                    </div>

                                </div>

                                <!-- list -->


                            </div>

                            <!-- notification -->
                            <button type="button" class="sm:p-2 p-1 rounded-full relative sm:bg-secondery dark:text-white" uk-tooltip="title: Notification; pos: bottom; offset:6">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 max-sm:hidden">
                                    <path d="M5.85 3.5a.75.75 0 00-1.117-1 9.719 9.719 0 00-2.348 4.876.75.75 0 001.479.248A8.219 8.219 0 015.85 3.5zM19.267 2.5a.75.75 0 10-1.118 1 8.22 8.22 0 011.987 4.124.75.75 0 001.48-.248A9.72 9.72 0 0019.266 2.5z" />
                                    <path fill-rule="evenodd" d="M12 2.25A6.75 6.75 0 005.25 9v.75a8.217 8.217 0 01-2.119 5.52.75.75 0 00.298 1.206c1.544.57 3.16.99 4.831 1.243a3.75 3.75 0 107.48 0 24.583 24.583 0 004.83-1.244.75.75 0 00.298-1.205 8.217 8.217 0 01-2.118-5.52V9A6.75 6.75 0 0012 2.25zM9.75 18c0-.034 0-.067.002-.1a25.05 25.05 0 004.496 0l.002.1a2.25 2.25 0 11-4.5 0z" clip-rule="evenodd" />
                                </svg>
                                <div class="absolute top-0 right-0 -m-1 bg-red-600 text-white text-xs px-1 rounded-full">
                                    6</div>
                                <ion-icon name="notifications-outline" class="sm:hidden text-2xl"></ion-icon>
                            </button>
                            <div class="hidden bg-white pr-1.5 rounded-lg drop-shadow-xl dark:bg-slate-700 md:w-[365px] w-screen border2" uk-drop="offset:6;pos: bottom-right; mode: click; animate-out: true; animation: uk-animation-scale-up uk-transform-origin-top-right ">

                                <!-- heading -->
                                <div class="flex items-center justify-between gap-2 p-4 pb-2">
                                    <h3 class="font-bold text-xl"> Notifications </h3>

                                    <div class="flex gap-2.5">
                                        <button type="button" class="p-1 flex rounded-full focus:bg-secondery dark:text-white"> <ion-icon class="text-xl" name="ellipsis-horizontal"></ion-icon> </button>
                                        <div class="w-[280px] group" uk-dropdown="pos: bottom-right; animation: uk-animation-scale-up uk-transform-origin-top-right; animate-out: true; mode: click; offset:5">
                                            <nav class="text-sm">
                                                <a href="#"> <ion-icon class="text-xl shrink-0" name="checkmark-circle-outline"></ion-icon> Mark all as read</a>
                                                <a href="#"> <ion-icon class="text-xl shrink-0" name="settings-outline"></ion-icon> Notification setting</a>
                                                <a href="#"> <ion-icon class="text-xl shrink-0" name="notifications-off-outline"></ion-icon> Mute Notification
                                                </a>
                                            </nav>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-sm h-[400px] w-full overflow-y-auto pr-2">

                                    <!-- contents list -->
                                    <div class="pl-2 p-1 text-sm font-normal dark:text-white">

                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10 bg-teal-500/5">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-3.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> Alexa Gray</b> started following you.
                                                    Welcome him to your profile. 👋 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 4 hours
                                                    ago </div>
                                                <div class="w-2.5 h-2.5 bg-teal-600 rounded-full absolute right-3 top-5">
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-7.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1">Jesse Steeve</b> mentioned you in a story.
                                                    Check it out and reply. 📣 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 8 hours
                                                    ago </div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-6.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> Alexa stella</b> commented on your photo
                                                    “Wow, stunning shot!” 💬 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 8 hours
                                                    ago </div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-2.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> John Michael</b> who you might know, is
                                                    on socialite.</p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 2 hours
                                                    ago </div>
                                            </div>
                                            <button type="button" class="button text-white bg-primary">fallow</button>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10 bg-teal-500/5">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-3.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> Sarah Gray</b> sent you a message. He
                                                    wants to chat with you. 💖 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 4 hours
                                                    ago </div>
                                                <div class="w-2.5 h-2.5 bg-teal-600 rounded-full absolute right-3 top-5">
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-4.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> Jesse Steeve</b> sarah tagged you <br> in
                                                    a photo of your birthday party. 📸 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 8 hours
                                                    ago </div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-2.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> Lewis Lewis</b> mentioned you in a story.
                                                    Check it out and reply. 📣 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 8 hours
                                                    ago </div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-7.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> Martin Gray</b> liked your photo of the
                                                    Eiffel Tower. 😍 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 8 hours
                                                    ago </div>
                                            </div>
                                        </a>

                                    </div>

                                </div>


                                <!-- footer -->
                                <a href="#">
                                    <div class="text-center py-4 border-t border-slate-100 text-sm font-medium text-blue-600 dark:text-white dark:border-gray-600">
                                        View Notifications </div>
                                </a>

                                <div class="w-3 h-3 absolute -top-1.5 right-3 bg-white border-l border-t rotate-45 max-md:hidden dark:bg-dark3 dark:border-transparent">
                                </div>
                            </div>

                            <!-- messages -->


                            <!-- profile -->
                            <div class="rounded-full relative bg-secondery cursor-pointer shrink-0">
                                <img src="assets/images/avatars/avatar-2.jpg" alt="" class="sm:w-9 sm:h-9 w-7 h-7 rounded-full shadow shrink-0">
                            </div>
                            <div class="hidden bg-white rounded-lg drop-shadow-xl dark:bg-slate-700 w-64 border2" uk-drop="offset:6;pos: bottom-right;animate-out: true; animation: uk-animation-scale-up uk-transform-origin-top-right ">

                                <a href="timeline.html">
                                    <div class="p-4 py-5 flex items-center gap-4">
                                        <img src="assets/images/avatars/avatar-2.jpg" alt="" class="w-10 h-10 rounded-full shadow">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-black">
                                                <?= $nom_client ?>
                                            </h4>
                                            <div class="text-sm mt-1 text-blue-600 font-light dark:text-white/70">
                                                <?= '@' . $username_client ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <hr class="dark:border-gray-600/60">

                                <nav class="p-2 text-sm text-black font-normal dark:text-white">

                                    <a href="setting.html">
                                        <div class="flex items-center gap-2.5 hover:bg-secondery p-2 px-2.5 rounded-md dark:hover:bg-white/10">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                            </svg>
                                            Porte-feuille
                                        </div>
                                    </a>
                                    <a href="setting.html">
                                        <div class="flex items-center gap-2.5 hover:bg-secondery p-2 px-2.5 rounded-md dark:hover:bg-white/10">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46" />
                                            </svg>
                                            Promotion
                                        </div>
                                    </a>
                                    <a href="profile.php">
                                        <div class="flex items-center gap-2.5 hover:bg-secondery p-2 px-2.5 rounded-md dark:hover:bg-white/10">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a9 9 0 0 0 5-1.5 4 4 0 0 0-4-3.5h-2a4 4 0 0 0-4 3.5 9 9 0 0 0 5 1.5Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            Profile
                                        </div>
                                    </a>

                                    <hr class="-mx-2 my-2 dark:border-gray-600/60">
                                    <a href="../../../logout.php">
                                        <div class="flex items-center gap-2.5 hover:bg-secondery p-2 px-2.5 rounded-md dark:hover:bg-white/10">
                                            <svg class="w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                </path>
                                            </svg>
                                            Se deconnecter
                                        </div>
                                    </a>

                                </nav>

                            </div>

                            <div class="flex items-center gap-2 hidden">

                                <img src="assets/images/avatars/avatar-2.jpg" alt="" class="w-9 h-9 rounded-full shadow">

                                <div class="w-20 font-semibold text-gray-600"> Hamse </div>

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </header>

        <!-- sidebar -->
        <div id="site__sidebar" class="box fixed top-0 left-0 z-[99] pt-[--m-top] overflow-hidden transition-transform xl:duration-500 max-xl:w-72 max-xl:-translate-x-full">

            <!-- sidebar inner -->
            <div class=" p-4 max-xl:bg-white shadow-sm 2xl:w-72 sm:w-64 w-[80%] h-[calc(100vh-64px)] relative z-30 max-lg:border-r dark:max-xl:!bg-slate-700 dark:border-slate-700">

                <div class=" pr-4" data-simplebar>

                    <nav id="side">

                        <ul>
                            <li>
                                <a href="user_page.php">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M11.3 3.3a1 1 0 0 1 1.4 0l6 6 2 2a1 1 0 0 1-1.4 1.4l-.3-.3V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3c0 .6-.4 1-1 1H7a2 2 0 0 1-2-2v-6.6l-.3.3a1 1 0 0 1-1.4-1.4l2-2 6-6Z" clip-rule="evenodd" />
                                    </svg>

                                    <span> Acceuil </span>
                                </a>
                            </li>
                            <li>
                                <a href="notif.php">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.1 12.6v-1.8A5.4 5.4 0 0 0 13 5.6V3a1 1 0 0 0-2 0v2.4a5.4 5.4 0 0 0-4 5.5v1.8c0 2.4-1.9 3-1.9 4.2 0 .6 0 1.2.5 1.2h13c.5 0 .5-.6.5-1.2 0-1.2-1.9-1.8-1.9-4.2Zm-13.2-.8a1 1 0 0 1-1-1c0-2.3.9-4.6 2.5-6.4a1 1 0 1 1 1.5 1.4 7.4 7.4 0 0 0-2 5 1 1 0 0 1-1 1Zm16.2 0a1 1 0 0 1-1-1c0-1.8-.7-3.6-2-5a1 1 0 0 1 1.5-1.4c1.6 1.8 2.5 4 2.5 6.4a1 1 0 0 1-1 1ZM8.8 19a3.5 3.5 0 0 0 6.4 0H8.8Z" />
                                    </svg>

                                    <span> Notifications </span>
                                </a>
                            </li>
                            <li>
                                <a href="listprod.php">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.3 6A2 2 0 0 0 14 9h1v1a2 2 0 0 0 3 1.7l-.3 1.5a1 1 0 0 1-1 .8h-8l.2 1H16a3 3 0 1 1-2.8 2h-2.4a3 3 0 1 1-4-1.8L4.7 5H4a1 1 0 0 1 0-2h1.5c.5 0 .9.3 1 .8L6.9 6h5.4Z" />
                                        <path d="M18 4a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0V8h2a1 1 0 1 0 0-2h-2V4Z" />
                                    </svg>

                                    <span> Mes produits </span>
                                </a>
                            </li>
                            <li>
                                <a href="liqstcons.php">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 0 0-2 2v12.6l3-8a1 1 0 0 1 1-.6h12V9a2 2 0 0 0-2-2h-4.5l-2-2.3A2 2 0 0 0 8 4H4Zm2.7 8h-.2l-3 8H18l3-8H6.7Z" clip-rule="evenodd" />
                                    </svg>

                                    <span> Consommation </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 0 0-2 2v10c0 1.1.9 2 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H4Zm0 6h16v6H4v-6Z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M5 14c0-.6.4-1 1-1h2a1 1 0 1 1 0 2H6a1 1 0 0 1-1-1Zm5 0c0-.6.4-1 1-1h5a1 1 0 1 1 0 2h-5a1 1 0 0 1-1-1Z" clip-rule="evenodd" />
                                    </svg>

                                    <span> Porte-feuille </span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M2 12a10 10 0 1 1 20 0 10 10 0 0 1-20 0Zm11-4a1 1 0 1 0-2 0v4c0 .3.1.5.3.7l3 3a1 1 0 0 0 1.4-1.4L13 11.6V8Z" clip-rule="evenodd" />
                                    </svg>

                                    <span> Historique </span>
                                </a>
                            </li>



                        </ul>



                    </nav>



                    <nav id="side" class="font-medium text-sm text-black border-t pt-3 mt-2 dark:text-white dark:border-slate-800">
                        <div class="px-3 pb-2 text-sm font-medium">
                            <div class="text-black dark:text-white">Pages</div>
                        </div>

                        <ul class="mt-2 -space-y-2" uk-nav="multiple: true">

                            <li>
                                <a href="profile.php">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a9 9 0 0 0 5-1.5 4 4 0 0 0-4-3.5h-2a4 4 0 0 0-4 3.5 9 9 0 0 0 5 1.5Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span> Profile </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                    </svg>
                                    <span> Upgrade </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                    </svg>
                                    <span> Authentication </span>
                                </a>
                            </li>
                            <li class="uk-parent">
                                <a href="#" class="group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                    </svg>
                                    <span> Development </span>
                                    <ion-icon name="chevron-down" class="text-base ml-auto duration-200 group-aria-expanded:rotate-180"></ion-icon>
                                </a>
                                <ul class="pl-10 my-1 space-y-0 text-sm">
                                    <li><a href="components.html" class="!py-2 !rounded -md">Elements</a></li>
                                    <li><a href="components.html" class="!py-2 !rounded -md">Components</a></li>
                                    <li><a href="components.html" class="!py-2 !rounded -md">Icons</a></li>
                                </ul>
                            </li>

                        </ul>

                    </nav>


                    <div class="text-xs font-medium flex flex-wrap gap-2 gap-y-0.5 p-2 mt-2">
                        <a href="#" class="hover:underline">About</a>
                        <a href="#" class="hover:underline">Blog </a>
                        <a href="#" class="hover:underline">Careers</a>
                        <a href="#" class="hover:underline">Support</a>
                        <a href="#" class="hover:underline">Contact Us </a>
                        <a href="#" class="hover:underline">Developer</a>
                    </div>

                </div>

            </div>

            <!-- sidebar overly -->
            <div id="site__sidebar__overly" class="absolute top-0 left-0 z-20 w-screen h-screen xl:hidden backdrop-blur-sm" uk-toggle="target: #site__sidebar ; cls :!-translate-x-0">
            </div>
        </div>



        <!-- main contents -->


        <main id="site__main" class="2xl:ml-[--w-side]  xl:ml-[--w-side-sm] p-5 h-[calc(100vh-var(--m-top))] mt-[--m-top]">

            <div class="mb-3">
                <h1 class=" text-center font-bold text-2xl">DETAILS DU PRODUITS</h1>
            </div>

            <div class="lg:flex 2xl:gap-16 gap-12 max-w-[1065px] mx-auto" id="js-oversized">

                <!-- search -->
                <div class="mb-4 flex-1 mx-auto  ">

                    <div class="md:max-w-[650px] mx-auto flex-1 xl:space-y-6 space-y-3">

                        <div class="flex items-center py-3 dark:border-gray-600 my-3">

                            <!--  TITRE DU PRODUIT  -->
                            <h1 class="text-xl"><?= $nom_prod ?></h1>

                        </div>
                    </div>

                    <div class="mb-4 grid sm:grid-cols-2 gap-3" uk-scrollspy="target: > div; cls: uk-animation-scale-up; delay: 100 ;repeat: true">

                        <div class="card flex space-x-5 p-5">
                            <div class="card-body flex-1 p-0">
                                <h4 class="card-title "> Type du produit </h4>
                                <p><?= $type_prod ?></p>
                            </div>
                        </div>
                        <div class="card flex space-x-5 p-5">
                            <div class="card-body flex-1 p-0">
                                <h4 class="card-title"> conditionnement </h4>
                                <p><?= $conditionnement_prod ?></p>
                            </div>
                        </div>

                        <div class="card flex space-x-5 p-5">
                            <div class="card-body flex-1 p-0">
                                <h4 class="card-title"> format </h4>
                                <p><?= $format_prod ?></p>
                            </div>
                        </div>
                        <div class="card flex space-x-5 p-5">
                            <div class="card-body flex-1 p-0">
                                <h4 class="card-title"> Quantité traité</h4>
                                <p>[<?= $quantite_prodmin ?> - <?= $quatite_promax ?>]</p>
                            </div>
                        </div>
                        <div class="card flex space-x-5 p-5">
                            <div class="card-body flex-1 p-0">
                                <h4 class="card-title"> Prix par unité </h4>
                                <p><?= $prix_prod ?></p>
                            </div>
                        </div>
                        <div class="card flex space-x-5 p-5">
                            <div class="card-body flex-1 p-0">
                                <h4 class="card-title">Capacité de livré</h4>
                                <p><?= $livraison_prod ?></p>
                            </div>
                        </div>
                        <div class="card flex space-x-5 p-5">
                            <div class="card-body flex-1 p-0">
                                <h4 class="card-title"> Zone economique </h4>
                                <p><?= $ActivZone_user ?></p>
                            </div>
                        </div>
                        <div class="card flex space-x-5 p-5">
                            <div class="card-body flex-1 p-0">
                                <h4 class="card-title"> Ville, Commune</h4>
                                <p><?= $local_user ?>, <?= $adress_user  ?></p>
                            </div>
                        </div>

                    </div>
                    <div class=" card flex space-x-5 p-5">
                        <div class="card-body flex-1 p-0">
                            <h4 class="card-title"> Description</h4>
                            <p><?= $description_prod ?></p>
                        </div>
                    </div>
                </div>

                <!-- image -->

                <div class="flex-1 items-center justify-center">

                    <div class="flex items-center flex-col lg:space-y-4 lg:pb-8 max-lg:w-full  sm:grid-cols-2 max-lg:gap-6 sm:mt-2" uk-sticky="media: 1024; end: #js-oversized; offset: 80">

                        <div class=" p-5 m-5  px-6 border1 dark:bg-dark2">
                            <?php if (!empty($prods['imgProd'])) : ?>
                                <img src="<?= $prods['imgProd'] ?>" alt="Image du produit">
                            <?php endif; ?>
                        </div>

                        <?php
                        // Vérifie si les variables $id_user et $id_vendeur sont définies et qu'elles ne sont pas égales
                        if (isset($id_user) && isset($id_vendeur) && $id_user != $id_vendeur) :
                        ?>
                            <!-- Si les conditions ci-dessus sont remplies, affiche ces éléments -->
                            <div class="flex flex-col justify-center items-center mt-4 w-[300px]">
                                <!-- Utilisation de flexbox pour centrer verticalement -->
                                <a href="#" uk-toggle="target: #achatd" class="w-full p-2 m-2 text-center text-white text-sm bg-green-500 rounded">Achat Direct</a>
                                <a href="#" uk-toggle="target: #achatg" class="w-full p-2 m-2 text-center text-white text-sm bg-blue-500 rounded">Achat Groupé<?php if ($nombreGroup > 0) { ?>
                                    (<?= $nombreGroup ?> participant<?php if ($nombreGroup > 1) { ?>s<?php } ?>)
                                <?php } ?>
                                </a>

                            </div>
                        <?php else : ?>
                            <!-- Si les conditions ci-dessus ne sont pas remplies, affiche ce message -->
                            <p class="text-center mt-4 text-gray-500">Ce produit vous appartient</p>

                            <a href="#" uk-toggle="target: #modal" class="w-1/2 py-2 m-2 text-center text-sm bg-teal-100/60 text-teal-600  rounded">Faire une offre</a>
                            <a href="#" uk-toggle="target: #modalx" class="w-1/2 py-2 m-2 text-center  text-sm bg-teal-100/60 text-teal-600  rounded">Faire une offre negocié</a>
                            <a href="#" uk-toggle="target: #modaly" class="w-1/2 py-2 m-2 text-center  text-sm bg-teal-100/60 text-teal-600  rounded">Faire une offre grouper</a>
                            <a href="#" uk-toggle="target: #modalz" class="w-1/2 py-2 m-2 text-center  text-sm bg-teal-100/60 text-teal-600  rounded">Faire une offre grouper  negocié</a>
                        <?php endif; ?>

                        <!-- faire une offre direct form  -->
                        <div class="lg:p-20 p-10" id="modal" uk-modal>

                            <div class="uk-modal-dialog tt relative mx-auto bg-white rounded-lg shadow-xl w-[400px]">

                                <div class="p-6">
                                    <h2 class="text-xl font-semibold">Faire une offre</h2>
                                </div>

                                <!-- Ajout de la balise de formulaire -->
                                <form method="post">
                                    <div class="p-6 py-0">
                                        <!-- Utilisation de la variable $count dans la balise p -->
                                        <p><?= $countUser ?> Clients ont ce produit dans leur liste de consommation</p>

                                        <!-- Déplacement de la balise input dans le formulaire -->
                                        <input type="text" name="message" class="w-full mt-3" placeholder="Écrire un message">

                                    </div>

                                    <div class="flex justify-end p-6 text-sm font-medium">
                                        <button class="px-4 py-1.5 rounded-md uk-modal-close" type="button">Annuler</button>
                                        <!-- Modification du bouton Envoyer pour qu'il soit de type "submit" -->
                                        <button class="px-5 py-1.5 bg-gray-100 rounded-md" type="submit" name="submitO">Envoyer</button>
                                    </div>
                                </form>

                                <button type="button" class="bg-white rounded-full p-2 absolute right-0 top-0 m-3 dark:bg-slate-600 uk-modal-close">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                            </div>

                        </div>

                        <!-- faire une offre grouper form -->
                        <div class="lg:p-20 p-10" id="modalx" uk-modal>

                            <div class="uk-modal-dialog tt relative mx-auto bg-white rounded-lg shadow-xl w-[400px]">

                                <div class="p-6">
                                    <h2 class="text-xl font-semibold">Faire une offre negocié</h2>
                                </div>

                                <!-- Ajout de la balise de formulaire -->
                                <form method="post">
                                    <div class="p-6 py-0">
                                        <!-- Utilisation de la variable $count dans la balise p -->
                                        <p><?= $countUser ?> Clients ont ce produit dans leur liste de consommation</p>

                                        <!-- Déplacement de la balise input dans le formulaire -->
                                        <input type="text" name="message2" class="w-full mt-3" placeholder="Écrire un message">

                                    </div>

                                    <div class="flex justify-end p-6 text-sm font-medium">
                                        <button class="px-4 py-1.5 rounded-md uk-modal-close" type="button">Annuler</button>
                                        <!-- Modification du bouton Envoyer pour qu'il soit de type "submit" -->
                                        <button class="px-5 py-1.5 bg-gray-100 rounded-md" type="submit" name="submitX">Envoyer</button>
                                    </div>
                                </form>

                                <button type="button" class="bg-white rounded-full p-2 absolute right-0 top-0 m-3 dark:bg-slate-600 uk-modal-close">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                            </div>

                        </div>

                        <!-- faire une offre grouper form -->
                        <div class="lg:p-20 p-10" id="modaly" uk-modal>

                            <div class="uk-modal-dialog tt relative mx-auto bg-white rounded-lg shadow-xl w-[400px]">

                                <div class="p-6">
                                    <h2 class="text-xl font-semibold">Faire une offre groupé</h2>
                                </div>

                                <!-- Ajout de la balise de formulaire -->
                                <form method="post">
                                    <div class="p-6 py-0">
                                        <!-- Utilisation de la variable $count dans la balise p -->
                                        <p> <?= $countproduser ?> fournisseur qui ont ce produit dans leur liste de produit</p>

                                        <!-- Déplacement de la balise input dans le formulaire -->
                                        <input type="text" name="message2" class="w-full mt-3" placeholder="Écrire un message">

                                    </div>

                                    <div class="flex justify-end p-6 text-sm font-medium">
                                        <button class="px-4 py-1.5 rounded-md uk-modal-close" type="button">Annuler</button>
                                        <!-- Modification du bouton Envoyer pour qu'il soit de type "submit" -->
                                        <button class="px-5 py-1.5 bg-gray-100 rounded-md" type="submit" name="submitY">Envoyer</button>
                                    </div>
                                </form>

                                <button type="button" class="bg-white rounded-full p-2 absolute right-0 top-0 m-3 dark:bg-slate-600 uk-modal-close">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                            </div>

                        </div>

                        <!-- faire une offre grouper negocier form -->
                        <div class="lg:p-20 p-10" id="modalz" uk-modal>

                            <div class="uk-modal-dialog tt relative mx-auto bg-white rounded-lg shadow-xl w-[400px]">

                                <div class="p-6">
                                    <h2 class="text-xl font-semibold">Faire une offre groupé negocier</h2>
                                </div>

                                <!-- Ajout de la balise de formulaire -->
                                <form method="post">
                                    <div class="p-6 py-0">
                                        <!-- Utilisation de la variable $count dans la balise p -->
                                        <p> <?= $countproduser ?> fournisseur qui ont ce produit dans leur liste de produit</p>

                                        <!-- Déplacement de la balise input dans le formulaire -->
                                        <input type="text" name="message2" class="w-full mt-3" placeholder="Écrire un message">

                                    </div>

                                    <div class="flex justify-end p-6 text-sm font-medium">
                                        <button class="px-4 py-1.5 rounded-md uk-modal-close" type="button">Annuler</button>
                                        <!-- Modification du bouton Envoyer pour qu'il soit de type "submit" -->
                                        <button class="px-5 py-1.5 bg-gray-100 rounded-md" type="submit" name="submitZ">Envoyer</button>
                                    </div>
                                </form>

                                <button type="button" class="bg-white rounded-full p-2 absolute right-0 top-0 m-3 dark:bg-slate-600 uk-modal-close">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                            </div>

                        </div>






                        <div class="lg:p-20 p-10" id="achatd" uk-modal>

                            <div class="relative mx-auto bg-white  rounded-lg shadow-xl uk-modal-dialog w-[400px]">

                                <form method="post">
                                    <div class="px-6 py-4 border-b">
                                        <h2 class="text-xl font-semibold">Achat direct</h2>
                                    </div>
                                    <div class="p-6 overflow-y-auto " uk-overflow-auto>
                                        <input type="number" class="w-full mb-3" placeholder="Quantité" name="Quantité">
                                        <input type="text" class="w-full mb-3" placeholder="Localité" name="local">
                                        <textarea class="w-full h-20" name="desProd" id="" cols="30" rows="10" placeholder="Description"></textarea>
                                    </div>
                                    <div class="flex justify-end p-6 text-sm font-medium px-6 py-4 border-t">
                                        <button class="px-4 py-1.5 rounded-md uk-modal-close" type="reset">Annuler</button>
                                        <button class="px-5 py-1.5 bg-gray-100 rounded-md " type="submit" name="submit">Envoyer</button>
                                    </div>
                                </form>


                                <!-- close button -->
                                <button type="button" class="bg-white rounded-full p-2 absolute right-0 top-0 m-3 dark:bg-slate-600 uk-modal-close">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                            </div>

                        </div>

                        <div class="lg:p-20 p-10" id="achatg" uk-modal>

                            <div class="relative mx-auto bg-white  rounded-lg shadow-xl uk-modal-dialog w-[400px]">


                                <form method="post">
                                    <div class="px-6 py-4 border-b">
                                        <h2 class="text-xl font-semibold">Achat Grouper</h2>
                                    </div>
                                    <div class="p-6 overflow-y-auto " uk-overflow-auto>
                                        <input type="number" class="w-full mb-3" placeholder="Quantité" name="Quantité">
                                        <input type="text" class="w-full mb-3" placeholder="Localité" name="local">

                                    </div>
                                    <div class="flex justify-end p-6 text-sm font-medium px-6 py-4 border-t">
                                        <button class="px-4 py-1.5 rounded-md uk-modal-close" type="reset">Annuler</button>
                                        <button class="px-5 py-1.5 bg-gray-100 rounded-md " type="submit" name="submitG">Envoyer</button>
                                    </div>
                                </form>


                                <!-- close button -->
                                <button type="button" class="bg-white rounded-full p-2 absolute right-0 top-0 m-3 dark:bg-slate-600 uk-modal-close">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                            </div>

                        </div>

                        <?php if ($nombreGroup > 0 && isset($id_user) && isset($id_vendeur) && $id_user != $id_vendeur) { ?>
                            <div id="countdown-container" class="flex flex-col justify-center items-center ">
                                <span class="mb-2">Temps restant pour cet achat groupé</span>
                                <div id="countdown" class="flex items-center gap-2 text-3xl font-semibold text-red-500 bg-red-100  p-3 rounded-xl w-auto">
                                    <div>-</div>:
                                    <div>-</div>:
                                    <div>-</div>:
                                    <div>-</div>
                                </div>
                            </div>
                        <?php } ?>


                        <script>
                            // Convertir la date de départ en objet Date JavaScript
                            const startDate = new Date("<?= $datePlusAncienne; ?>");

                            // Ajouter 5 jours à la date de départ
                            startDate.setDate(startDate.getDate() + 5);

                            // Mettre à jour le compte à rebours à intervalles réguliers
                            const countdownTimer = setInterval(updateCountdown, 1000);

                            function updateCountdown() {
                                // Obtenir la date et l'heure actuelles
                                const currentDate = new Date();

                                // Calculer la différence entre la date cible et la date de départ en millisecondes
                                const difference = startDate.getTime() - currentDate.getTime();

                                // Convertir la différence en jours, heures, minutes et secondes
                                const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                                const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((difference % (1000 * 60)) / 1000);

                                // Afficher le compte à rebours dans l'élément HTML avec l'id "countdown"
                                const countdownElement = document.getElementById('countdown');
                                countdownElement.innerHTML = `
                                    <div>${days}j</div>:
                                    <div>${hours}h</div>:
                                    <div>${minutes}m</div>:
                                    <div>${seconds}s</div>
                                `;

                                // Arrêter le compte à rebours lorsque la date cible est atteinte
                                if (difference <= 0) {
                                    clearInterval(countdownTimer);
                                    countdownElement.innerHTML = "Temps écoulé !";
                                }
                            }
                        </script>


                    </div>
                </div>



            </div>


        </main>

    </div>


    <!-- Javascript  -->
    <script src="assets/js/uikit.min.js"></script>
    <script src="assets/js/simplebar.js"></script>
    <script src="assets/js/script.js"></script>


    <!-- Ion icon -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <script>
        window.onload = function() {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        }
    </script>



</body>

</html>