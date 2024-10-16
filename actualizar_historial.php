<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['mascota_id'])) {
        die('ID de mascota no proporcionado.');
    }
    $mascota_id = $_GET['mascota_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['mascota_id'])) {
        die('ID de mascota no proporcionado.');
    }
    $mascota_id = $_POST['mascota_id'];
    $descripcion = $_POST['descripcion'];

    $query = "INSERT INTO historial_clinico (mascota_id, descripcion, fecha_actualizacion) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $mascota_id, $descripcion);

    if ($stmt->execute()) {
        echo "Historial actualizado con éxito.";
    } else {
        echo "Error al actualizar el historial.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Historial Clínico</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Actualizar Historial Clínico</h2>
        <form method="POST" action="">
            <input type="hidden" name="mascota_id" value="<?= htmlspecialchars($mascota_id); ?>">
            <div class="mb-4">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Actualizar Historial
            </button>
        </form>
        <a href="ver_mascotas.php" class="block text-center text-blue-600 font-semibold mt-6 hover:underline">
            Volver a Mascotas
        </a>
    </div>
</body>
</html>
