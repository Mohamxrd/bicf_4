<?php
@include('../../config.php');

session_start();

if(!isset($_SESSION['nom_user'])){
    header('location: ../../auth/login.php');
}


?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User page</title>
    <link rel="stylesheet" type="text/css" href="../../../css/main2.css">
   

    <link href="../../css/nucleo-icons.css" rel="stylesheet" />
    <link href="../../css/nucleo-svg.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body>
    <div class="container_admin">
        <div class="content">
            <h3>Salut, <span>User</span></h3>
            <h1>Bienvenu <span><?php echo $_SESSION['nom_user'] ?></span></h1>
            <p>Ceci est la page de d'un utlisateur</p>
            <a href="../../auth/login.php" class="btn">Se connecter</a>
            <a href="../../auth/register.php" class="btn">Créer un compte</a>
            <a href="../../logout.php" class="btn">Se déconnecter</a>

        </div>


    </div>
</body>
<script async defer src="https://buttons.github.io/buttons.js"></script>
</html>