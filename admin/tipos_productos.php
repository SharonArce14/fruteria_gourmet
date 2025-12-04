<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/database.php';

$conn = conectarDB();

// Manejar creacion de nuevo tipo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    
    if ($nombre) {
        $sql = "INSERT INTO tipos_productos (nombre, descripcion) VALUES ('$nombre', '$descripcion')";
        $conn->query($sql);
    }
}

// Manejar eliminacion
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    if ($id) {
        $sql = "DELETE FROM tipos_productos WHERE id = $id";
        $conn->query($sql);
    }
}

$query = "SELECT * FROM tipos_productos ORDER BY nombre";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de Productos - Fruteria Gourmet</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Tipos de Productos</h1>
        
        <div class="form-section">
            <h2>Crear Nuevo Tipo</h2>
            <form method="POST" action="" class="form-inline">
                <div class="form-group">
                    <input type="text" name="nombre" placeholder="Nombre del tipo" required>
                </div>
                <div class="form-group">
                    <input type="text" name="descripcion" placeholder="Descripcion">
                </div>
                <button type="submit" name="crear" class="btn btn-primary">Crear</button>
            </form>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($tipo = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $tipo['id']; ?></td>
                    <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($tipo['descripcion']); ?></td>
                    <td class="actions">
                        <a href="?eliminar=<?php echo $tipo['id']; ?>" 
                           class="btn btn-small btn-delete" 
                           onclick="return confirm('¿Esta seguro de eliminar este tipo?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <?php 
    include '../includes/footer.php';
    $conn->close();
    ?>
</body>
</html>