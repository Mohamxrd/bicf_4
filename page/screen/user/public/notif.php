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

// Préparez la requête pour récupérer les notifications de l'utilisateur à partir de la table notifUser
$recupNotif = $conn->prepare("SELECT notifUser.*, prodUser.*, appelOffre.* 
FROM notifUser 
LEFT JOIN prodUser ON notifUser.id_prod = prodUser.id_prod 
LEFT JOIN appelOffre ON notifUser.code_appel = appelOffre.code_unique AND appelOffre.id_trader = :id_user
WHERE notifUser.id_trader = :id_user 

ORDER BY notifUser.date_ajout DESC");

$recupNotif->bindParam(':code_appel', $code_appel, PDO::PARAM_STR);
$recupNotif->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);


// Exécutez la requête préparée
$recupNotif->execute();

// Récupérez le nombre de notifications
$nombreNotif = $recupNotif->rowCount();



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
                                <?php if ($nombreNotif > 0) : ?>
                                    <div class="absolute top-0 right-0 -m-1 bg-red-600 text-white text-xs px-1 rounded-full">
                                        <?= $nombreNotif ?>
                                    </div>
                                <?php endif; ?>

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

            <!-- timeline -->
            <div class="mb-3">
                <h2 class="text-2xl text-center font-bold text-gray-800 dark:text-white">Notifications</h2>
            </div>


            <div class="relative z-20 p-10  overflow-y-auto">


                <?php
                // Boucle sur les notifications récupérées depuis la table notifUser
                while ($notification = $recupNotif->fetch()) {
                    // Récupération des informations de la notification
                    $id_notification = $notification['id_notif'];
                    $message = $notification['message'];
                    $localite = $notification['localite'];
                    $quantite = $notification['quantiteProd'];
                    $confirm = $notification['confirm'];
                    $id_client = $notification['id_user'];

                    // Informations sur le produit associé à la notification
                    $nom_produit = $notification['nomArt'];
                    $prix_produit = $notification['PrixProd'];

                    //information sur l'appel d'offre
                    $nomAppel = $notification['nomArt_appel'];
                    $id_appel = $notification['id_appeloffre'];

                ?>

                    <!-- Affichage de chaque notification -->
                    <div class="mb-3 space-y-3 text-sm font-semibold dark:text-white" uk-scrollspy="target: > div; cls: uk-animation-scale-up; delay: 100 ;repeat: true">
                        <div class="flex items-center gap-3 p-4 bg-white shadow rounded-md dark:bg-slate-700">
                            <div class="flex-1"> <!-- Titre du produit -->

                                <?php if ($confirm == '') : ?>
                                    <?= $nom_produit ?>
                                    <span class="block text-xs font-medium dark:text-white/70">
                                        Quantité: <?= $quantite ?>
                                    </span>
                                    <span class="block text-xs font-medium  dark:text-white/70">
                                        <p><?= $message ?></p>
                                    </span>
                                <?php elseif ($confirm == 'accepte') : ?>
                                    <?= $nom_produit ?>
                                    <span class="block text-xs font-medium  dark:text-white/70">
                                        <p>Votre demande a été acceptée par le fournisseur</p>
                                    </span>
                                <?php elseif ($confirm == 'refus') : ?>
                                    <?= $nom_produit ?>
                                    <span class="block text-xs font-medium  dark:text-white/70">
                                        <p>Le fournisseur a refusé votre demande</p>
                                    </span>
                                <?php elseif ($confirm == 'appel') : ?>
                                    <?= $nomAppel ?>
                                    <span class="block text-xs font-medium  dark:text-white/70">
                                        <p>Vous avez été identifier dans un appel d'offre</p>
                                    </span>
                                <?php elseif ($confirm == 'groupeDirect') : ?>
                                    <?= $nom_produit ?>
                                    <span class="block text-xs font-medium dark:text-white/70">
                                        Quantité: <?= $quantite ?>
                                    </span>

                                    <span class="block text-xs font-medium  dark:text-white/70">
                                        <p><?= $message ?></p>
                                    </span>
                                <?php endif; ?>


                            </div>

                            <form method="post">
                                <?php if ($confirm == '' || $confirm == 'groupeDirect') : ?>
                                    <input type="hidden" name="id_notification" value="<?= $id_notification ?>">
                                    <button type="submit" name="accepter" class="px-3 py-1 text-white text-sm bg-green-500 rounded">Accepter</button>
                                    <button type="submit" name="refus" class="px-3 py-1 text-white text-sm bg-red-500 rounded" style="background: red; color:white;">Refuser</button>
                                <?php elseif ($confirm == 'accepte') : ?>
                                    <button type="submit" name="confirmer" class="px-3 py-1 text-white text-sm bg-green-500 rounded">Confirmer</button>
                                    <button type="submit" name="annuler" class="px-3 py-1 text-white text-sm bg-red-500 rounded" style="background: red; color:white;">Annuler</button>
                                <?php elseif ($confirm == 'appel') : ?>
                                    <a href="detailnegos.php?id=<?= $id_appel ?>" type="button" class="px-3 py-1 bg-blue-500 text-white text-sm rounded">Voir</a>

                                <?php endif; ?>


                            </form>
                        </div>
                    </div>
                <?php
                }

                // if (isset($_POST['accepter'])) {
                //     // Assurez-vous que $_POST['id_notification'] est défini et est un nombre entier
                //     if (isset($_POST['id_notification']) && is_numeric($_POST['id_notification'])) {
                //         // Récupérez l'ID de la notification à partir du formulaire posté
                //         $id_notification = $_POST['id_notification'];
                //         // Récupérez l'ID de l'utilisateur depuis la session
                //         $id_user = $_SESSION['id_user'];

                //         // Exécutez la requête SQL pour mettre à jour les valeurs dans la table notifUser
                //         $updateNotif = $conn->prepare("UPDATE notifUser SET confirm = 'accepte', id_trader = id_user, id_user = null WHERE id_notif = :id_notification AND id_trader = :id_user");

                //         // Liez les valeurs aux paramètres de la requête
                //         $updateNotif->bindParam(':id_notification', $id_notification, PDO::PARAM_INT);
                //         $updateNotif->bindParam(':id_user', $id_user, PDO::PARAM_INT);

                //         // Exécutez la requête préparée
                //         $updateNotif->execute();
                //     }
                // }


                if (isset($_POST['refus'])) {
                    // Assurez-vous que $_POST['id_notification'] est défini et est un nombre entier
                    if (isset($_POST['id_notification']) && is_numeric($_POST['id_notification'])) {
                        // Récupérez l'ID de la notification à partir du formulaire posté
                        $id_notification = $_POST['id_notification'];

                        // Récupérez l'ID de l'utilisateur depuis la session
                        $id_user = $_SESSION['id_user'];

                        // Exécutez la requête SQL pour mettre à jour les valeurs dans la table notifUser
                        $updateNotif = $conn->prepare("UPDATE notifUser SET confirm = 'refus', id_trader = id_user, id_user = null WHERE id_notif = :id_notification AND id_trader = :id_user");

                        // Liez les valeurs aux paramètres de la requête
                        $updateNotif->bindParam(':id_notification', $id_notification, PDO::PARAM_INT);
                        $updateNotif->bindParam(':id_user', $id_user, PDO::PARAM_INT);

                        // Exécutez la requête préparée
                        $updateNotif->execute();
                    }
                }


                if (isset($_POST['annuler'])) {
                    // Assurez-vous que $_POST['id_notification'] est défini et est un nombre entier
                    if (isset($_POST['id_notification']) && is_numeric($_POST['id_notification'])) {
                        // Récupérez l'ID de la notification à partir du formulaire posté
                        $id_notification = $_POST['id_notification'];

                        // Récupérez l'ID de l'utilisateur depuis la session
                        $id_user = $_SESSION['id_user'];

                        // Exécutez la requête SQL pour supprimer l'entrée de la table notifUser
                        $deleteNotif = $conn->prepare("DELETE FROM notifUser WHERE id_notif = :id_notification AND id_trader = :id_user");

                        // Liez les valeurs aux paramètres de la requête
                        $deleteNotif->bindParam(':id_notification', $id_notification, PDO::PARAM_INT);
                        $deleteNotif->bindParam(':id_user', $id_user, PDO::PARAM_INT);

                        // Exécutez la requête préparée
                        $deleteNotif->execute();
                    }
                }

                ?>




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

    <script>
        window.onload = function() {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        }
    </script>


</body>

</html>