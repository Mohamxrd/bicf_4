<?php
session_start();
@include('../../../config.php');



if (!isset($_SESSION['nom_user'])) {
    header('location: ../../../auth/login.php');
    exit(); // Ajout pour terminer l'exécution après la redirection
}



$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

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

            <div class="lg:flex 2xl:gap-16 gap-12 max-w-[1065px] mx-auto" id="js-oversized">

                <!-- search -->


                <div class="flex-1 mx-auto  ">

                    <!-- stories -->


                    <!-- feed story -->
                    <div class="md:max-w-[650px] mx-auto flex-1 xl:space-y-6 space-y-3">
                        <!-- Formulaire de recherche -->
                        <form id="searchForm" method="post" class="w-full" autocomplete="off">
                            <div class="flex items-center py-3 dark:border-gray-600">
                                <!-- Champ de recherche -->
                                <input type="text" name="recherche" placeholder="Rechercher un produit ou service" class="flex-1 border-none bg-transparent focus:outline-none dark:text-white rounded-l-md" value="<?php echo isset($_POST['recherche']) ? $_POST['recherche'] : ''; ?>" />
                                <!-- Bouton de recherche -->
                                <button type="submit" class="flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-md ml-2">
                                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                    </svg>
                                </button>
                            </div>

                            <div class="flex justify-between">
                                <!-- Dropdown 1 -->
                                <div class="dropdown" style="width: calc(33% - 10px);">
                                    <!-- Dropdown Trigger -->
                                    <select name="zone_economique" class="dropdown-trigger w-full">
                                        <option value="" disabled <?php echo (!isset($_POST['zone_economique']) || $_POST['zone_economique'] == '') ? 'selected' : ''; ?>>Zone economique</option>
                                        <option value="Proximité" <?php echo (isset($_POST['zone_economique']) && $_POST['zone_economique'] == 'Proximité') ? 'selected' : ''; ?>>Proximité</option>
                                        <option value="Locale" <?php echo (isset($_POST['zone_economique']) && $_POST['zone_economique'] == 'Locale') ? 'selected' : ''; ?>>Locale</option>
                                        <option value="Nationale" <?php echo (isset($_POST['zone_economique']) && $_POST['zone_economique'] == 'Nationale') ? 'selected' : ''; ?>>Nationale</option>
                                        <option value="Sous Régionale" <?php echo (isset($_POST['zone_economique']) && $_POST['zone_economique'] == 'Sous Régionale') ? 'selected' : ''; ?>>Sous Régionale</option>
                                        <option value="Continentale" <?php echo (isset($_POST['zone_economique']) && $_POST['zone_economique'] == 'Continentale') ? 'selected' : ''; ?>>Continentale</option>
                                        <option value="Internationale" <?php echo (isset($_POST['zone_economique']) && $_POST['zone_economique'] == 'Internationale') ? 'selected' : ''; ?>>Internationale</option>
                                    </select>
                                </div>
                                <!-- Dropdown 2 -->
                                <div class="dropdown" style="width: calc(33% - 10px);">
                                    <!-- Dropdown Trigger -->
                                    <select name="type_produit" class="dropdown-trigger w-full">
                                        <option value="" disabled <?php echo (!isset($_POST['type_produit']) || $_POST['type_produit'] == '') ? 'selected' : ''; ?>>Type de produit</option>
                                        <option value="Importé" <?php echo (isset($_POST['type_produit']) && $_POST['type_produit'] == 'Importé') ? 'selected' : ''; ?>>Importé</option>
                                        <option value="Local" <?php echo (isset($_POST['type_produit']) && $_POST['type_produit'] == 'Local') ? 'selected' : ''; ?>>Local</option>
                                    </select>
                                </div>
                                <!-- Dropdown 3 -->
                                <div style="width: calc(33% - 10px);">
                                    <!-- Input field -->
                                    <input type="text" name="quantite" class="w-full" placeholder="Quantité" value="<?php echo isset($_POST['quantite']) ? $_POST['quantite'] : ''; ?>">
                                </div>
                            </div>
                        </form>
                    </div>


                    <!-- //recherche -->
                    <?php
                    // Initialiser une variable pour suivre le nombre de résultats trouvés
                    $resultatsTrouves = 0;

                    $zoneEconomique = "";
                    $typeProduit = "";
                    $quantite = "";
                    $recherche = "";

                    // Vérifier si la requête de recherche a été soumise
                    if (isset($_POST['recherche'])) {
                        $zoneEconomique = isset($_POST['zone_economique']) ? $_POST['zone_economique'] : "";
                        $typeProduit = isset($_POST['type_produit']) ? $_POST['type_produit'] : "";
                        $quantite = $_POST['quantite'];
                        $recherche = $_POST['recherche'];

                        // Construire la requête SQL en fonction des filtres sélectionnés
                        $sql = "SELECT MIN(prixProd) AS min_price, COUNT(DISTINCT id_user) AS total FROM prodUser WHERE 1=1"; // Clause WHERE 1=1 permet de construire dynamiquement la requête

                        if ($zoneEconomique != "") {
                            $sql .= " AND zonecoProd = '$zoneEconomique'";
                        }

                        if ($typeProduit != "") {
                            $sql .= " AND typeProd = '$typeProduit'";
                        }

                        if ($quantite != "") {
                            $sql .= " AND ('$quantite' BETWEEN qteProd_min AND qteProd_max OR qteProd_min = '' OR qteProd_max = '')";
                        }

                        if ($recherche != "") {
                            $sql .= " AND nomArt LIKE '%$recherche%'";
                        }

                        if (!empty($id_user)) {
                            $sql .= " AND id_user != '$id_user'";
                        }

                        // Exécuter la requête SQL
                        $requete = $conn->prepare($sql);
                        $requete->execute();

                        // Obtenir le nombre de résultats trouvés
                        $resultatRequete = $requete->fetch();
                        $resultatsTrouves = $resultatRequete['total'];
                        $minPrice = $resultatRequete['min_price'];

                        // Afficher les résultats s'il y en a
                        if ($resultatsTrouves > 0) {
                    ?>
                            <div class="bg-white rounded-xl shadow-sm text-sm font-medium border1 dark:bg-dark2 my-3">
                                <div class="flex items-center gap-3 sm:p-4 p-2.5 text-sm font-medium">
                                    <div class="flex-1">
                                        <h4 class="text-lg text-black dark:text-white"><?= $recherche ?></h4>
                                    </div>
                                    <div class="flex">
                                        <div class="flex p-1 items-center text-xs bg-teal-100/60 text-teal-600 rounded">
                                            <ion-icon name="person" class="drop-shadow-md mr-1"></ion-icon>
                                            <span><?= $resultatsTrouves ?> Fournisseur</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Résultats de la recherche -->
                            <div class="flex flex-col justify-center bg-white rounded-xl shadow-sm text-sm font-medium border1 dark:bg-dark2 my-3 p-3">
                                <?php
                                // Construire une nouvelle requête pour obtenir les détails des produits correspondants à la recherche
                                $sqlDetails = "SELECT nomArt FROM prodUser WHERE 1=1";

                                if ($zoneEconomique != "") {
                                    $sqlDetails .= " AND zonecoProd = '$zoneEconomique'";
                                }

                                if ($typeProduit != "") {
                                    $sqlDetails .= " AND typeProd = '$typeProduit'";
                                }

                                if ($quantite != "") {
                                    $sqlDetails .= " AND ('$quantite' BETWEEN qteProd_min AND qteProd_max OR qteProd_min = '' OR qteProd_max = '')";
                                }

                                if ($recherche != "") {
                                    $sqlDetails .= " AND nomArt LIKE '%$recherche%'";
                                }

                                // Exécuter la requête SQL pour les détails des produits
                                $requeteDetails = $conn->prepare($sqlDetails);
                                $requeteDetails->execute();

                                // Afficher les détails des produits
                                while ($produit = $requeteDetails->fetch()) {
                                ?>
                                    <div class="flex items-center gap-3 sm:p-4 p-2.5 text-sm font-medium border-b">
                                        <div class="flex-1">
                                            <h4 class="text-lg text-black dark:text-white"><?= $produit['nomArt'] ?></h4>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="flex justify-center items-center dark:text-white/80 p-4 w-full">
                                    <button onclick="faireAppelOffre()" class="text-white p-2 bg-blue-500 rounded-md">Faire un appel d'offre</button>

                                    <?php
                                    // Requête SQL pour récupérer tous les id_user distincts de prodUser
                                    $sql = "SELECT DISTINCT id_user FROM prodUser WHERE 1=1"; // Commencez la requête avec 1=1 pour permettre la construction dynamique

                                    // Ajoutez les conditions supplémentaires en fonction des critères de recherche
                                    if ($zoneEconomique != "") {
                                        $sql .= " AND zonecoProd = '$zoneEconomique'";
                                    }

                                    if ($typeProduit != "") {
                                        $sql .= " AND typeProd = '$typeProduit'";
                                    }

                                    if ($quantite != "") {
                                        $sql .= " AND ('$quantite' BETWEEN qteProd_min AND qteProd_max OR qteProd_min = '' OR qteProd_max = '')";
                                    }

                                    if ($recherche != "") {
                                        $sql .= " AND nomArt LIKE '%$recherche%'";
                                    }

                                    if (!empty($id_user)) {
                                        $sql .= " AND id_user != '$id_user'";
                                    }

                                    // Exécutez la requête SQL
                                    $requete = $conn->prepare($sql);
                                    $requete->execute();

                                    // Récupérez les résultats
                                    $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);

                                    // Fermez la requête
                                    $requete->closeCursor();

                                    // Créez un tableau pour stocker les id_user
                                    $id_trader = array();

                                    // Bouclez sur les résultats pour remplir le tableau
                                    foreach ($resultats as $resultat) {
                                        // Vérifiez si l'id_user est différent de l'id de la session active
                                        if ($resultat['id_user'] != $id_user) {
                                            // Ajoutez l'id_user au tableau
                                            $id_trader[] = $resultat['id_user'];
                                        }
                                    }

                                    // Affichez le contenu du tableau pour vérification

                                    ?>


                                    <script>
                                        function faireAppelOffre() {
                                            // Création de l'URL avec les id_trader
                                            var url = 'formappel.php?id_trader=<?php echo implode(",", $id_trader); ?>&minPrice=<?php echo urlencode($minPrice); ?>&recherche=<?php echo urlencode($recherche); ?>';

                                            // Redirection avec les id_trader dans l'URL
                                            window.location.href = url;
                                        }
                                    </script>
                                </div>




                            </div>



                        <?php
                        } else { ?>
                            <div class="w-full mt-6 h-96 flex  flex-col items-center justify-center">
                                <p>Aucun résultat trouvé.</p>
                            </div>
                        <?php
                        }
                    } else { ?>
                        <div class="w-full mt-6 h-96 flex  flex-col items-center justify-center">
                            <div class="text-sm text-gray-500 mt-6 text-center">Tapez dans la barre de recherche le produit ou le service dont vous avez besoin pour faire un appel d'offre</div>
                        </div>
                        <?php }

                    if (isset($_POST['recherche'])) {
                        $recherche = $_POST['recherche'];

                        // Requête SQL pour rechercher dans la table appelOffre
                        $getGroup = $conn->prepare("SELECT DISTINCT code_unique, nomArt_appel FROM appelOffre WHERE difference = 'groupe' AND nomArt_appel LIKE :recherche");
                        $getGroup->execute(array(':recherche' => '%' . $recherche . '%'));



                        // Vérifier s'il y a des résultats
                        if ($getGroup->rowCount() > 0) {
                            // Afficher les résultats
                        ?>
                            <div class="w-full my-6 flex items-center justify-center">
                                <p class="text-xl">Resulats pour les appels offre groupé</p>
                            </div>
                            <?php

                            while ($offreGroup = $getGroup->fetch()) {
                                // Récupérer les données nécessaires
                                $nomAppel = $offreGroup['nomArt_appel'];
                                $code_unique = $offreGroup['code_unique'];


                                // Afficher les résultats ici
                                try {
                                    // Requête SQL pour compter le nombre d'ID demandeurs distincts
                                    $nombrePers = $conn->prepare("SELECT COUNT(DISTINCT id_demander) AS totalPers FROM appelOffre WHERE code_unique = :code_unique");

                                    // Liaison du paramètre
                                    $nombrePers->bindParam(':code_unique', $code_unique, PDO::PARAM_STR);

                                    // Exécution de la requête
                                    $nombrePers->execute();

                                    // Récupération du nombre total de personnes
                                    $totalPers = $nombrePers->fetchColumn();

                                    // Affichage du résultat
                                    
                                } catch (PDOException $e) {
                                    // En cas d'erreur, afficher le message d'erreur
                                    echo "Erreur PDO : " . $e->getMessage();
                                }

                                $recupDatePlusAncienne = $conn->prepare("SELECT MIN(date_ajout) AS date_plus_ancienne FROM appelOffre WHERE code_unique = :code_unique");
                                $recupDatePlusAncienne->bindParam(':code_unique', $code_unique, PDO::PARAM_INT);
                                $recupDatePlusAncienne->execute();

                                $resultatDate = $recupDatePlusAncienne->fetch(PDO::FETCH_ASSOC);

                                $datePlusAncienne = $resultatDate['date_plus_ancienne'];

                                $dateDuJour = date("Y-m-d H:i:s");
                                $tempEcoule = date("Y-m-d H:i:s", strtotime($datePlusAncienne . "+5 days"));


                            ?>
                                <div class="box w-full p-3 flex flex-col items-center mb-3">
                                    <div class="flex-1 mb-2 justify-start">
                                        <h4 class="text-lg text-black dark:text-white"><?= $nomAppel ?></h4>
                                    </div>

                                    <button class="w-2/3 p-2 text-center text-white text-sm bg-blue-500 rounded my-2">Nombre de participant (<?= $totalPers ?>)</button>

                                    <div id="countdown-container" class="flex flex-col justify-center items-center ">
                                        <span class="mb-2">Temps restant pour cet achat groupé</span>
                                        <div id="countdown" class="flex items-center gap-2 text-3xl font-semibold text-red-500 bg-red-100  p-3 rounded-xl w-auto">
                                            <div>-</div>:
                                            <div>-</div>:
                                            <div>-</div>:
                                            <div>-</div>
                                        </div>
                                    </div>
                                </div>

                                

                    <?php
                            }
                        }
                    }
                    ?>






                </div>




                <!-- sidebar -->

                <div class="flex-2" style="width: 350px; ">

                    <div class="lg:space-y-4 lg:pb-8 max-lg:hidden sm:grid-cols-2 max-lg:gap-6 sm:mt-2" uk-sticky="media: 1024; end: #js-oversized; offset: 80">



                        <div class="box p-5 px-6 border1 dark:bg-dark2">

                            <div class="flex justify-between text-black dark:text-white">
                                <h3 class="font-bold text-base">Thème les plus recherché</h3>
                                <button type="button"> <ion-icon name="sync-outline" class="text-xl"></ion-icon> </button>
                            </div>

                            <div class="space-y-3.5 capitalize text-xs font-normal mt-5 mb-2 text-gray-600 dark:text-white/80">
                                <a href="#">
                                    <div class="flex items-center gap-3 p">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-black dark:text-white text-sm"> artificial intelligence </h4>
                                            <div class="mt-0.5"> 1,245,62 post </div>
                                        </div>
                                    </div>
                                </a>

                                <a href="#" class="block">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-black dark:text-white text-sm"> Web developers</h4>
                                            <div class="mt-0.5"> 1,624 post </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-black dark:text-white text-sm"> Ui Designers</h4>
                                            <div class="mt-0.5"> 820 post </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-black dark:text-white text-sm"> affiliate marketing </h4>
                                            <div class="mt-0.5"> 480 post </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-black dark:text-white text-sm"> affiliate marketing </h4>
                                            <div class="mt-0.5"> 480 post </div>
                                        </div>
                                    </div>
                                </a>


                            </div>


                        </div>

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


</body>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        // Récupérer les valeurs des champs de recherche
        var zoneEconomique = document.querySelector('select[name="zone_economique"]').value;
        var typeProduit = document.querySelector('select[name="type_produit"]').value;
        var quantite = document.querySelector('input[name="quantite"]').value;
        var recherche = document.querySelector('input[name="recherche"]').value;

        // Construire l'URL avec les paramètres de recherche
        var newURL = window.location.origin + window.location.pathname + '?';

        // Ajouter les paramètres de recherche à l'URL
        newURL += 'zone_economique=' + encodeURIComponent(zoneEconomique) + '&';
        newURL += 'type_produit=' + encodeURIComponent(typeProduit) + '&';
        newURL += 'quantite=' + encodeURIComponent(quantite) + '&';
        newURL += 'recherche=' + encodeURIComponent(recherche);

        // Modifier l'URL de la page sans rechargement
        window.history.replaceState(null, null, newURL);
    });

    window.addEventListener('popstate', function() {
        // Recharger la page pour afficher les résultats de la recherche
        location.reload();
    });

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


</html>