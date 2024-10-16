<?php
    session_start();
    require 'config.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $edad = $_POST['edad'];
        $raza = $_POST['raza'];
        $especie = $_POST['especie']; // Nueva columna para especie
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO mascotas (nombre, edad, raza, especie, propietario_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sissi", $nombre, $edad, $raza, $especie, $user_id);
        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Error al registrar la mascota";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mascota</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 max-w-xl bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Registrar Nueva Mascota</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-200 text-red-800 p-4 rounded mb-6">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <form action="registrar_mascota.php" method="POST" class="mb-4">
            <div class="mb-6">
                <label for="nombre" class="block text-lg font-semibold mb-2">Nombre de la Mascota:</label>
                <input type="text" name="nombre" id="nombre" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="especie" class="block text-lg font-semibold mb-2">Especie:</label>
                <input type="text" name="especie" id="especie" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="edad" class="block text-lg font-semibold mb-2">Edad:</label>
                <input type="number" name="edad" id="edad" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="raza" class="block text-lg font-semibold mb-2">Raza:</label>
                <input type="text" name="raza" id="raza" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="flex flex-col items-center">
                <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 mb-4">Registrar</button>
                <a href="dashboard.php" class="text-blue-500 hover:underline">Volver al Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>
