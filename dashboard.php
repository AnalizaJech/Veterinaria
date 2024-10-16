<?php
session_start();
require 'config.php'; // Incluye la conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el user_id de la sesión
$user_id = $_SESSION['user_id'];

// Consultar el nombre del usuario desde la base de datos
$stmt = $conn->prepare("SELECT nombre FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['nombre'];
} else {
    $user_name = "Usuario";
}

$stmt->close();

// Consultar la cantidad de citas programadas para hoy
$queryCitas = "SELECT COUNT(*) AS cantidad FROM citas WHERE DATE(fecha_cita) = CURDATE()";
$resultCitas = $conn->query($queryCitas);
$citasHoy = ($resultCitas->num_rows > 0) ? $resultCitas->fetch_assoc()['cantidad'] : 0;

// Consultar la cantidad de mascotas registradas
$queryMascotas = "SELECT COUNT(*) AS cantidad FROM mascotas";
$resultMascotas = $conn->query($queryMascotas);
$mascotasRegistradas = ($resultMascotas->num_rows > 0) ? $resultMascotas->fetch_assoc()['cantidad'] : 0;

// Consultar la cantidad de usuarios activos (última conexión hace menos de 15 minutos)
$queryUsuariosActivos = "SELECT COUNT(*) AS cantidad FROM usuarios WHERE TIMESTAMPDIFF(MINUTE, ultima_conexion, NOW()) <= 15";
$resultUsuariosActivos = $conn->query($queryUsuariosActivos);
$usuariosActivos = ($resultUsuariosActivos->num_rows > 0) ? $resultUsuariosActivos->fetch_assoc()['cantidad'] : 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Veterinaria Cañete</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Bienvenido, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <div class="relative">
                <button id="user-menu-button" class="bg-gray-500 text-white w-10 h-10 rounded-full flex items-center justify-center">
                    <?php echo strtoupper(substr($user_name, 0, 2)); ?>
                </button>
                <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg">
                    <a href="editar_perfil.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Editar Perfil</a>
                    <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Cerrar Sesión</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-bold text-gray-700 mb-2">Citas Programadas</h2>
                <p class="text-4xl font-bold text-blue-600"><?php echo $citasHoy; ?></p>
                <p class="text-gray-600">Citas hoy</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-bold text-gray-700 mb-2">Mascotas Registradas</h2>
                <p class="text-4xl font-bold text-green-600"><?php echo $mascotasRegistradas; ?></p>
                <p class="text-gray-600">Total de mascotas</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-bold text-gray-700 mb-2">Usuarios Activos</h2>
                <p class="text-4xl font-bold text-orange-600"><?php echo $usuariosActivos; ?></p>
                <p class="text-gray-600">Usuarios conectados</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Sección Gestión de Citas -->
<div class="bg-blue-100 p-6 rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-blue-800 mb-4">Gestión de Citas</h2>
    <a href="agendar_cita.php">
        <button class="bg-green-500 text-white font-semibold py-2 px-4 rounded hover:bg-green-600 mb-3 w-full">Agendar Nueva Cita</button>
    </a>
    <a href="ver_citas.php">
        <button class="bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 w-full">Ver Citas Programadas</button>
    </a>
</div>

<!-- Sección Información de Mascotas -->
<div class="bg-green-100 p-6 rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-green-800 mb-4">Información de Mascotas</h2>
    <a href="registrar_mascota.php">
        <button class="bg-green-500 text-white font-semibold py-2 px-4 rounded hover:bg-green-600 mb-3 w-full">Registrar Nueva Mascota</button>
    </a>
    <a href="ver_mascotas.php">
        <button class="bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 w-full">Ver Información de Mascotas</button>
    </a>
</div>

<!-- Sección Historial Clínico -->
<div class="bg-yellow-100 p-6 rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-orange-800 mb-4">Historial Clínico</h2>
    <a href="ver_historial.php">
        <button class="bg-yellow-500 text-white font-semibold py-2 px-4 rounded hover:bg-yellow-600 mb-3 w-full">Ver Historial Clínico</button>
    </a>
    <a href="actualizar_historial.php">
        <button class="bg-yellow-700 text-white font-semibold py-2 px-4 rounded hover:bg-yellow-800 w-full">Actualizar Historial Clínico</button>
    </a>
</div>

        </div>

        <div class="bg-blue-100 p-6 rounded-lg shadow-md mt-6">
            <h2 class="text-lg font-bold text-blue-800 mb-4">Noticias y Actualizaciones</h2>
            <ul class="list-disc list-inside text-gray-700">
                <li>Actualización del sistema programada para el próximo mes.</li>
                <li>Nuevos servicios para la atención integral de mascotas.</li>
                <li>Horarios extendidos para consultas veterinarias.</li>
            </ul>
        </div>
    </div>

    <script>
        document.getElementById('user-menu-button').addEventListener('click', function () {
            const userMenu = document.getElementById('user-menu');
            userMenu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
