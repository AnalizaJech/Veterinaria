<?php
    session_start();
    require 'config.php';

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Verificar si se ha pasado el ID de la mascota
    if (isset($_GET['id'])) {
        $mascota_id = $_GET['id'];

        // Eliminar la mascota de la base de datos
        $stmt = $conn->prepare("DELETE FROM mascotas WHERE id = ? AND propietario_id = ?");
        $stmt->bind_param("ii", $mascota_id, $_SESSION['user_id']);
        if ($stmt->execute()) {
            header("Location: ver_mascotas.php?mensaje=Mascota eliminada con éxito.");
            exit();
        } else {
            header("Location: ver_mascotas.php?error=Error al eliminar la mascota.");
            exit();
        }
    } else {
        header("Location: ver_mascotas.php");
        exit();
    }
?>
