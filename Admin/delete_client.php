<?php
session_start();
@include('../page/config.php');

if (!isset($_SESSION['username'])) {
    header('location: ../page/auth/adlogin.php');
}

if (isset($_POST['id_user']) && is_numeric($_POST['id_user'])) {
    $id_user = $_POST['id_user'];

    // Delete the user from the database
    $deleteUser = $conn->prepare('DELETE FROM user WHERE id_user = :id_user');
    $deleteUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $deleteUser->execute();

    // Redirect to the listclient.php page
    header('location: listclient.php');
} else {
    // Redirect to the listclient.php page with an error message
    header('location: listclient.php?error=true');
}
?>