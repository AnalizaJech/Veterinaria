<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die('No tienes permiso para acceder a esta página.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mascota_id = $_POST['mascota_id'];
    $veterinario_id = $_POST['veterinario_id'];
    $fecha_cita = $_POST['fecha_cita'];
    $motivo = $_POST['motivo'];

    $query = "INSERT INTO citas (mascota_id, veterinario_id, fecha_cita, motivo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss", $mascota_id, $veterinario_id, $fecha_cita, $motivo);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Cita agendada con éxito.";
        header("Location: ver_citas.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error al agendar la cita." . $stmt->error ."-".$mascota_id."-". $veterinario_id."-". $fecha_cita. "-".$motivo;
    }
}

// Obtener mascotas del usuario
$user_id = $_SESSION['user_id'];
$query_mascotas = "SELECT id, nombre FROM mascotas WHERE propietario_id = ?";
$stmt_mascotas = $conn->prepare($query_mascotas);
$stmt_mascotas->bind_param("i", $user_id);
$stmt_mascotas->execute();
$mascotas = $stmt_mascotas->get_result();

// Obtener veterinarios (solo usuarios con rol 'veterinario')
$query_veterinarios = "SELECT id, nombre FROM usuarios WHERE rol = 'veterinario'";
$veterinarios = $conn->query($query_veterinarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Agendar Nueva Cita</h2>
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                <?= $_SESSION['mensaje']; ?>
            </div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="mascota_id" class="block text-sm font-medium text-gray-700 mb-2">Mascota:</label>
                <select id="mascota_id" name="mascota_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php while ($row = $mascotas->fetch_assoc()): ?>
                        <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="veterinario_id" class="block text-sm font-medium text-gray-700 mb-2">Veterinario:</label>
                <select id="veterinario_id" name="veterinario_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php while ($row = $veterinarios->fetch_assoc()): ?>
                        <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="fecha_cita" class="block text-sm font-medium text-gray-700 mb-2">Fecha de la Cita:</label>
                <input type="datetime-local" id="fecha_cita" name="fecha_cita" required
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">Motivo:</label>
                <input type="text" id="motivo" name="motivo" required
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Agendar Cita
            </button>
        </form>
        <a href="dashboard.php" class="block text-center text-blue-600 font-semibold mt-6 hover:underline">
            Volver al Dashboard
        </a>
    </div>
</body>
</html>
