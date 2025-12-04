<?php
require_once 'config/database.php';

$conn = conectarDB();

echo "<h2>Verificando usuarios en la base de datos:</h2>";

$sql = "SELECT id, username, nombre FROM usuarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<p>Usuarios encontrados: " . $result->num_rows . "</p>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Nombre</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>No hay usuarios en la base de datos</p>";
}

$conn->close();
?>