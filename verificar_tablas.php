<?php
require_once 'config/database.php';

$conn = conectarDB();

echo "<h2>Estructura de la tabla productos:</h2>";

$result = $conn->query("DESCRIBE productos");

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Key</th><th>Default</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . $row['Default'] . "</td>";
    echo "</tr>";
}
echo "</table>";

$conn->close();
?>