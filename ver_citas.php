<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die('No tienes permiso para acceder a esta página.');
}

$user_id = $_SESSION['user_id'];

// Consulta corregida
$query = "
    SELECT 
        c.id AS cita_id,
        c.fecha_cita,
        c.motivo,
        c.estado,
        m.nombre AS mascota_nombre,
        u.nombre AS veterinario_nombre
    FROM citas c
    JOIN mascotas m ON c.mascota_id = m.id
    JOIN usuarios u ON c.veterinario_id = u.id
    WHERE m.propietario_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Citas Agendadas</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Fecha de la Cita</th>
                        <th class="py-2">Motivo</th>
                        <th class="py-2">Mascota</th>
                        <th class="py-2">Veterinario</th>
                        <th class="py-2">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['fecha_cita']); ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['motivo']); ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['mascota_nombre']); ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['veterinario_nombre']); ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['estado']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-gray-700">No hay citas agendadas.</p>
        <?php endif; ?>
        <a href="dashboard.php" class="block text-center text-blue-600 font-semibold mt-6 hover:underline">
            Volver al Dashboard
        </a>
    </div>
</body>
</html>
