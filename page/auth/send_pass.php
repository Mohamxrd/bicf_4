<?php
session_start();

$error_message = "";

// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
    // Récupérer l'email du formulaire
    $email = $_POST["email"];

    // Inclure le fichier de configuration de la base de données
    $conn = new mysqli('localhost', 'root', 'root', 'bicf');

    // Vérifier si la connexion a échoué
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Vérifier si l'email existe dans la base de données
    $checkEmailSql = "SELECT * FROM user WHERE email_user = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSql);
    $checkEmailStmt->bind_param('s', $email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows === 0) {
        // L'email n'existe pas dans la base de données, définir le message d'erreur
        $error_message = "L'email n'existe pas dans la base de données.";
    } else {
       

        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);
        $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

        $sql = "UPDATE user
                SET reset_token_hash = ?,
                    reset_token_expires_at = ?
                WHERE email_user = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param('sss', $token_hash, $expiry, $email);

        $stmt->execute();

        if ($conn->affected_rows) {
            require __DIR__ . '/mailer.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            $mail->setFrom("bricohack813@gmail.com");
            $mail->addAddress($email);
            $mail->Subject = "Password Reset";
            $mail->Body = <<<END
            Click <a href="http://localhost:8888/bicf/page/auth/reset_pass.php?token=$token">here</a> 
            to reset your password.
END;

            try {
                $mail->send();
				$error_message = "Message sent, please check your inbox.";
            } catch (Exception $e) {
				$error_message = "Le message n'a pas pu être envoyé. Erreur du système de messagerie: {$mail->ErrorInfo}";
            }
        }
    }

    // Fermer la connexion
    $conn->close();
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=, initial-scale=1.0">
	<title>Mot de passe oublé</title>

	<link rel="stylesheet" type="text/css" href="../../css/util.css">
	<link rel="stylesheet" type="text/css" href="../../css/main2.css">

	<link href="../../css/nucleo-icons.css" rel="stylesheet" />
	<link href="../../css/nucleo-svg.css" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body>
	<main class="main-content  mt-0">
		<section>
			<div class="page-header min-vh-75">
				<div class="container">
					<div class="row">
						<div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
							<div class="card card-plain mt-8">
								<div class="card-header pb-0 text-left bg-transparent">
									<h3 class="font-weight-bolder forg-text ">Mot de passe oublié ? Entrez votre email ici</h3>

								</div>
								<div class="card-body">
									<form action="" role="form" method="post">
										<label>Email</label>
										<div class="mb-2">
											<input type="text" class="form-control" placeholder="Email" aria- label="Email" aria-describedby="email-addon" name="email" autocomplete="off">
										</div>
										<?php

										if (!empty($error_message)) {
											echo '<div class="error-msg">' . $error_message . '</div>';
										}
										?>


										<div class="text-center">
											<button type="submit" class="btn bg-gradient-info w-100 mt-2 mb-0" name="submit">Envoyer</button>
										</div>
									</form>
								</div>





							</div>
						</div>
						<div class="col-md-6">
							<div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
								<div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../../images/curved14.jpg')"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
</body>

</html>