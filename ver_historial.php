<?php
session_start();
require 'config.php';

if (!isset($_GET['mascota_id'])) {
    die('ID de mascota no proporcionado.');
}

$mascota_id = $_GET['mascota_id'];
$query = "SELECT * FROM historial_clinico WHERE mascota_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $mascota_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Historial Clínico</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Historial Clínico</h2>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="mb-4">
                <p><strong>Fecha:</strong> <?= htmlspecialchars($row['fecha_actualizacion']); ?></p>
                <p><strong>Descripción:</strong> <?= htmlspecialchars($row['descripcion']); ?></p>
                <hr class="my-4">
            </div>
        <?php endwhile; ?>
        <a href="ver_mascotas.php" class="block text-center text-blue-600 font-semibold mt-6 hover:underline">
            Volver a Mascotas
        </a>
    </div>
</body>
</html>
