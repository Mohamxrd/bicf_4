<?php

session_start();

$conn = new PDO('mysql:host=localhost;dbname=bicf;charset=utf8;', 'root', 'root');



$errorMsg = '';

if (isset($_POST['submit'])) {
	if (!empty($_POST['username']) && !empty($_POST['password'])) {
		$username = trim(htmlspecialchars($_POST['username']));

		$mdpSoumis = htmlspecialchars($_POST['password']);

		$recupUser = $conn->prepare('SELECT * FROM admin WHERE username_admin = ?');
		$recupUser->execute(array($username));

		if ($recupUser->rowCount() > 0) {
			$admin = $recupUser->fetch();

			$mdpDansLaBase = $admin['password_admin'];

			// Vérifiez si le mot de passe soumis correspond au mot de passe dans la base de données
			if (password_verify($mdpSoumis, $mdpDansLaBase)) {


				$_SESSION['username'] = $admin['username_admin'];
				$_SESSION['nom_user'] = $admin['nom_admin'];
				header('location: ../../Agent/index.php');

				exit();
			} else {
				$errorMsg = "Mauvais identifiant ou mot de passe";
			}
		} else {
			$errorMsg = "Mauvais identifiant ou mot de passe";
		}
	} else {
		$errorMsg = 'Veuillez remplir tous les champs';
	}
}


?>





<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login V16</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="../../fonts/Linearicons-Free-v1.0.0/icon-font.min.css">

	<link rel="stylesheet" type="text/css" href="../../css/adlogutil.css">
	<link rel="stylesheet" type="text/css" href="../../css/adlog.css">

</head>

<body>

	<div class="limiter">
		<div class="container-login100" style="background-image: url('../../images/bg-02.jpg');">
			<div class="wrap-login100 p-t-30 p-b-50">

				<form class="login100-form validate-form p-b-33 p-t-5" method="post">

					<div class="wrap-input100 validate-input">
						<input class="input100" type="text" name="username" placeholder="Nom d'utlisateur">
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>

					<div class="wrap-input100 validate-input">
						<input class="input100" type="password" name="password" placeholder="Mot de passe">
						<span class="focus-input100" data-placeholder="&#xe80f;"></span>
					</div>

					<?php

					if (!empty($errorMsg)) {
						echo '<div class="error-msg">' . $errorMsg . '</div>';
					}
					?>

                    

					<div class="container-login100-form-btn m-t-32">
						<button class="login100-form-btn" type="submit" name="submit">
							Se connecter
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>


	<div id="dropDownSelect1"></div>



</body>

</html>