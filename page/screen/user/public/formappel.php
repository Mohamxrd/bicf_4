<?php
session_start();
@include('../../../config.php');



if (!isset($_SESSION['nom_user'])) {
    header('location: ../../../auth/login.php');
    exit(); // Ajout pour terminer l'exécution après la redirection
}



$id_user = $_SESSION['id_user'];
// Récupération des informations de l'utilisateur
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
    }
    // ... Ajoutez d'autres champs au besoin ...
} else {
    // Gérer le cas où l'utilisateur n'est pas trouvé dans la base de données
    echo "Erreur: Utilisateur non trouvé dans la base de données.";
    exit();
}

// Récupération du prix minimal depuis l'URL
if (isset($_GET['minPrice']) && isset($_GET['recherche'])) {
    $minPrice = $_GET['minPrice'];
    $recherche = $_GET['recherche'];
}



if (isset($_POST['submit'])) {

    function genererCodeAleatoire($longueur)
    {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $code = '';

        for ($i = 0; $i < $longueur; $i++) {
            $code .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }

        return $code;
    }

    // Exemple d'utilisation
    $code = genererCodeAleatoire(10);


    // Récupération des données du formulaire
    $titre_prod = isset($_POST['titre_prod']) ? htmlspecialchars($_POST['titre_prod']) : '';
    $quantite = isset($_POST['quantite']) ? htmlspecialchars($_POST['quantite']) : '';
    $payement = isset($_POST['payement']) ? htmlspecialchars($_POST['payement']) : '';
    $livraisonProd = isset($_POST['livraisonProd']) ? htmlspecialchars($_POST['livraisonProd']) : '';
    $dateTot = isset($_POST['dateTot']) ? htmlspecialchars($_POST['dateTot']) : '';
    $dateTard = isset($_POST['dateTard']) ? htmlspecialchars($_POST['dateTard']) : '';
    $desProd = isset($_POST['desProd']) ? htmlspecialchars($_POST['desProd']) : '';

    // Vérification si les champs obligatoires sont vides
    if (empty($titre_prod) || empty($quantite) || empty($payement) || empty($livraisonProd) || empty($dateTot) || empty($dateTard) || empty($desProd)) {
        $errorMsg = 'Veuillez remplir tous les champs.';
    } else {
        // Récupération de l'ID de l'utilisateur à partir de la session
        $id_demander = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

        // Préparation de la requête d'insertion
        $insertAppel = $conn->prepare("INSERT INTO appelOffre (nomArt_appel, quantite, prixMax, payement, livraison, dateTot, dateTard, descrip, joint, id_demander, id_trader, code_unique, difference) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Si une image a été téléchargée, la traiter
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = $_FILES['image']['name']; // Nom de l'image
            $image_tmp_name = $_FILES['image']['tmp_name']; // Chemin temporaire de l'image sur le serveur
            $target_dir = "uploads/"; // Dossier où vous souhaitez stocker les images téléchargées
            $target_file = $target_dir . basename($image_name);

            // Déplacer l'image téléchargée vers le dossier de destination
            if (move_uploaded_file($image_tmp_name, $target_file)) {
                // L'image a été téléchargée avec succès

                // Préparation de la requête d'insertion avec l'image
                $insertAppel = $conn->prepare("INSERT INTO appelOffre (nomArt_appel, quantite, prixMax, payement, livraison, dateTot, dateTard, descrip, joint, id_demander, id_trader, code_unique, difference) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                // Exécution de la requête d'insertion avec l'image
                if ($insertAppel) {
                    if (isset($_GET['id_trader'])) {
                        $id_trader = explode(",", $_GET['id_trader']);

                        // Boucler sur chaque id_trader
                        foreach ($id_trader as $id) {
                            // Exécuter la requête d'insertion avec les valeurs appropriées
                            $insertAppel->execute([$titre_prod, $quantite, $minPrice, $payement, $livraisonProd, $dateTot, $dateTard, $desProd, $target_file, $id_demander, $id, $code, "single"]);

                            // Ajout de la notification
                            $notif_insert = $conn->prepare("INSERT INTO notifUser (message, quantiteProd, id_user, id_trader, confirm, code_appel) VALUES (?, ?, ?, ?, ?, ?)");
                            $notif_insert->execute(["Vous avez reçu un appel d'offre", $quantite, $id_demander, $id, "appel", $code]);

                            // Ajout du commentaire
                            $comment_insert = $conn->prepare("INSERT INTO comment (prixTrade, id_trader, code_unique) VALUES (?, ?, ?)");
                            $comment_insert->execute([null, $id, $code]);
                        }
                    }
                    // Message de succès
                    $successMsg = "L'appel d'offre a été enregistré avec succès.";
                } else {
                    // Erreur lors de la préparation de la requête
                    $errorMsg = "Une erreur s'est produite lors de l'enregistrement de l'appel d'offre.";
                }
            } else {
                // Erreur lors du téléchargement de l'image
                $errorMsg = "Une erreur s'est produite lors du téléchargement de l'image.";
            }
        } else {
            // Pas d'image téléchargée
            // Exécution de la requête d'insertion sans l'image

            $insertAppel = $conn->prepare("INSERT INTO appelOffre (nomArt_appel, quantite, prixMax, payement, livraison, dateTot, dateTard, descrip, id_demander, id_trader, code_unique, difference) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($insertAppel) {
                if (isset($_GET['id_trader'])) {
                    $id_trader = explode(",", $_GET['id_trader']);

                    // Boucler sur chaque id_trader
                    foreach ($id_trader as $id) {
                        // Exécuter la requête d'insertion avec les valeurs appropriées
                        $insertAppel->execute([$titre_prod, $quantite, $minPrice, $payement, $livraisonProd, $dateTot, $dateTard, $desProd, $id_demander, $id, $code, "single"]);

                        // Ajout de la notification
                        $notif_insert = $conn->prepare("INSERT INTO notifUser (message, quantiteProd, id_user, id_trader, confirm, code_appel) VALUES (?, ?, ?, ?, ?, ?)");
                        $notif_insert->execute(["Vous avez reçu un appel d'offre", $quantite, $id_demander, $id, "appel", $code]);

                        // Ajout du commentaire
                        $comment_insert = $conn->prepare("INSERT INTO comment (prixTrade, id_trader, code_unique) VALUES (?, ?, ?)");
                        $comment_insert->execute([null, $id, $code]);
                    }
                }
                // Message de succès
                $successMsg = "L'appel d'offre a été enregistré avec succès.";
            } else {
                // Erreur lors de la préparation de la requête
                $errorMsg = "Une erreur s'est produite lors de l'enregistrement de l'appel d'offre.";
            }
        }
    }
}



if (isset($_POST['submit2'])) {

    function genererCodeAleatoire($longueur)
    {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $code = '';

        for ($i = 0; $i < $longueur; $i++) {
            $code .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }

        return $code;
    }

    // Exemple d'utilisation
    $code = genererCodeAleatoire(10);


    // Récupération des données du formulaire
    $titre_prod = isset($_POST['titre_prod']) ? htmlspecialchars($_POST['titre_prod']) : '';
    $quantite = isset($_POST['quantite']) ? htmlspecialchars($_POST['quantite']) : '';
    $payement = isset($_POST['payement']) ? htmlspecialchars($_POST['payement']) : '';
    $livraisonProd = isset($_POST['livraisonProd']) ? htmlspecialchars($_POST['livraisonProd']) : '';
    $dateTot = isset($_POST['dateTot']) ? htmlspecialchars($_POST['dateTot']) : '';
    $dateTard = isset($_POST['dateTard']) ? htmlspecialchars($_POST['dateTard']) : '';
    $desProd = isset($_POST['desProd']) ? htmlspecialchars($_POST['desProd']) : '';

    // Vérification si les champs obligatoires sont vides
    if (empty($titre_prod) || empty($quantite) || empty($payement) || empty($livraisonProd) || empty($dateTot) || empty($dateTard) || empty($desProd)) {
        $errorMsg = 'Veuillez remplir tous les champs.';
    } else {
        // Récupération de l'ID de l'utilisateur à partir de la session
        $id_demander = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

        // Préparation de la requête d'insertion
        $insertAppel = $conn->prepare("INSERT INTO appelOffre (nomArt_appel, quantite, prixMax, payement, livraison, dateTot, dateTard, descrip, joint, id_demander, id_trader, code_unique, difference) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Si une image a été téléchargée, la traiter
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = $_FILES['image']['name']; // Nom de l'image
            $image_tmp_name = $_FILES['image']['tmp_name']; // Chemin temporaire de l'image sur le serveur
            $target_dir = "uploads/"; // Dossier où vous souhaitez stocker les images téléchargées
            $target_file = $target_dir . basename($image_name);

            // Déplacer l'image téléchargée vers le dossier de destination
            if (move_uploaded_file($image_tmp_name, $target_file)) {
                // L'image a été téléchargée avec succès

                // Préparation de la requête d'insertion avec l'image
                $insertAppel = $conn->prepare("INSERT INTO appelOffre (nomArt_appel, quantite, prixMax, payement, livraison, dateTot, dateTard, descrip, joint, id_demander, id_trader, code_unique, difference) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                // Exécution de la requête d'insertion avec l'image
                if ($insertAppel) {
                    if (isset($_GET['id_trader'])) {
                        $id_trader = explode(",", $_GET['id_trader']);

                        // Boucler sur chaque id_trader
                        foreach ($id_trader as $id) {
                            // Exécuter la requête d'insertion avec les valeurs appropriées
                            $insertAppel->execute([$titre_prod, $quantite, $minPrice, $payement, $livraisonProd, $dateTot, $dateTard, $desProd, $target_file, $id_demander, $id, $code, "groupe"]);
                        }
                        // Ajouter dans la table offre groupe

                        $inserOgroup = $conn->prepare("INSERT INTO offreGroup (qte_prod, id_demander, code_unique) VALUES (?, ? , ?)");
                        $inserOgroup->execute([$quantite, $id_demander, $code]);
                    }
                    // Message de succès
                    $successMsg = "L'appel d'offre a été enregistré avec succès.";
                } else {
                    // Erreur lors de la préparation de la requête
                    $errorMsg = "Une erreur s'est produite lors de l'enregistrement de l'appel d'offre.";
                }
            } else {
                // Erreur lors du téléchargement de l'image
                $errorMsg = "Une erreur s'est produite lors du téléchargement de l'image.";
            }
        } else {
            // Pas d'image téléchargée
            // Exécution de la requête d'insertion sans l'image

            $insertAppel = $conn->prepare("INSERT INTO appelOffre (nomArt_appel, quantite, prixMax, payement, livraison, dateTot, dateTard, descrip, id_demander, id_trader, code_unique, difference) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($insertAppel) {
                if (isset($_GET['id_trader'])) {
                    $id_trader = explode(",", $_GET['id_trader']);

                    // Boucler sur chaque id_trader
                    foreach ($id_trader as $id) {
                        // Exécuter la requête d'insertion avec les valeurs appropriées
                        $insertAppel->execute([$titre_prod, $quantite, $minPrice, $payement, $livraisonProd, $dateTot, $dateTard, $desProd, $id_demander, $id, $code, "groupe"]);
                    }


                    $inserOgroup = $conn->prepare("INSERT INTO offreGroup (qte_prod, id_demander, code_unique) VALUES (?, ? , ?)");
                    $inserOgroup->execute([$quantite, $id_demander, $code]);
                }
                // Message de succès
                $successMsg = "L'appel d'offre a été enregistré avec succès.";
            } else {
                // Erreur lors de la préparation de la requête
                $errorMsg = "Une erreur s'est produite lors de l'enregistrement de l'appel d'offre.";
            }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Thème Tailwind pour DatePicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">


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

            <!-- timeline -->


            <div class="max-w-3xl mx-auto">
                <div class="box relative rounded-lg shadow-md p-6">

                    <div class="flex-1">
                        <h3 class="md:text-xl text-base font-semibold text-black dark:text-white mb-4">Faire l'appel d'offre</h3>

                    </div>


                    <div class=" text-sm">


                        <div class="p-4 space-y-2">


                            <?php if (isset($errorMsg)) : ?>
                                <div uk-alert>
                                    <div class="p-2 border bg-red-50 border-red-500/30 rounded-xl dark:bg-slate-700">
                                        <div class="inline-flex items-center justify-between gap-6">
                                            <!-- Icon -->
                                            <div class="p-1 text-white shadow rounded-xl shadow-red-300" style="background-color: red">
                                                <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd" d="M2 12a10 10 0 1 1 20 0 10 10 0 0 1-20 0Zm9.4-5.5a1 1 0 1 0 0 2 1 1 0 1 0 0-2ZM10 10a1 1 0 1 0 0 2h1v3h-1a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-1v-4c0-.6-.4-1-1-1h-2Z" clip-rule="evenodd" />
                                                </svg>

                                            </div>
                                            <!-- Text -->
                                            <div class="text-base font-semibold text-red"><?= $errorMsg ?></div>
                                            <!-- Icon close -->

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($successMsg)) : ?>
                                <div uk-alert>
                                    <div class="p-2 border bg-green-50 border-green-500/30 rounded-xl dark:bg-slate-700">
                                        <div class="inline-flex items-center justify-between gap-6">
                                            <!-- Icon -->
                                            <div class="p-1 text-white bg-green-500 shadow rounded-xl shadow-green-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <!-- Text -->
                                            <div class="text-base font-semibold text-green-700"><?= $successMsg ?></div>
                                            <!-- Icon close -->

                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>


                            <form action="" method="post" enctype="multipart/form-data">

                                <div class="w-full card flex mb-3 p-5">
                                    <div class="card-body flex-1 p-0">
                                        <h4 class="card-title"> Prix unitaire max</h4>
                                        <p><?= $minPrice ?></p>
                                    </div>
                                </div>
                                <input type="text" class="w-full mb-3" name="titre_prod" value="<?= $recherche ?>">
                                <input type="text" class="w-full mb-3" placeholder="Quantité" name="quantite">



                                <select class="w-full mb-3" name="payement">
                                    <option value="" disabled selected>Payement</option>
                                    <option value="Payerment comptant">Payement comptant</option>
                                    <option value="Avance partielle">Avance partielle</option>
                                    <option value="A credit">À credit</option>
                                    <option value="Vente à terme">Vente à terme</option>
                                    <option value="Quotionnement / Quarantie de prèt">Quotionnement / Garantie de prèt</option>
                                </select>
                                <select class="w-full mb-3" name="livraisonProd">
                                    <option value="" disabled selected>Livraison</option>
                                    <option value="Oui">Oui</option>
                                    <option value="Non">Non</option>
                                </select>


                                <div date-rangepicker class="flex items-center w-full mb-3">
                                    <div class="w-1/2 mr-2 relative">
                                        <?php
                                        // Obtenez la date actuelle au format "Y-m-d"
                                        $dateActuelle = date('Y-m-d', strtotime('+2 days'));
                                        ?>
                                        <label for="datePicker">Au plus tôt</label>
                                        <input type="date" id="datePicker" name="dateTot" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Sélectionner la date de début" min="<?php echo $dateActuelle; ?>">
                                    </div>

                                    <span class="mx-4 text-gray-500 ">à</span>
                                    <div class="w-1/2 ml-2 relative">
                                        <label for="date-end" class="mb-1">Au plus tard</label>
                                        <input type="date" id="datePicker" name="dateTard" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Sélectionner la date de début" min="<?php echo $dateActuelle; ?>">

                                    </div>
                                </div>



                                <textarea class="w-full h-20" name="desProd" id="" cols="30" rows="10" placeholder="Description"></textarea>

                                <div class="flex justify-between p-3 items-center">
                                    <h3 class="text-black dark:text-white text-xl">Ajouter une piece joint (facultatif)</h3>
                                    <div class="p-4 border-dotted border-2 border-gray-400 rounded-md relative">
                                        <label for="file-upload" class="w-[100px] cursor-pointer">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M9 2.2V7H4.2l.4-.5 3.9-4 .5-.3Zm2-.2v5a2 2 0 0 1-2 2H4v11c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7Z" clip-rule="evenodd" />
                                            </svg>
                                        </label>
                                        <input id="file-upload" class="hidden" type="file" onchange="previewImage(this)" name="image">
                                        <img id="image-preview" class="absolute inset-0 w-full h-full object-cover hidden">
                                        <button onclick="removeImage()" id="remove-button" class="absolute top-2 right-3 text-red-500 text-xl font-bold rounded-full bg-white p-1 hidden">&times;</button>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 mt-4 lg:pl-[10.5rem]">
                                    <button type="reset" class="button lg:px-6 bg-secondery max-md:flex-1">
                                        Annuler</button>
                                    <button type="submit" name="submit" class="button lg:px-10 bg-primary text-white max-md:flex-1"> Envoyer <span class="ripple-overlay"></span></button>
                                    <button type="submit" name="submit2" class="button lg:px-10 bg-green-500 text-white max-md:flex-1"> Grouper <span class="ripple-overlay"></span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>


    <!-- open chat box -->



    <!-- post preview modal -->


    <!-- create status -->


    <!-- create story -->


    <!-- Javascript  -->
    <script src="assets/js/uikit.min.js"></script>
    <script src="assets/js/simplebar.js"></script>
    <script src="assets/js/script.js"></script>


    <!-- Ion icon -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>


</body>

<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const removeButton = document.getElementById('remove-button');
        const file = input.files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.classList.remove('hidden');
            removeButton.classList.remove('hidden');
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            removeButton.classList.add('hidden');
        }
    }

    function removeImage() {
        const preview = document.getElementById('image-preview');
        const removeButton = document.getElementById('remove-button');
        const fileInput = document.getElementById('file-upload');

        preview.src = '';
        preview.classList.add('hidden');
        removeButton.classList.add('hidden');
        fileInput.value = ''; // Clear the file input
    }

    function previewImage2(input) {
        const preview = document.getElementById('image-preview2');
        const removeButton = document.getElementById('remove-button2');
        const file = input.files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.classList.remove('hidden');
            removeButton.classList.remove('hidden');
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            removeButton.classList.add('hidden');
        }
    }

    function removeImage2() {
        const preview = document.getElementById('image-preview2');
        const removeButton = document.getElementById('remove-button2');
        const fileInput = document.getElementById('file-upload2');

        preview.src = '';
        preview.classList.add('hidden');
        removeButton.classList.add('hidden');
        fileInput.value = ''; // Clear the file input
    }

    window.onload = function() {
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    }

    flatpickr("#date-start", {
        enableTime: false, // désactiver le choix de l'heure
        dateFormat: "Y-m-d", // format de la date
        // d'autres options si nécessaire
    });
    flatpickr("#date-end", {
        enableTime: false,
        dateFormat: "Y-m-d",
        // d'autres options si nécessaire
    });
</script>



</html>