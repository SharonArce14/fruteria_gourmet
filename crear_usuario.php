<?php
require_once 'config/database.php';

$conn = conectarDB();

// Primero, eliminar el usuario admin si existe
$sql_delete = "DELETE FROM usuarios WHERE username = 'admin'";
$conn->query($sql_delete);

// Crear la contraseña encriptada
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insertar el nuevo usuario
$sql = "INSERT INTO usuarios (username, password, nombre) VALUES ('admin', '$password_hash', 'Administrador')";

if ($conn->query($sql)) {
    echo "<h2>✓ Usuario creado exitosamente</h2>";
    echo "<p>Usuario: admin</p>";
    echo "<p>Contraseña: (la que ingresaste)</p>";
    echo "<p><a href='login.php'>Ir al login</a></p>";
} else {
    echo "<h2>✗ Error al crear usuario</h2>";
    echo "<p>Error: " . $conn->error . "</p>";
}

$conn->close();
?>
