<?php
    session_start();
    require 'config.php';

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Consultar las mascotas del usuario
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT id, nombre, especie, raza, edad FROM mascotas WHERE propietario_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Mascotas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 max-w-4xl bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Información de Mascotas</h2>
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">Nombre</th>
                    <th class="py-3 px-6 text-left">Especie</th>
                    <th class="py-3 px-6 text-left">Raza</th>
                    <th class="py-3 px-6 text-left">Edad</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($row['nombre']); ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($row['especie']); ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($row['raza']); ?></td>
                        <td class="py-3 px-6 text-left"><?= htmlspecialchars($row['edad']); ?></td>
                        <td class="py-3 px-6 text-center">
                            <a href="editar_mascota.php?id=<?= $row['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 mr-2">Editar</a>
                            <a href="eliminar_mascota.php?id=<?= $row['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('¿Estás seguro de que deseas eliminar esta mascota?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="flex justify-center mt-6">
            <a href="dashboard.php" class="text-blue-500 hover:underline">Volver al Dashboard</a>
        </div>
    </div>
</body>
</html>
<?php
    $stmt->close();
    $conn->close();
?>
