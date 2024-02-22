<?php
session_start();
@include('../../../config.php');

if (!isset($_SESSION['nom_user'])) {
    header('location: ../../../auth/login.php');
}


$id_user = $_SESSION['id_user'];

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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                                </svg>
                                <ion-icon name="add-circle-outline" class="sm:hidden text-2xl "></ion-icon>
                            </button>
                            <div class="hidden bg-white p-4 rounded-lg overflow-hidden drop-shadow-xl dark:bg-slate-700 md:w-[324px] w-screen border2" uk-drop="offset:6;pos: bottom-right; mode: click; animate-out: true; animation: uk-animation-scale-up uk-transform-origin-top-right ">

                                <h3 class="font-bold text-md"> Ajouter </h3>

                                <!-- slider -->
                                <div class="mt-4" tabindex="-1" uk-slider="finite:true;sets: true">

                                    <div class="uk-slider-container pb-1">
                                        <ul class="uk-slider-items grid grid-cols-2 gap-4" uk-scrollspy="target: > li; cls: uk-animation-scale-up , uk-animation-slide-right-small; delay: 20 ;repeat: true">
                                            <a href="addprod.php">
                                                <li class="w-full" uk-scrollspy-class="uk-animation-fade">
                                                    <div class="p-3 px-4 rounded-lg bg-teal-100/60 text-teal-600 dark:text-white dark:bg-dark4">
                                                        <ion-icon name="cart" class="text-2xl drop-shadow-md"></ion-icon>
                                                        <div class="mt-1.5 text-sm font-medium"> Produit </div>
                                                    </div>
                                                </li>
                                            </a>
                                            <a href="addcons.php">
                                                <li class="w-full">
                                                    <div class="p-3 px-4 rounded-lg bg-sky-100/60 text-sky-600 dark:text-white dark:bg-dark4">
                                                        <ion-icon name="pricetags" class="text-2xl drop-shadow-md"></ion-icon>

                                                        <div class="mt-1.5 text-sm font-medium"> Consommation </div>
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
                                        <ul class="inline-flex flex-wrap justify-center gap-1 uk-dotnav uk-slider-nav"> </ul>
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
                                <div class="absolute top-0 right-0 -m-1 bg-red-600 text-white text-xs px-1 rounded-full">6</div>
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
                                                <a href="#"> <ion-icon class="text-xl shrink-0" name="notifications-off-outline"></ion-icon> Mute Notification </a>
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
                                                <p> <b class="font-bold mr-1"> Alexa Gray</b> started following you. Welcome him to your profile. 👋 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 4 hours ago </div>
                                                <div class="w-2.5 h-2.5 bg-teal-600 rounded-full absolute right-3 top-5"></div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-7.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1">Jesse Steeve</b> mentioned you in a story. Check it out and reply. 📣 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 8 hours ago </div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-6.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> Alexa stella</b> commented on your photo “Wow, stunning shot!” 💬 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 8 hours ago </div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-2.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> John Michael</b> who you might know, is on socialite.</p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 2 hours ago </div>
                                            </div>
                                            <button type="button" class="button text-white bg-primary">fallow</button>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10 bg-teal-500/5">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-3.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> Sarah Gray</b> sent you a message. He wants to chat with you. 💖 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 4 hours ago </div>
                                                <div class="w-2.5 h-2.5 bg-teal-600 rounded-full absolute right-3 top-5"></div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-4.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> Jesse Steeve</b> sarah tagged you <br> in a photo of your birthday party. 📸 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 8 hours ago </div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-2.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> Lewis Lewis</b> mentioned you in a story. Check it out and reply. 📣 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 8 hours ago </div>
                                            </div>
                                        </a>
                                        <a href="#" class="relative flex items-center gap-3 p-2 duration-200 rounded-xl pr-10 hover:bg-secondery dark:hover:bg-white/10">
                                            <div class="relative w-12 h-12 shrink-0"> <img src="assets/images/avatars/avatar-7.jpg" alt="" class="object-cover w-full h-full rounded-full"></div>
                                            <div class="flex-1 ">
                                                <p> <b class="font-bold mr-1"> Martin Gray</b> liked your photo of the Eiffel Tower. 😍 </p>
                                                <div class="text-xs text-gray-500 mt-1.5 dark:text-white/80"> 8 hours ago </div>
                                            </div>
                                        </a>

                                    </div>

                                </div>


                                <!-- footer -->
                                <a href="#">
                                    <div class="text-center py-4 border-t border-slate-100 text-sm font-medium text-blue-600 dark:text-white dark:border-gray-600"> View Notifications </div>
                                </a>

                                <div class="w-3 h-3 absolute -top-1.5 right-3 bg-white border-l border-t rotate-45 max-md:hidden dark:bg-dark3 dark:border-transparent"></div>
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
                                            <h4 class="text-sm font-medium text-black"><?= $nom_client ?></h4>
                                            <div class="text-sm mt-1 text-blue-600 font-light dark:text-white/70"><?= '@' . $username_client ?></div>
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
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
                            <li class="active">
                                <a href="#">
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

                        <!-- add story -->


                        <!--  post image-->


                        <!--  post image with slider-->

                        <div class="flex items-center py-3 dark:border-gray-600">

                            <!-- Champ de recherche -->
                            <input type="text" placeholder="Rechercher un produit" class="flex-1 border-none bg-transparent focus:outline-none dark:text-white rounded-l-md" />
                            <!-- Bouton de suffixe -->
                            <button class="flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-md ml-2">
                                <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                </svg>
                            </button>
                        </div>

                        <div class="flex justify-between">
                            <!-- Dropdown 1 -->
                            <div class="dropdown" style="width: calc(25% - 10px);">
                                <!-- Dropdown Trigger -->
                                <select class="dropdown-trigger w-full">
                                    <option value="" disabled selected>Achat</option>
                                    <option value="option1">Option 1</option>
                                    <option value="option2">Option 2</option>
                                    <option value="option3">Option 3</option>
                                    <option value="option4">Option 4</option>
                                    <option value="option5">Option 5</option>
                                </select>
                            </div>
                            <!-- Dropdown 2 -->
                            <div class="dropdown" style="width: calc(25% - 10px);">
                                <!-- Dropdown Trigger -->
                                <select class="dropdown-trigger w-full">
                                    <option value="" disabled selected>Domaine</option>
                                    <option value="option1">Option 1</option>
                                    <option value="option2">Option 2</option>
                                    <option value="option3">Option 3</option>
                                    <option value="option4">Option 4</option>
                                    <option value="option5">Option 5</option>
                                </select>
                            </div>
                            <!-- Dropdown 3 -->
                            <div class="dropdown" style="width: calc(25% - 10px);">
                                <!-- Dropdown Trigger -->
                                <select class="dropdown-trigger w-full">
                                    <option value="" disabled selected>Pays</option>
                                    <option value="option1">Option 1</option>
                                    <option value="option2">Option 2</option>
                                    <option value="option3">Option 3</option>
                                    <option value="option4">Option 4</option>
                                    <option value="option5">Option 5</option>
                                </select>
                            </div>
                            <!-- Dropdown 4 -->
                            <div class="dropdown" style="width: calc(25%);">
                                <!-- Dropdown Trigger -->
                                <select class="dropdown-trigger w-full">
                                    <option value="" disabled selected>Detaillant</option>
                                    <option value="option1">Option 1</option>
                                    <option value="option2">Option 2</option>
                                    <option value="option3">Option 3</option>
                                    <option value="option4">Option 4</option>
                                    <option value="option5">Option 5</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="w-full mt-6 h-96 flex  flex-col items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" width="300" height="250" viewBox="0 0 709.53268 558.59384" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect x="0.27492" y="0.36501" width="643.86162" height="412.35762" fill="#e6e6e6" />
                            <rect x="18.68599" y="52.08494" width="607.03947" height="336.24257" fill="#fff" />
                            <rect width="643.86163" height="27.3536" fill="#2f69f3" />
                            <circle cx="20.327" cy="13.98461" r="5.06978" fill="#fff" />
                            <circle cx="39.57061" cy="13.98461" r="5.06978" fill="#fff" />
                            <circle cx="58.81422" cy="13.98461" r="5.06978" fill="#fff" />
                            <rect x="73.84385" y="86.97284" width="155.98055" height="266.46677" fill="#e6e6e6" />
                            <rect x="256.7496" y="86.97284" width="129.9838" height="73.34799" fill="#2f69f3" />
                            <rect x="256.7496" y="180.74686" width="129.9838" height="78.91873" fill="#e6e6e6" />
                            <rect x="256.7496" y="280.09161" width="129.9838" height="73.34799" fill="#e6e6e6" />
                            <rect x="414.58707" y="86.97284" width="155.98056" height="116.12476" fill="#e6e6e6" />
                            <rect x="414.58707" y="237.31485" width="155.98056" height="116.12476" fill="#e6e6e6" />
                            <path d="M755.71223,382.14309v-25a33.5,33.5,0,1,1,67,0v25a4.50508,4.50508,0,0,1-4.5,4.5h-58A4.50508,4.50508,0,0,1,755.71223,382.14309Z" transform="translate(-245.23366 -170.70308)" fill="#2f2e41" />
                            <polygon points="593.514 536.786 581.698 540.056 563.462 496.038 580.901 491.212 593.514 536.786" fill="#ffb8b8" />
                            <path d="M819.38459,708.28158h23.64387a0,0,0,0,1,0,0v14.88687a0,0,0,0,1,0,0H804.49773a0,0,0,0,1,0,0v0A14.88686,14.88686,0,0,1,819.38459,708.28158Z" transform="translate(-406.29299 74.94457) rotate(-15.46951)" fill="#2f2e41" />
                            <polygon points="470.328 545.875 458.068 545.875 452.235 498.587 470.33 498.587 470.328 545.875" fill="#ffb8b8" />
                            <path d="M449.31065,542.37161h23.64387a0,0,0,0,1,0,0v14.88687a0,0,0,0,1,0,0H434.42379a0,0,0,0,1,0,0v0A14.88686,14.88686,0,0,1,449.31065,542.37161Z" fill="#2f2e41" />
                            <path d="M700.77825,452.301a10.0558,10.0558,0,0,0,15.392.91737l32.59018,14.65807L745.796,449.54488l-30.4937-11.10914a10.11028,10.11028,0,0,0-14.524,13.86524Z" transform="translate(-245.23366 -170.70308)" fill="#ffb8b8" />
                            <path d="M768.49246,562.53911c-10.23925,0-20.83911-1.52539-29.74878-6.06152a38.41551,38.41551,0,0,1-19.70874-23.56543c-4.64233-14.69922,1.21094-29.14014,6.87134-43.105,3.50757-8.65381,6.82056-16.82715,7.68018-24.88379l.30029-2.86036c1.33887-12.84765,2.49512-23.94335,8.897-28.105,3.31836-2.15722,7.77979-2.28027,13.64063-.377l55.04492,17.88135-2.02393,104.49023-.33447.11182C808.82279,556.16118,789.41824,562.53911,768.49246,562.53911Z" transform="translate(-245.23366 -170.70308)" fill="#2f2e41" />
                            <path d="M755.46218,401.05127s27-8,48-5c0,0-12,66-8,88s-69.5,8.5-54.5-12.5l5-25s-10-10-1-22Z" transform="translate(-245.23366 -170.70308)" fill="#2f69f3" />
                            <path d="M742.18192,560.55815l-33.27637-6.23926,11.61768-89.40673c.78125-2.4961,18.77807-59.14307,26.95166-62.208a139.51716,139.51716,0,0,1,18.16626-5.04688l1.18383-.23681-6.67236,10.00879-26.56445,63.65429Z" transform="translate(-245.23366 -170.70308)" fill="#2f2e41" />
                            <path d="M724.84329,705.62163l-42.99487-7.16553,24.12817-98.52392,35.90332-134.73731.35425,2.39258c.02808.17822,3.38208,17.77978,53.15064,9.96973l.43774-.06836.12085.42627,60.1521,212.53759-48.99048,8.165L762.42215,543.55083Z" transform="translate(-245.23366 -170.70308)" fill="#2f2e41" />
                            <path d="M784.43558,577.2896l.02685-.75635c.03-.83984,2.988-84.37256,2-117.96729-.99145-33.709,9.92188-62.90087,10.03223-63.19189l.08887-.23438.24121-.06933c14.11963-4.03369,26.3689,8.00537,26.491,8.12744l.17211.17188-4.02124,33.17626,17.21607,120.64161Z" transform="translate(-245.23366 -170.70308)" fill="#2f2e41" />
                            <circle cx="537.09466" cy="190.79701" r="24.56103" fill="#ffb8b8" />
                            <path d="M747.78694,359.14309a26.53,26.53,0,0,1,26.5-26.5h5.00024a26.52977,26.52977,0,0,1,26.49976,26.5v.5H795.22029l-3.604-10.09179-.7207,10.09179h-5.46094l-1.81836-5.09179-.36377,5.09179H747.78694Z" transform="translate(-245.23366 -170.70308)" fill="#2f2e41" />
                            <path d="M779.91118,389.45438a4.43341,4.43341,0,0,1-.3523-4.707c5.29859-10.07813,12.71729-28.7002,2.87012-40.18457l-.70776-.8252h28.5874V386.6575l-25.96948,4.582a4.59632,4.59632,0,0,1-.79639.07032A4.48193,4.48193,0,0,1,779.91118,389.45438Z" transform="translate(-245.23366 -170.70308)" fill="#2f2e41" />
                            <path d="M664.81368,212.24945a135.01972,135.01972,0,1,0,7.65509,199.4028L838.08687,551.4a12.44209,12.44209,0,0,0,16.06592-19.00287l-.01831-.01544L688.51631,392.63391A135.02729,135.02729,0,0,0,664.81368,212.24945ZM654.13692,379.17712a101.15765,101.15765,0,1,1-12.0766-142.54788l.00006,0A101.15764,101.15764,0,0,1,654.13692,379.17712Z" transform="translate(-245.23366 -170.70308)" fill="#3f3d56" />
                            <path d="M511.589,391.25375a101.16315,101.16315,0,0,1-17.16559-135.989q-2.90121,2.92177-5.60938,6.1199A101.15767,101.15767,0,1,0,643.43849,391.85605q2.702-3.20224,5.089-6.559A101.163,101.163,0,0,1,511.589,391.25375Z" transform="translate(-245.23366 -170.70308)" opacity="0.3" style="isolation:isolate" />
                            <path d="M790.214,495.239a10.05578,10.05578,0,0,0,12.42386-9.13254l34.433-9.55748L823.074,464.34553l-30.55233,10.94686A10.11027,10.11027,0,0,0,790.214,495.239Z" transform="translate(-245.23366 -170.70308)" fill="#ffb8b8" />
                            <path d="M804.52567,490.18022,802.43021,470.274l28.76245-15.86914-18.75244-22.70019L815.5,406.20512l7.61987-3.26562.23707.30469c3.593,4.62011,35.10522,45.28076,35.10522,50.30713,0,5.16259-6.02856,20.32324-14.27637,24.44726-7.95581,3.978-37.83081,11.70947-39.09863,12.03711Z" transform="translate(-245.23366 -170.70308)" fill="#2f2e41" />
                            <path d="M953.76634,729.29692h-381a1,1,0,1,1,0-2h381a1,1,0,0,1,0,2Z" transform="translate(-245.23366 -170.70308)" fill="#ccc" />
                        </svg>
                    


                        <div class="text-sm text-gray-500 mt-6 text-center">Tapez dans la barre de recherche le produit ou le service dont vous avez besoin</div>
                    </div> -->




                    <?php
                    $requete = $conn->prepare('SELECT * FROM prodUser ORDER BY date_ajout DESC');
                    $requete->execute();

                    while ($produit = $requete->fetch()) {
                    ?>
                        <div class="bg-white rounded-xl shadow-sm text-sm font-medium border1 dark:bg-dark2 my-3">
                            <div class="flex gap-3 sm:p-4 p-2.5 text-sm font-medium">
                                <div class="flex-1">
                                    <a href="detailprod.php"> 
                                        <h4 class="text-lg text-black dark:text-white"><?= $produit['nomArt']; ?></h4>
                                    </a>
                                    <div class="flex items-center text-xs text-gray-500 dark:text-white/80">
                                        <svg class="w-4 h-4  text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M12 2a8 8 0 0 1 6.6 12.6l-.1.1-.6.7-5.1 6.2a1 1 0 0 1-1.6 0L6 15.3l-.3-.4-.2-.2v-.2A8 8 0 0 1 11.8 2Zm3 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-1"><?= $produit['villePro']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:px-4 p-2.5 pt-0">
                                <p class="font-normal"><?= $produit['desProd']; ?></p>
                            </div>
                            <div class="flex justify-between items-center  dark:text-white/80 p-4 w-full">
                                <button class="text-white p-2 bg-green-500 rounded-md">Commander directement</button>
                                <div class="text-xs text-gray-500">2 hours ago</div>
                            </div>
                        </div>
                    <?php
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


</body>

</html>