<?php
session_start();
@include('../../../config.php');

if (!isset($_SESSION['nom_user'])) {
    header('location: ../../auth/login.php');
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

                                <h3 class="font-bold text-md"> Create </h3>

                                <!-- slider -->
                                <div class="mt-4" tabindex="-1" uk-slider="finite:true;sets: true">

                                    <div class="uk-slider-container pb-1">
                                        <ul class="uk-slider-items grid grid-cols-2 gap-4" uk-scrollspy="target: > li; cls: uk-animation-scale-up , uk-animation-slide-right-small; delay: 20 ;repeat: true">
                                            <li class="w-full" uk-scrollspy-class="uk-animation-fade">
                                                <div class="p-3 px-4 rounded-lg bg-teal-100/60 text-teal-600 dark:text-white dark:bg-dark4">
                                                    <ion-icon name="cart" class="text-2xl drop-shadow-md"></ion-icon>
                                                    <div class="mt-1.5 text-sm font-medium"> Demande </div>
                                                </div>
                                            </li>
                                            <li class="w-full">
                                                <div class="p-3 px-4 rounded-lg bg-sky-100/60 text-sky-600 dark:text-white dark:bg-dark4">
                                                <ion-icon name="pricetags" class="text-2xl drop-shadow-md"></ion-icon>
                                                    
                                                    <div class="mt-1.5 text-sm font-medium"> Offre </div>
                                                </div>
                                            </li>
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
                                            My Billing
                                        </div>
                                    </a>
                                    <a href="setting.html">
                                        <div class="flex items-center gap-2.5 hover:bg-secondery p-2 px-2.5 rounded-md dark:hover:bg-white/10">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46" />
                                            </svg>
                                            Advatacing
                                        </div>
                                    </a>
                                    <a href="setting.html">
                                        <div class="flex items-center gap-2.5 hover:bg-secondery p-2 px-2.5 rounded-md dark:hover:bg-white/10">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            My Account
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
                                    <img src="assets/images/icons/home.png" alt="feeds" class="w-6">
                                    <span> Feed </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="assets/images/icons/message.png" alt="messages" class="w-5">
                                    <span> messages </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="assets/images/icons/video.png" alt="messages" class="w-6">
                                    <span> video </span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <img src="assets/images/icons/page.png" alt="pages" class="w-6">
                                    <span> Pages </span>
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
                                <a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span> Setting </span>
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


                <div class="max-w-[680px] mx-auto">

                    <!-- stories -->


                    <!-- feed story -->
                    <div class="md:max-w-[600px] mx-auto flex-1 xl:space-y-6 space-y-3">

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

                        <div class="flex justify-between mb-3">
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



                        <!-- post text-->
                        <div class="bg-white rounded-xl shadow-sm text-sm font-medium border1 dark:bg-dark2 ">



                            <!-- post heading -->
                            <div class="flex gap-3 sm:p-4 p-2.5 text-sm font-medium">
                                <a href="timeline.html"> <img src="assets/images/avatars/avatar-5.jpg" alt="" class="w-9 h-9 rounded-full"> </a>
                                <div class="flex-1">
                                    <a href="timeline.html">
                                        <h4 class="text-black dark:text-white"> John Michael </h4>
                                    </a>
                                    <div class="text-xs text-gray-500 dark:text-white/80"> 2 hours ago</div>
                                </div>

                                <div class="-mr-1">
                                    <button type="button" class="button__ico w-8 h-8" aria-haspopup="true" aria-expanded="false"> <ion-icon class="text-xl md hydrated" name="ellipsis-horizontal" role="img" aria-label="ellipsis horizontal"></ion-icon> </button>
                                    <div class="w-[245px] uk-dropdown" uk-dropdown="pos: bottom-right; animation: uk-animation-scale-up uk-transform-origin-top-right; animate-out: true; mode: click">
                                        <nav>
                                            <a href="#"> <ion-icon class="text-xl shrink-0 md hydrated" name="bookmark-outline" role="img" aria-label="bookmark outline"></ion-icon> Add to favorites </a>
                                            <a href="#"> <ion-icon class="text-xl shrink-0 md hydrated" name="notifications-off-outline" role="img" aria-label="notifications off outline"></ion-icon> Mute Notification </a>
                                            <a href="#"> <ion-icon class="text-xl shrink-0 md hydrated" name="flag-outline" role="img" aria-label="flag outline"></ion-icon> Report this post </a>
                                            <a href="#"> <ion-icon class="text-xl shrink-0 md hydrated" name="share-outline" role="img" aria-label="share outline"></ion-icon> Share your profile </a>
                                            <hr>
                                            <a href="#" class="text-red-400 hover:!bg-red-50 dark:hover:!bg-red-500/50"> <ion-icon class="text-xl shrink-0 md hydrated" name="stop-circle-outline" role="img" aria-label="stop circle outline"></ion-icon> Unfollow </a>
                                        </nav>
                                    </div>
                                </div>
                            </div>

                            <div class="sm:px-4 p-2.5 pt-0">
                                <p class="font-normal"> Photography is the art of capturing light with a camera. It can be used to create images that tell stories, express emotions, or document reality. it can be fun, challenging, or rewarding. It can also be a hobby, a profession, or a passion. 📷 </p>
                            </div>

                            <!-- post icons -->
                            <div class="sm:p-4 p-2.5 flex items-center gap-4 text-xs font-semibold">
                                <div>
                                    <div class="flex items-center gap-2.5">
                                        <button type="button" class="button-icon text-red-500 bg-red-100 dark:bg-slate-700"> <ion-icon class="text-lg" name="heart"></ion-icon> </button>
                                        <a href="#">1,300</a>
                                    </div>
                                    <div class="p-1 px-2 bg-white rounded-full drop-shadow-md w-[212px] dark:bg-slate-700 text-2xl" uk-drop="offset:10;pos: top-left; animate-out: true; animation: uk-animation-scale-up uk-transform-origin-bottom-left">

                                        <div class="flex gap-2" uk-scrollspy="target: > button; cls: uk-animation-scale-up; delay: 100 ;repeat: true">
                                            <button type="button" class="text-red-600 hover:scale-125 duration-300"> <span> 👍 </span></button>
                                            <button type="button" class="text-red-600 hover:scale-125 duration-300"> <span> ❤️ </span></button>
                                            <button type="button" class="text-red-600 hover:scale-125 duration-300"> <span> 😂 </span></button>
                                            <button type="button" class="text-red-600 hover:scale-125 duration-300"> <span> 😯 </span></button>
                                            <button type="button" class="text-red-600 hover:scale-125 duration-300"> <span> 😢 </span></button>
                                        </div>

                                        <div class="w-2.5 h-2.5 absolute -bottom-1 left-3 bg-white rotate-45 hidden"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" class="button-icon bg-slate-200/70 dark:bg-slate-700"> <ion-icon class="text-lg" name="chatbubble-ellipses"></ion-icon> </button>
                                    <span>260</span>
                                </div>
                                <button type="button" class="button-icon ml-auto"> <ion-icon class="text-xl" name="paper-plane-outline"></ion-icon> </button>
                                <button type="button" class="button-icon"> <ion-icon class="text-xl" name="share-outline"></ion-icon> </button>
                            </div>

                            <!-- comments -->

                            <!-- add comment -->
                            <div class="sm:px-4 sm:py-3 p-2.5 border-t border-gray-100 flex items-center gap-1 dark:border-slate-700/40">

                                <img src="assets/images/avatars/avatar-7.jpg" alt="" class="w-6 h-6 rounded-full">

                                <div class="flex-1 relative overflow-hidden h-10">
                                    <textarea placeholder="Add Comment...." rows="1" class="w-full resize-none !bg-transparent px-4 py-2 focus:!border-transparent focus:!ring-transparent" aria-haspopup="true" aria-expanded="false"></textarea>

                                    <div class="!top-2 pr-2 uk-drop" uk-drop="pos: bottom-right; mode: click">
                                        <div class="flex items-center gap-2" uk-scrollspy="target: > svg; cls: uk-animation-slide-right-small; delay: 100 ;repeat: true">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 fill-sky-600" style="opacity: 0;">
                                                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 fill-pink-600" style="opacity: 0;">
                                                <path d="M3.25 4A2.25 2.25 0 001 6.25v7.5A2.25 2.25 0 003.25 16h7.5A2.25 2.25 0 0013 13.75v-7.5A2.25 2.25 0 0010.75 4h-7.5zM19 4.75a.75.75 0 00-1.28-.53l-3 3a.75.75 0 00-.22.53v4.5c0 .199.079.39.22.53l3 3a.75.75 0 001.28-.53V4.75z"></path>
                                            </svg>
                                        </div>
                                    </div>


                                </div>


                                <button type="submit" class="text-sm rounded-full py-1.5 px-3.5 bg-secondery"> Replay</button>
                            </div>

                        </div>

                        <!-- placeholder -->


                    </div>

                </div>

                <!-- sidebar -->
                <div class="flex-1">

                    <div class="lg:space-y-4 lg:pb-8 max-lg:grid sm:grid-cols-2 max-lg:gap-6 sm:mt-2" uk-sticky="media: 1024; end: #js-oversized; offset: 80">

                        <div class="box p-5 px-6">

                            <div class="flex items-baseline justify-between text-black dark:text-white">
                                <h3 class="font-bold text-base"> People you may know </h3>
                                <a href="#" class="text-sm text-blue-500">See all</a>
                            </div>

                            <div class="side-list">

                                <div class="side-list-item">
                                    <a href="timeline.html">
                                        <img src="assets/images/avatars/avatar-2.jpg" alt="" class="side-list-image rounded-full">
                                    </a>
                                    <div class="flex-1">
                                        <a href="timeline.html">
                                            <h4 class="side-list-title"> John Michael </h4>
                                        </a>
                                        <div class="side-list-info"> 125k Following </div>
                                    </div>
                                    <button class="button bg-primary-soft text-primary dark:text-white">follow</button>
                                </div>

                                <div class="side-list-item">
                                    <a href="timeline.html">
                                        <img src="assets/images/avatars/avatar-3.jpg" alt="" class="side-list-image rounded-full">
                                    </a>
                                    <div class="flex-1">
                                        <a href="timeline.html">
                                            <h4 class="side-list-title"> Monroe Parker </h4>
                                        </a>
                                        <div class="side-list-info"> 320k Following </div>
                                    </div>
                                    <button class="button bg-primary-soft text-primary dark:text-white">follow</button>
                                </div>

                                <div class="side-list-item">
                                    <a href="timeline.html">
                                        <img src="assets/images/avatars/avatar-5.jpg" alt="" class="side-list-image rounded-full">
                                    </a>
                                    <div class="flex-1">
                                        <a href="timeline.html">
                                            <h4 class="side-list-title"> James Lewis</h4>
                                        </a>
                                        <div class="side-list-info"> 125k Following </div>
                                    </div>
                                    <button class="button bg-primary-soft text-primary dark:text-white">follow</button>
                                </div>

                                <div class="side-list-item">
                                    <a href="timeline.html">
                                        <img src="assets/images/avatars/avatar-6.jpg" alt="" class="side-list-image rounded-full">
                                    </a>
                                    <div class="flex-1">
                                        <a href="timeline.html">
                                            <h4 class="side-list-title"> Alexa stella </h4>
                                        </a>
                                        <div class="side-list-info"> 192k Following </div>
                                    </div>
                                    <button class="button bg-primary-soft text-primary dark:text-white">follow</button>
                                </div>

                                <div class="side-list-item">
                                    <a href="timeline.html">
                                        <img src="assets/images/avatars/avatar-2.jpg" alt="" class="side-list-image rounded-full">
                                    </a>
                                    <div class="flex-1">
                                        <a href="timeline.html">
                                            <h4 class="side-list-title"> John Michael </h4>
                                        </a>
                                        <div class="side-list-info"> 320k Following </div>
                                    </div>
                                    <button class="button bg-primary-soft text-primary dark:text-white">follow</button>
                                </div>

                                <button class="bg-secondery button w-full mt-2 hidden">See all</button>

                            </div>

                        </div>

                        <!-- peaple you might know -->
                        <div class="box p-5 px-6 border1  dark:bg-dark2 hidden">

                            <div class="flex justify-between text-black dark:text-white">
                                <h3 class="font-bold text-base"> Peaple You might know </h3>
                                <button type="button"> <ion-icon name="sync-outline" class="text-xl"></ion-icon> </button>
                            </div>

                            <div class="space-y-4 capitalize text-xs font-normal mt-5 mb-2 text-gray-500 dark:text-white/80">

                                <div class="flex items-center gap-3">
                                    <a href="timeline.html">
                                        <img src="assets/images/avatars/avatar-7.jpg" alt="" class="bg-gray-200 rounded-full w-10 h-10">
                                    </a>
                                    <div class="flex-1">
                                        <a href="timeline.html">
                                            <h4 class="font-semibold text-sm text-black dark:text-white"> Johnson smith</h4>
                                        </a>
                                        <div class="mt-0.5"> Suggested For You </div>
                                    </div>
                                    <button type="button" class="text-sm rounded-full py-1.5 px-4 font-semibold bg-secondery"> Follow </button>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="timeline.html">
                                        <img src="assets/images/avatars/avatar-5.jpg" alt="" class="bg-gray-200 rounded-full w-10 h-10">
                                    </a>
                                    <div class="flex-1">
                                        <a href="timeline.html">
                                            <h4 class="font-semibold text-sm text-black dark:text-white"> James Lewis</h4>
                                        </a>
                                        <div class="mt-0.5"> Followed by Johnson </div>
                                    </div>
                                    <button type="button" class="text-sm rounded-full py-1.5 px-4 font-semibold bg-secondery"> Follow </button>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="timeline.html">
                                        <img src="assets/images/avatars/avatar-2.jpg" alt="" class="bg-gray-200 rounded-full w-10 h-10">
                                    </a>
                                    <div class="flex-1">
                                        <a href="timeline.html">
                                            <h4 class="font-semibold text-sm text-black dark:text-white"> John Michael</h4>
                                        </a>
                                        <div class="mt-0.5"> Followed by Monroe </div>
                                    </div>
                                    <button type="button" class="text-sm rounded-full py-1.5 px-4 font-semibold bg-secondery"> Follow </button>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="timeline.html">
                                        <img src="assets/images/avatars/avatar-3.jpg" alt="" class="bg-gray-200 rounded-full w-10 h-10">
                                    </a>
                                    <div class="flex-1">
                                        <a href="timeline.html">
                                            <h4 class="font-semibold text-sm text-black dark:text-white"> Monroe Parker</h4>
                                        </a>
                                        <div class="mt-0.5"> Suggested For You </div>
                                    </div>
                                    <button type="button" class="text-sm rounded-full py-1.5 px-4 font-semibold bg-secondery"> Follow </button>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="timeline.html">
                                        <img src="assets/images/avatars/avatar-4.jpg" alt="" class="bg-gray-200 rounded-full w-10 h-10">
                                    </a>
                                    <div class="flex-1">
                                        <a href="timeline.html">
                                            <h4 class="font-semibold text-sm text-black dark:text-white"> Martin Gray</h4>
                                        </a>
                                        <div class="mt-0.5"> Suggested For You </div>
                                    </div>
                                    <button type="button" class="text-sm rounded-full py-1.5 px-4 font-semibold bg-secondery"> Follow </button>
                                </div>
                            </div>

                        </div>


                        <!-- latest marketplace items -->


                        <!-- online friends -->


                        <!-- Pro Members -->


                        <!-- Trends -->
                        <div class="box p-5 px-6 border1 dark:bg-dark2">

                            <div class="flex justify-between text-black dark:text-white">
                                <h3 class="font-bold text-base"> Trends for you </h3>
                                <button type="button"> <ion-icon name="sync-outline" class="text-xl"></ion-icon> </button>
                            </div>

                            <div class="space-y-3.5 capitalize text-xs font-normal mt-5 mb-2 text-gray-600 dark:text-white/80">
                                <a href="#">
                                    <div class="flex items-center gap-3 p">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 -mt-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-black dark:text-white text-sm"> artificial intelligence </h4>
                                            <div class="mt-0.5"> 1,245,62 post </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block">
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 -mt-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-black dark:text-white text-sm"> Web developers</h4>
                                            <div class="mt-0.5"> 1,624 post </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block">
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 -mt-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-black dark:text-white text-sm"> Ui Designers</h4>
                                            <div class="mt-0.5"> 820 post </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block">
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 -mt-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
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
    <div class="hidden lg:p-20 max-lg:!items-start" id="preview_modal" uk-modal="">

        <div class="uk-modal-dialog tt relative mx-auto overflow-hidden shadow-xl rounded-lg lg:flex items-center ax-w-[86rem] w-full lg:h-[80vh]">

            <!-- image previewer -->
            <div class="lg:h-full lg:w-[calc(100vw-400px)] w-full h-96 flex justify-center items-center relative">

                <div class="relative z-10 w-full h-full">
                    <img src="assets/images/post/post-1.jpg" alt="" class="w-full h-full object-cover absolute">
                </div>

                <!-- close button -->
                <button type="button" class="bg-white rounded-full p-2 absolute right-0 top-0 m-3 uk-animation-slide-right-medium z-10 dark:bg-slate-600 uk-modal-close">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

            </div>

            <!-- right sidebar -->
            <div class="lg:w-[400px] w-full bg-white h-full relative  overflow-y-auto shadow-xl dark:bg-dark2 flex flex-col justify-between">

                <div class="p-5 pb-0">

                    <!-- story heading -->
                    <div class="flex gap-3 text-sm font-medium">
                        <img src="assets/images/avatars/avatar-5.jpg" alt="" class="w-9 h-9 rounded-full">
                        <div class="flex-1">
                            <h4 class="text-black font-medium dark:text-white"> Steeve </h4>
                            <div class="text-gray-500 text-xs dark:text-white/80"> 2 hours ago</div>
                        </div>

                        <!-- dropdown -->
                        <div class="-m-1">
                            <button type="button" class="button__ico w-8 h-8"> <ion-icon class="text-xl" name="ellipsis-horizontal"></ion-icon> </button>
                            <div class="w-[253px]" uk-dropdown="pos: bottom-right; animation: uk-animation-scale-up uk-transform-origin-top-right; animate-out: true">
                                <nav>
                                    <a href="#"> <ion-icon class="text-xl shrink-0" name="bookmark-outline"></ion-icon> Add to favorites </a>
                                    <a href="#"> <ion-icon class="text-xl shrink-0" name="notifications-off-outline"></ion-icon> Mute Notification </a>
                                    <a href="#"> <ion-icon class="text-xl shrink-0" name="flag-outline"></ion-icon> Report this post </a>
                                    <a href="#"> <ion-icon class="text-xl shrink-0" name="share-outline"></ion-icon> Share your profile </a>
                                    <hr>
                                    <a href="#" class="text-red-400 hover:!bg-red-50 dark:hover:!bg-red-500/50"> <ion-icon class="text-xl shrink-0" name="stop-circle-outline"></ion-icon> Unfollow </a>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <p class="font-normal text-sm leading-6 mt-4"> Photography is the art of capturing light with a camera. it can be fun, challenging. It can also be a hobby, a passion. 📷 </p>

                    <div class="shadow relative -mx-5 px-5 py-3 mt-3">
                        <div class="flex items-center gap-4 text-xs font-semibold">
                            <div class="flex items-center gap-2.5">
                                <button type="button" class="button__ico text-red-500 bg-red-100 dark:bg-slate-700"> <ion-icon class="text-lg" name="heart"></ion-icon> </button>
                                <a href="#">1,300</a>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" class="button__ico bg-slate-100 dark:bg-slate-700"> <ion-icon class="text-lg" name="chatbubble-ellipses"></ion-icon> </button>
                                <span>260</span>
                            </div>
                            <button type="button" class="button__ico ml-auto"> <ion-icon class="text-xl" name="share-outline"></ion-icon> </button>
                            <button type="button" class="button__ico"> <ion-icon class="text-xl" name="bookmark-outline"></ion-icon> </button>
                        </div>
                    </div>

                </div>

                <div class="p-5 h-full overflow-y-auto flex-1">

                    <!-- comment list -->
                    <div class="relative text-sm font-medium space-y-5">

                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-2.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> Steeve </a>
                                <p class="mt-0.5">What a beautiful, I love it. 😍 </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-3.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> Monroe </a>
                                <p class="mt-0.5"> You captured the moment.😎 </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-7.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> Alexa </a>
                                <p class="mt-0.5"> This photo is amazing! </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-4.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> John </a>
                                <p class="mt-0.5"> Wow, You are so talented 😍 </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-5.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> Michael </a>
                                <p class="mt-0.5"> I love taking photos 🌳🐶</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-3.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> Monroe </a>
                                <p class="mt-0.5"> Awesome. 😊😢 </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-5.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> Jesse </a>
                                <p class="mt-0.5"> Well done 🎨📸 </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-2.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> Steeve </a>
                                <p class="mt-0.5">What a beautiful, I love it. 😍 </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-7.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> Alexa </a>
                                <p class="mt-0.5"> This photo is amazing! </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-4.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> John </a>
                                <p class="mt-0.5"> Wow, You are so talented 😍 </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-5.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> Michael </a>
                                <p class="mt-0.5"> I love taking photos 🌳🐶</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 relative">
                            <img src="assets/images/avatars/avatar-3.jpg" alt="" class="w-6 h-6 mt-1 rounded-full">
                            <div class="flex-1">
                                <a href="#" class="text-black font-medium inline-block dark:text-white"> Monroe </a>
                                <p class="mt-0.5"> Awesome. 😊😢 </p>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="bg-white p-3 text-sm font-medium flex items-center gap-2">

                    <img src="assets/images/avatars/avatar-2.jpg" alt="" class="w-6 h-6 rounded-full">

                    <div class="flex-1 relative overflow-hidden ">
                        <textarea placeholder="Add Comment...." rows="1" class="w-full resize-  px-4 py-2 focus:!border-transparent focus:!ring-transparent resize-y"></textarea>

                        <div class="flex items-center gap-2 absolute bottom-0.5 right-0 m-3">
                            <ion-icon class="text-xl flex text-blue-700" name="image"></ion-icon>
                            <ion-icon class="text-xl flex text-yellow-500" name="happy"></ion-icon>
                        </div>

                    </div>

                    <button type="submit" class="hidden text-sm rounded-full py-1.5 px-4 font-semibold bg-secondery"> Replay</button>

                </div>

            </div>

        </div>

    </div>

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