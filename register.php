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
    <div class="container mx-auto mt-10 w-1/2 bg-white p-10 rounded shadow-lg">
        <h2 class="text-2xl font-bold text-center">Regístrate</h2>
        <?php if (isset($error)): ?>
            <div class="bg-red-200 text-red-800 p-4 rounded mt-4">
                <?= $error; ?>
            </div>
        <?php endif; ?>
        <form action="register.php" method="POST" class="mt-5">
            <div class="mb-4">
                <label for="nombre" class="block font-bold">Nombre Completo:</label>
                <input type="text" name="nombre" id="nombre" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block font-bold">Correo Electrónico:</label>
                <input type="email" name="email" id="email" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block font-bold">Contraseña:</label>
                <input type="password" name="password" id="password" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="telefono" class="block font-bold">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" class="w-full p-2 border rounded">
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">Registrarse</button>
            <p class="mt-4 text-center">¿Ya tienes una cuenta? <a href="login.php" class="text-blue-600 hover:underline">Inicia Sesión aquí</a></p>
        </form>
    </div>
</body>
</html>
