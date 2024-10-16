<?php
session_start();
require 'config.php'; // Archivo que contiene la conexión a la base de datos y otras configuraciones

// Consulta para obtener los datos actuales del usuario al cargar la página
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Manejar la actualización del perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $query = "UPDATE usuarios SET nombre = ?, email = ?, telefono = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $nombre, $email, $telefono, $user_id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Perfil actualizado correctamente.";

        // Hacer una nueva consulta para obtener los datos actualizados
        $query = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el perfil.";
    }
}
?>

<!-- Código HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Editar Perfil</h2>
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                <?= $_SESSION['mensaje']; ?>
            </div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($user['nombre']); ?>" required
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($user['telefono']); ?>"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Guardar Cambios
            </button>
        </form>
        <a href="dashboard.php" class="block text-center text-blue-600 font-semibold mt-6 hover:underline">
            Volver al Dashboard
        </a>
    </div>
</body>
</html>
