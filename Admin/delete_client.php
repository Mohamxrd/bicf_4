<?php
session_start();
@include('../page/config.php');

if (!isset($_SESSION['username'])) {
    header('location: ../page/auth/adlogin.php');
    exit();
}

if (isset($_POST['id_user']) && is_numeric($_POST['id_user'])) {
    $id_user = $_POST['id_user'];

    try {
        // Début de la transaction
        $conn->beginTransaction();

        // Suppression des enregistrements dans l'ordre souhaité
        $deleteConsprodUser = $conn->prepare('DELETE FROM consprodUser WHERE id_user = :id_user');
        $deleteConsprodUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $deleteConsprodUser->execute();

        $deleteConsservUser = $conn->prepare('DELETE FROM consservUser WHERE id_user = :id_user');
        $deleteConsservUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $deleteConsservUser->execute();

        $deleteProdUser = $conn->prepare('DELETE FROM prodUser WHERE id_user = :id_user');
        $deleteProdUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $deleteProdUser->execute();

        $deleteServUser = $conn->prepare('DELETE FROM servUser WHERE id_user = :id_user');
        $deleteServUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $deleteServUser->execute();

        $deleteUser = $conn->prepare('DELETE FROM user WHERE id_user = :id_user');
        $deleteUser->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result = $deleteUser->execute();

        // Commit de la transaction si tout s'est bien passé
        $conn->commit();

        // Redirection en cas de succès
        if ($result) {
            header('location: listclient.php');
            exit();
        } else {
            header('location: listclient.php?error=true');
            exit();
        }
    } catch (PDOException $e) {
        // En cas d'erreur, rollback de la transaction et redirection avec un message d'erreur
        $conn->rollBack();
        header('location: listclient.php?error=true');
        exit();
    }
} else {
    header('location: listclient.php?error=true');
    exit();
}
?>

