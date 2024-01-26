<?php
session_start();
@include('../page/config.php');

if (!isset($_SESSION['username'])) {
    header('location: ../page/auth/adlogin.php');
}

if (isset($_POST['id_admin']) && is_numeric($_POST['id_admin'])) {
    $id_agent = $_POST['id_admin'];

    // Delete the user from the database
    $deleteAgent = $conn->prepare('DELETE FROM adminTable WHERE id_admin = :id_admin');
    $deleteAgent->bindParam(':id_admin', $id_agent, PDO::PARAM_INT);
    $deleteAgent->execute();

    // Redirect to the listclient.php page
    header('location: listagent.php');
} else {
    // Redirect to the listclient.php page with an error message
    header('location: listagent.php?error=true');
}
?>