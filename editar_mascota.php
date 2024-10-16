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
        
        // Consultar los datos actuales de la mascota
        $stmt = $conn->prepare("SELECT nombre, especie, raza, edad FROM mascotas WHERE id = ? AND propietario_id = ?");
        $stmt->bind_param("ii", $mascota_id, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $mascota = $result->fetch_assoc();
        } else {
            echo "Mascota no encontrada.";
            exit();
        }
        $stmt->close();
    } else {
        echo "ID de mascota no proporcionado.";
        exit();
    }

    // Procesar la actualización de la mascota
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $especie = $_POST['especie'];
        $raza = $_POST['raza'];
        $edad = $_POST['edad'];

        $stmt = $conn->prepare("UPDATE mascotas SET nombre = ?, especie = ?, raza = ?, edad = ? WHERE id = ? AND propietario_id = ?");
        $stmt->bind_param("sssiii", $nombre, $especie, $raza, $edad, $mascota_id, $_SESSION['user_id']);

        if ($stmt->execute()) {
            $mensaje = "Mascota actualizada con éxito.";
            // Actualizar los valores de la variable $mascota para reflejar los cambios en los inputs
            $mascota['nombre'] = $nombre;
            $mascota['especie'] = $especie;
            $mascota['raza'] = $raza;
            $mascota['edad'] = $edad;
        } else {
            $error = "Error al actualizar la mascota.";
        }
        $stmt->close();
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mascota</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 max-w-xl bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center mb-6">Editar Mascota</h2>
        <?php if (isset($mensaje)): ?>
            <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
                <?= htmlspecialchars($mensaje); ?>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="bg-red-200 text-red-800 p-4 rounded mb-4">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form action="editar_mascota.php?id=<?= $mascota_id; ?>" method="POST" class="space-y-4">
            <div>
                <label for="nombre" class="block font-bold">Nombre de la Mascota:</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($mascota['nombre']); ?>" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="especie" class="block font-bold">Especie:</label>
                <input type="text" name="especie" id="especie" value="<?= htmlspecialchars($mascota['especie']); ?>" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="raza" class="block font-bold">Raza:</label>
                <input type="text" name="raza" id="raza" value="<?= htmlspecialchars($mascota['raza']); ?>" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="edad" class="block font-bold">Edad:</label>
                <input type="number" name="edad" id="edad" value="<?= htmlspecialchars($mascota['edad']); ?>" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-700">Guardar Cambios</button>
            <div class="flex justify-center mt-4">
                <a href="ver_mascotas.php" class="text-blue-500 hover:underline">Volver a Información de Mascotas</a>
            </div>
        </form>
    </div>
</body>
</html>
