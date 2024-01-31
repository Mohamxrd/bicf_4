<?php
session_start();
@include('../page/config.php');

if (!isset($_SESSION['username'])) {
    header('location: ../page/auth/adlogin.php');
}

if (isset($_POST['id_admin']) && is_numeric($_POST['id_admin'])) {
    $id_agent = $_POST['id_admin'];

    try {
        // Début de la transaction
        $conn->beginTransaction();

        // Mettre à jour les utilisateurs liés en définissant id_admin à null
        $updateUsers = $conn->prepare('UPDATE user SET id_admin = null WHERE id_admin = :id_admin');
        $updateUsers->bindParam(':id_admin', $id_agent, PDO::PARAM_INT);
        $updateUsers->execute();

        // Supprimer l'agent de la table adminTable
        $deleteAgent = $conn->prepare('DELETE FROM adminTable WHERE id_admin = :id_admin');
        $deleteAgent->bindParam(':id_admin', $id_agent, PDO::PARAM_INT);
        $deleteAgent->execute();

        // Commit de la transaction si tout s'est bien passé
        $conn->commit();

        // Redirection en cas de succès
        header('location: listagent.php');
    } catch (PDOException $e) {
        // En cas d'erreur, rollback de la transaction et redirection avec un message d'erreur
        $conn->rollBack();
        header('location: listagent.php?error=true');
    }
} else {
    // Redirect to the listagent.php page with an error message
    header('location: listagent.php?error=true');
}
?>
