<?php
require 'config.php'; // Conexión con la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $telefono = trim($_POST['telefono']);

    // Verificar si el correo electrónico ya está registrado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Este correo electrónico ya está registrado. Por favor, inicia sesión.";
    } else {
        // Insertar nuevo usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, telefono) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $email, $password, $telefono);

        if ($stmt->execute()) {
            header("Location: login.php?success=registered");
            exit();
        } else {
            $error = "Error al registrar el usuario. Inténtalo nuevamente.";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="max-w-lg bg-white p-6 sm:p-10 rounded-lg shadow-lg w-full mx-4">
        <h2 class="text-2xl font-bold text-center mb-6">Regístrate</h2>
        <?php if (isset($error)): ?>
            <div class="bg-red-200 text-red-800 p-4 rounded mb-4">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form action="register.php" method="POST" class="space-y-4">
            <div>
                <label for="nombre" class="block font-bold mb-1">Nombre Completo:</label>
                <input type="text" name="nombre" id="nombre" class="w-full p-3 border rounded focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>
            <div>
                <label for="email" class="block font-bold mb-1">Correo Electrónico:</label>
                <input type="email" name="email" id="email" class="w-full p-3 border rounded focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>
            <div>
                <label for="password" class="block font-bold mb-1">Contraseña:</label>
                <input type="password" name="password" id="password" class="w-full p-3 border rounded focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>
            <div>
                <label for="telefono" class="block font-bold mb-1">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" class="w-full p-3 border rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-green-500 text-white py-3 rounded hover:bg-green-600 focus:ring-2 focus:ring-green-500 focus:outline-none">
                Registrarse
            </button>
            <p class="mt-4 text-center text-gray-600">
                ¿Ya tienes una cuenta? <a href="login.php" class="text-blue-600 hover:underline">Inicia Sesión aquí</a>
            </p>
        </form>
    </div>
</div>

</body>
</html>
