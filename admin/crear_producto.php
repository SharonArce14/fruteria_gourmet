<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/database.php';

$conn = conectarDB();

$mensaje = "";
$error = "";

// Obtener tipos de productos
$query_tipos = "SELECT * FROM tipos_productos ORDER BY nombre";
$tipos_productos = $conn->query($query_tipos);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $tipo_producto_id = intval($_POST['tipo_producto_id']);
    $disponible = isset($_POST['disponible']) ? 1 : 0;
    
    if ($nombre && $precio && $tipo_producto_id) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, tipo_producto_id, disponible) 
                VALUES ('$nombre', '$descripcion', $precio, $tipo_producto_id, $disponible)";
        
        if ($conn->query($sql)) {
            header("Location: productos.php");
            exit();
        } else {
            $error = "Error al crear el producto: " . $conn->error;
        }
    } else {
        $error = "Por favor complete todos los campos requeridos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto - Fruteria Gourmet</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include_once '../includes/header.php'; ?>    
    <div class="container">
        <h1>Crear Nuevo Producto</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="form-product">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripcion</label>
                <textarea id="descripcion" name="descripcion" rows="4"></textarea>
            </div>
            
            <div class="form-group">
                <label for="precio">Precio *</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="tipo_producto_id">Tipo de Producto *</label>
                <select id="tipo_producto_id" name="tipo_producto_id" required>
                    <option value="">Seleccione un tipo</option>
                    <?php 
                    $tipos_productos->data_seek(0);
                    while ($tipo = $tipos_productos->fetch_assoc()): 
                    ?>
                        <option value="<?php echo $tipo['id']; ?>">
                            <?php echo htmlspecialchars($tipo['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group checkbox-group">
                <label>
                    <input type="checkbox" name="disponible" checked>
                    Producto disponible
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Crear Producto</button>
                <a href="productos.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
    
    <?php 
   <?php include_once '../includes/footer.php'; ?>
    $conn->close();
    ?>
</body>
</html>
