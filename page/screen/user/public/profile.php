<?php
session_start();
@include('../../../config.php');

if (!isset($_SESSION['nom_user'])) {
    header('location: ../../../auth/login.php');
}

$errorMsg = '';
$successMsg = '';
$id_user = $_SESSION['id_user'];

if (isset($_POST['submit-mode'])) {
    $new_name = $_POST['nomuser'];
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['teluser'];

    // Vérifier si le nouveau nom d'utilisateur existe déjà
    $check_username = $conn->prepare('SELECT id_user FROM user WHERE username = :new_username AND id_user != :id_user');
    $check_username->bindParam(':new_username', $new_username, PDO::PARAM_STR);
    $check_username->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $check_username->execute();

    if ($check_username->fetchColumn()) {
        $errorMsg = 'Le nom d\'utilisateur existe déjà.';
    } else {
        // Mettre à jour les informations dans la table user
        $update_info = $conn->prepare('UPDATE user SET nom_user = :new_name, username = :new_username, email_user = :new_email, tel_user = :new_phone WHERE id_user = :id_user');
        $update_info->bindParam(':new_name', $new_name, PDO::PARAM_STR);
        $update_info->bindParam(':new_username', $new_username, PDO::PARAM_STR);
        $update_info->bindParam(':new_email', $new_email, PDO::PARAM_STR);
        $update_info->bindParam(':new_phone', $new_phone, PDO::PARAM_STR);
        $update_info->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        if ($update_info->execute()) {
            $successMsg = 'Les informations ont été mises à jour avec succès.';
            // Rafraîchir les informations de l'utilisateur après la mise à jour
            $nom_client = $new_name;
            $username_client = $new_username;
            $email_user = $new_email;
            $phonenumber = $new_phone;
        } else {
            $errorMsg = 'Une erreur s\'est produite lors de la mise à jour des informations.';
        }
    }
}

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
    $recupAgent = $conn->prepare('SELECT nom_admin, phonenumber FROM admintable WHERE id_admin = :id_admin');
    $recupAgent->bindParam(':id_admin', $id_agent, PDO::PARAM_INT);
    $recupAgent->execute();

    if ($agent = $recupAgent->fetch()) {
        $nom_agent = $agent['nom_admin'];
        $phone_agent = $agent['phonenumber'];
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
                            <li>
                                <a href="user_page.php">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M11.3 3.3a1 1 0 0 1 1.4 0l6 6 2 2a1 1 0 0 1-1.4 1.4l-.3-.3V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3c0 .6-.4 1-1 1H7a2 2 0 0 1-2-2v-6.6l-.3.3a1 1 0 0 1-1.4-1.4l2-2 6-6Z" clip-rule="evenodd" />
                                    </svg>

                                    <span> Acceuil </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.1 12.6v-1.8A5.4 5.4 0 0 0 13 5.6V3a1 1 0 0 0-2 0v2.4a5.4 5.4 0 0 0-4 5.5v1.8c0 2.4-1.9 3-1.9 4.2 0 .6 0 1.2.5 1.2h13c.5 0 .5-.6.5-1.2 0-1.2-1.9-1.8-1.9-4.2Zm-13.2-.8a1 1 0 0 1-1-1c0-2.3.9-4.6 2.5-6.4a1 1 0 1 1 1.5 1.4 7.4 7.4 0 0 0-2 5 1 1 0 0 1-1 1Zm16.2 0a1 1 0 0 1-1-1c0-1.8-.7-3.6-2-5a1 1 0 0 1 1.5-1.4c1.6 1.8 2.5 4 2.5 6.4a1 1 0 0 1-1 1ZM8.8 19a3.5 3.5 0 0 0 6.4 0H8.8Z" />
                                    </svg>

                                    <span> Notifications </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.3 6A2 2 0 0 0 14 9h1v1a2 2 0 0 0 3 1.7l-.3 1.5a1 1 0 0 1-1 .8h-8l.2 1H16a3 3 0 1 1-2.8 2h-2.4a3 3 0 1 1-4-1.8L4.7 5H4a1 1 0 0 1 0-2h1.5c.5 0 .9.3 1 .8L6.9 6h5.4Z" />
                                        <path d="M18 4a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0V8h2a1 1 0 1 0 0-2h-2V4Z" />
                                    </svg>

                                    <span> Mes annonces </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
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

                            <li class="active">
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


                <div class="box relative rounded-lg shadow-md">

                    <div class="flex md:gap-8 gap-4 items-center md:p-8 p-6 md:pb-4">


                        <div class="relative md:w-20 md:h-20 w-12 h-12 shrink-0">

                            <label for="file" class="cursor-pointer">
                                <img id="img" src="assets/images/avatars/avatar-3.jpg" class="object-cover w-full h-full rounded-full" alt="" />
                                <input type="file" id="file" class="hidden" />
                            </label>

                            <label for="file" class="md:p-1 p-0.5 rounded-full bg-slate-600 md:border-4 border-white absolute -bottom-2 -right-2 cursor-pointer dark:border-slate-700">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="md:w-4 md:h-4 w-3 h-3 fill-white">
                                    <path d="M12 9a3.75 3.75 0 100 7.5A3.75 3.75 0 0012 9z" />
                                    <path fill-rule="evenodd" d="M9.344 3.071a49.52 49.52 0 015.312 0c.967.052 1.83.585 2.332 1.39l.821 1.317c.24.383.645.643 1.11.71.386.054.77.113 1.152.177 1.432.239 2.429 1.493 2.429 2.909V18a3 3 0 01-3 3h-15a3 3 0 01-3-3V9.574c0-1.416.997-2.67 2.429-2.909.382-.064.766-.123 1.151-.178a1.56 1.56 0 001.11-.71l.822-1.315a2.942 2.942 0 012.332-1.39zM6.75 12.75a5.25 5.25 0 1110.5 0 5.25 5.25 0 01-10.5 0zm12-1.5a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                </svg>

                                <input id="file" type="file" class="hidden" />

                            </label>

                        </div>

                        <div class="flex-1">
                            <h3 class="md:text-xl text-base font-semibold text-black dark:text-white"><?= $nom_client ?></h3>
                            <p class="text-sm text-blue-600 mt-1 font-normal"><?= '@' . $username_client ?></p>
                        </div>


                    </div>

                    <!-- nav tabs -->
                    <div class="relative border-b" tabindex="-1" uk-slider="finite: true">

                        <nav class="uk-slider-container overflow-hidden nav__underline px-6 p-0 border-transparent -mb-px">

                            <ul class="uk-slider-items w-[calc(100%+10px)] !overflow-hidden" uk-switcher="connect: #setting_tab ; animation: uk-animation-slide-right-medium, uk-animation-slide-left-medium">

                                <li class="w-auto pr-2.5"> <a href="#"> Information personnel </a> </li>
                                <li class="w-auto pr-2.5"> <a href="#"> Modifier profile</a> </li>
                                <li class="w-auto pr-2.5"> <a href="#"> Modifier mot de passe</a> </li>


                            </ul>

                        </nav>

                        <a class="absolute -translate-y-1/2 top-1/2 left-0 flex items-center w-20 h-full p-2 py-1 justify-start bg-gradient-to-r from-white via-white dark:from-slate-800 dark:via-slate-800" href="#" uk-slider-item="previous"> <ion-icon name="chevron-back" class="text-2xl ml-1"></ion-icon> </a>
                        <a class="absolute right-0 -translate-y-1/2 top-1/2 flex items-center w-20 h-full p-2 py-1 justify-end bg-gradient-to-l from-white via-white dark:from-slate-800 dark:via-slate-800" href="#" uk-slider-item="next"> <ion-icon name="chevron-forward" class="text-2xl mr-1"></ion-icon> </a>

                    </div>


                    <div id="setting_tab" class="uk-switcher md:py-12 md:px-20 p-6 overflow-hidden text-black text-sm">


                        <!-- tab user basic info -->
                        <div>

                            <div>

                                <div class="space-y-6">

                                    <div class="md:flex items-center gap-10">
                                        <h5 class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80">Nom</h5>
                                        <div class="flex-1 max-md:mt-4 text-blue-500">
                                            <?= $nom_client ?>
                                        </div>
                                    </div>
                                    <div class="md:flex items-center gap-10">
                                        <h5 class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80">Username</h5>
                                        <div class="flex-1 max-md:mt-4 text-blue-500">
                                            <?= $username_client ?>
                                        </div>
                                    </div>

                                    <div class="md:flex items-center gap-10">
                                        <h5 class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80">Email</h5>
                                        <div class="flex-1 max-md:mt-4 text-blue-500">
                                            <?= $email_user ?>
                                        </div>
                                    </div>

                                    <div class="md:flex items-center gap-10">
                                        <h5 class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80">Télephone</h5>
                                        <div class="flex-1 max-md:mt-4 text-blue-500">
                                            <?= $phonenumber ?>
                                        </div>
                                    </div>

                                    <div class="md:flex items-center gap-10">
                                        <h5 class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80">Type d'acteur</h5>
                                        <div class="flex-1 max-md:mt-4 text-blue-500">
                                            <?= $actorType ?>
                                        </div>
                                    </div>

                                    <div class="md:flex items-center gap-10">
                                        <h5 class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80">Secteur d'activité</h5>
                                        <div class="flex-1 max-md:mt-4 text-blue-500">
                                            <?= $activSector_user ?>
                                        </div>
                                    </div>

                                    <div class="md:flex items-center gap-10">
                                        <h5 class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80">Adress</h5>
                                        <div class="flex-1 max-md:mt-4 text-blue-500">
                                            <?= $adress_user ?>
                                        </div>
                                    </div>

                                    <div class="md:flex items-center gap-10">
                                        <h5 class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80">Conctact Agent</h5>
                                        <div class="flex-1 max-md:mt-4 text-blue-500">
                                            <?= $phone_agent ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- tab socialinks -->
                        <div>

                            <div>

                                <form action="" method="post">

                                    <div class="space-y-6">


                                        <div class="md:flex items-center gap-10">
                                            <label class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80"> Nom </label>
                                            <div class="flex-1 max-md:mt-4">
                                                <input type="text" name="nomuser" value="<?= $nom_client ?>" class="lg:w-1/2 w-full">
                                            </div>
                                        </div>

                                        <div class="md:flex items-center gap-10">
                                            <label class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80"> Username </label>
                                            <div class="flex-1 max-md:mt-4">
                                                <input type="text" name="username" value="<?= $username_client ?>" class="lg:w-1/2 w-full">
                                            </div>
                                        </div>

                                        <div class="md:flex items-center gap-10">
                                            <label class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80"> Email </label>
                                            <div class="flex-1 max-md:mt-4">
                                                <input type="email" name="email" value="<?= $email_user ?>" class="lg:w-1/2 w-full">
                                            </div>
                                        </div>

                                        <div class="md:flex items-center gap-10">
                                            <label class="md:w-32 text-right text-gray-500 text-xs dark:text-white/80"> Télephone </label>
                                            <div class="flex-1 max-md:mt-4">
                                                <input type="text" name="teluser" value="<?= $phonenumber ?>" class="lg:w-1/2 w-full">
                                            </div>
                                        </div>


                                    </div>

                                    <div class="flex items-center gap-4 mt-16 lg:pl-[10.5rem]">
                                        <button type="reset" class="button lg:px-6 bg-secondery max-md:flex-1"> Annuler</button>
                                        <button type="submit" name="submit-mode" class="button lg:px-10 bg-primary text-white max-md:flex-1"> Enregistrer <span class="ripple-overlay"></span></button>
                                    </div>

                                </form>

                            </div>

                        </div>

                        <!-- tab checkbox -->

                        <div>

                            <div>

                                <div class="space-y-6 max-w-lg mx-auto">

                                    <div class="md:flex items-center gap-16 justify-between max-md:space-y-3">
                                        <label class="md:w-40 text-right text-xs dark:text-white/80"> Mot de passe actuel </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <input type="password" placeholder="******" class="w-full">
                                        </div>
                                    </div>

                                    <div class="md:flex items-center gap-16 justify-between max-md:space-y-3">
                                        <label class="md:w-40 text-right text-xs dark:text-white/80"> Nouveau mot de passe</label>
                                        <div class="flex-1 max-md:mt-4">
                                            <input type="password" placeholder="******" class="w-full">
                                        </div>
                                    </div>

                                    <div class="md:flex items-center gap-16 justify-between max-md:space-y-3">
                                        <label class="md:w-40 text-right text-xs dark:text-white/80"> Confirmer mot de passe </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <input type="password" placeholder="******" class="w-full">
                                        </div>
                                    </div>




                                </div>

                                <div class="flex items-center justify-center gap-4 mt-16">
                                    <button type="submit" class="button lg:px-6 bg-secondery max-md:flex-1"> Annuler</button>
                                    <button type="submit" class="button lg:px-10 bg-primary text-white max-md:flex-1"> Enregistrer</button>
                                </div>

                            </div>

                        </div>

                        <!-- tab toggle options-->

                        <!-- tab password-->
                        <div>

                            <div>

                                <div class="space-y-6 max-w-lg mx-auto">

                                    <div class="md:flex items-center gap-16 justify-between max-md:space-y-3">
                                        <label class="md:w-40 text-right"> Current Password </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <input type="password" placeholder="******" class="w-full">
                                        </div>
                                    </div>

                                    <div class="md:flex items-center gap-16 justify-between max-md:space-y-3">
                                        <label class="md:w-40 text-right"> New password </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <input type="password" placeholder="******" class="w-full">
                                        </div>
                                    </div>

                                    <div class="md:flex items-center gap-16 justify-between max-md:space-y-3">
                                        <label class="md:w-40 text-right"> Repeat password </label>
                                        <div class="flex-1 max-md:mt-4">
                                            <input type="password" placeholder="******" class="w-full">
                                        </div>
                                    </div>

                                </div>

                                <div class="flex items-center justify-center gap-4 mt-16">
                                    <button type="submit" class="button lg:px-6 bg-secondery max-md:flex-1"> Cancle</button>
                                    <button type="submit" class="button lg:px-10 bg-primary text-white max-md:flex-1"> Save</button>
                                </div>

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