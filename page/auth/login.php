<?php

@include('../config.php');

$errorMsg = '';

if (isset($_POST['submit'])) {
	if (!empty($_POST['username']) && !empty($_POST['password'])) {
		$username = htmlspecialchars($_POST['username']);
		$mdpSoumis = $_POST['password'];

		$recupUser = $conn->prepare('SELECT * FROM user WHERE username = ?');
		$recupUser->execute(array($username));

		if ($recupUser->rowCount() > 0) {
			$user = $recupUser->fetch();

			$mdpDansLaBase = $user['password'];

			// Vérifiez si le mot de passe soumis correspond au mot de passe dans la base de données
			if (password_verify($mdpSoumis, $mdpDansLaBase)) {

		
					$_SESSION['username'] = $user['username'];
					$_SESSION['nom_user'] = $user['nom_user'];
					header('location: ../screen/user/user_page.php');
				






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
	<title>BICF</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<link rel="stylesheet" type="text/css" href="../../css/util.css">
	<link rel="stylesheet" type="text/css" href="../../css/main2.css">

	<link href="../../css/nucleo-icons.css" rel="stylesheet" />
	<link href="../../css/nucleo-svg.css" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>

</head>

<body>

	<body class="">

		<main class="main-content  mt-0">
			<section>
				<div class="page-header min-vh-75">
					<div class="container">
						<div class="row">
							<div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
								<div class="card card-plain mt-8">
									<div class="card-header pb-0 text-left bg-transparent">
										<h3 class="font-weight-bolder text-info text-gradient">BICF</h3>
										<p class="mb-0">Entrez votre nom d'utlisateur et votre mot de passe pour vous
											connecter</p>
									</div>
									<div class="card-body">
										<form action="" role="form" method="post">
											<label>Nom d'utlisateur</label>
											<div class="mb-3">
												<input type="text" class="form-control" placeholder="Nom d'utlisateur" aria- label="Email" aria-describedby="email-addon" name="username" autocomplete="off">
											</div>
											<?php

											if (!empty($errorMsg)) {
												echo '<div class="error-msg">' . $errorMsg . '</div>';
											}
											?>

											<label>Mot de passe</label>
											<div class="mb-3">
												<input type="password" class="form-control" placeholder="Mot de passe" aria-label="Password" aria-describedby="password-addon" name="password" autocomplete="off">
											</div>

											<div class="form-check form-switch ">
												<input class="form-check-input" type="checkbox" id="rememberMe" checked="">
												<label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
											</div>
											<div class="text-center">
												<button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0" name="submit">Se connecter</button>
											</div>
										</form>
									</div>



									<div class="card-footer text-center ">
										<p class="text-sm mx-auto m-0">
											Mot de passe oublié ?
											<a href="send_pass.php" class="text-info text-gradient font-weight-bold ">Cliquez ici</a>
										</p>
										<p class="mb-4 text-sm mx-auto m-0">
											Vouys n'avez pas de compte ?
											<a href="register.php" class="text-info text-gradient font-weight-bold">Créer un compte</a>
										</p>
									</div>

								</div>
							</div>
							<div class="col-md-6">
								<div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
									<div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../../images/bg-02.jpg')"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>
		<!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
		<footer class="footer py-5">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 mb-4 mx-auto text-center">
						<a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
							Contact
						</a>
						<a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
							A propos
						</a>
						<a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
							Team
						</a>
						<a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
							Produits
						</a>
						<a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
							Blog
						</a>
						<a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
							Prix
						</a>
					</div>

				</div>

			</div>
		</footer>






		<script src="../../js/core/popper.min.js"></script>
		<script src="../../js/core/bootstrap.min.js"></script>
		<script src="../../js/plugins/perfect-scrollbar.min.js"></script>
		<script src="../../js/plugins/smooth-scrollbar.min.js"></script>
		<script async defer src="https://buttons.github.io/buttons.js"></script>
		<script src="../../js/soft-ui-dashboard.min.js?v=1.0.7"></script>

	</body>

</html>