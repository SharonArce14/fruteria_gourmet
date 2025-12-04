<?php
session_start();

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto al carrito
if (isset($_POST['agregar_carrito'])) {
    $producto_id = intval($_POST['producto_id']);
    $nombre = $_POST['nombre'];
    $precio = floatval($_POST['precio']);
    
    // Si el producto ya está en el carrito, aumentar cantidad
    if (isset($_SESSION['carrito'][$producto_id])) {
        $_SESSION['carrito'][$producto_id]['cantidad']++;
    } else {
        $_SESSION['carrito'][$producto_id] = [
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => 1
        ];
    }
}

// Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $producto_id = intval($_GET['eliminar']);
    unset($_SESSION['carrito'][$producto_id]);
}

// Vaciar carrito
if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
}

require_once 'config/database.php';

$conn = conectarDB();

// Obtener todos los productos disponibles
$query = "SELECT p.*, t.nombre as tipo_nombre 
          FROM productos p 
          INNER JOIN tipos_productos t ON p.tipo_producto_id = t.id 
          WHERE p.disponible = 1
          ORDER BY t.nombre, p.nombre";
$productos = $conn->query($query);

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruteria Gourmet - Catalogo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
        }

        /* Header */
        .hero-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
        }

        .hero-header h1 {
            font-size: 48px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .hero-header p {
            font-size: 20px;
            opacity: 0.9;
        }

        .header-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }

        .header-btn {
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border: 2px solid transparent;
            position: relative;
        }

        .header-btn:hover {
            background: rgba(255,255,255,0.3);
            border-color: white;
        }

        .cart-btn {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Carrito flotante */
        .cart-sidebar {
            position: fixed;
            right: -400px;
            top: 0;
            width: 400px;
            height: 100%;
            background: white;
            box-shadow: -5px 0 15px rgba(0,0,0,0.2);
            transition: right 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }

        .cart-sidebar.active {
            right: 0;
        }

        .cart-header {
            background: #667eea;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .close-cart {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .cart-content {
            padding: 20px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
            gap: 10px;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: #667eea;
            font-size: 14px;
        }

        .cart-item-quantity {
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        .remove-item {
            background: #ff4757;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .cart-total {
            padding: 20px;
            background: #f9f9f9;
            margin-top: 20px;
            border-radius: 10px;
        }

        .cart-total-label {
            font-size: 18px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .cart-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .cart-btn-action {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .btn-checkout {
            background: #4CAF50;
            color: white;
        }

        .btn-clear {
            background: #ff4757;
            color: white;
        }

        .empty-cart {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        /* Categorias */
        .category-section {
            margin-bottom: 50px;
        }

        .category-title {
            font-size: 32px;
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
        }

        /* Grid de productos */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        /* Tarjeta de producto */
        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .product-image {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
        }

        .product-info {
            padding: 20px;
        }

        .product-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .product-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
            min-height: 60px;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .product-price {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        .btn-add-cart {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-add-cart:hover {
            background: #45a049;
            transform: scale(1.05);
        }

        /* Footer */
        .main-footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 30px 20px;
            margin-top: 50px;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            z-index: 999;
        }

        .overlay.active {
            display: block;
        }

        @media (max-width: 768px) {
            .cart-sidebar {
                width: 100%;
                right: -100%;
            }
            
            .header-buttons {
                position: relative;
                display: flex;
                justify-content: center;
                margin-top: 20px;
                flex-wrap: wrap;
            }

            .hero-header h1 {
                font-size: 32px;
            }

            .hero-header p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeCart()"></div>

    <!-- Carrito lateral -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h2>🛒 Mi Carrito</h2>
            <button class="close-cart" onclick="closeCart()">×</button>
        </div>
        
        <div class="cart-content">
            <?php if (empty($_SESSION['carrito'])): ?>
                <div class="empty-cart">
                    <h3>Tu carrito está vacío</h3>
                    <p>Agrega productos para continuar</p>
                </div>
            <?php else: ?>
                <?php 
                $total = 0;
                foreach ($_SESSION['carrito'] as $id => $item): 
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total += $subtotal;
                ?>
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name"><?php echo htmlspecialchars($item['nombre']); ?></div>
                            <div class="cart-item-price">
                                $<?php echo number_format($item['precio'], 0); ?> x <?php echo $item['cantidad']; ?> = 
                                $<?php echo number_format($subtotal, 0); ?>
                            </div>
                        </div>
                        <span class="cart-item-quantity">x<?php echo $item['cantidad']; ?></span>
                        <a href="?eliminar=<?php echo $id; ?>" class="remove-item" onclick="return confirm('¿Eliminar este producto?')">✕</a>
                    </div>
                <?php endforeach; ?>
                
                <div class="cart-total">
                    <div class="cart-total-label">
                        <span>Total:</span>
                        <span>$<?php echo number_format($total, 0); ?></span>
                    </div>
                    
                    <div class="cart-actions">
                        <button class="cart-btn-action btn-checkout" onclick="finalizarCompra()">Finalizar Compra</button>
                        <a href="?vaciar=1" class="cart-btn-action btn-clear" onclick="return confirm('¿Vaciar el carrito?')">Vaciar</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Header -->
    <div class="hero-header">
        <div class="header-buttons">
            <a href="registro_cliente.php" class="header-btn">📝 Registrarse</a>
            <a href="#" onclick="openCart(); return false;" class="header-btn cart-btn">
                🛒 Carrito
                <?php if (count($_SESSION['carrito']) > 0): ?>
                    <span class="cart-count"><?php echo array_sum(array_column($_SESSION['carrito'], 'cantidad')); ?></span>
                <?php endif; ?>
            </a>
            <a href="login.php" class="header-btn">🔐 Admin</a>
        </div>
        <h1>🍓 Frutería Gourmet</h1>
        <p>Productos frescos y deliciosos para ti</p>
    </div>

    <div class="container">
        <?php
        // Agrupar productos por tipo
        $productos_por_tipo = [];
        $productos->data_seek(0);
        
        while ($producto = $productos->fetch_assoc()) {
            $tipo = $producto['tipo_nombre'];
            if (!isset($productos_por_tipo[$tipo])) {
                $productos_por_tipo[$tipo] = [];
            }
            $productos_por_tipo[$tipo][] = $producto;
        }

        // Iconos por producto
        $iconos_productos = [
            'Parfait de Fresas' => '🍓',
            'Tarta de Mango' => '🥭',
            'Waffles con Frutas' => '🧇',
            'Ensalada de Frutas Tropical' => '🍍',
            'Mousse de Maracuya' => '🍊',
            'Smoothie Bowl' => '🥥',
            'Batido Verde Detox' => '🥬',
            'Jugo Natural Naranja' => '🍊',
            'Limonada de Coco' => '🥥',
            'Frappe de Frutas' => '🍹'
        ];

        // Mostrar productos por categoría
        if (count($productos_por_tipo) > 0) {
            foreach ($productos_por_tipo as $tipo => $prods) {
                echo '<div class="category-section">';
                echo '<h2 class="category-title">' . htmlspecialchars($tipo) . '</h2>';
                echo '<div class="products-grid">';
                
                foreach ($prods as $producto) {
                    $icono = isset($iconos_productos[$producto['nombre']]) 
                             ? $iconos_productos[$producto['nombre']] 
                             : '🍎';
                    
                    echo '<div class="product-card">';
                    echo '  <div class="product-image">';
                    echo '    <span>' . $icono . '</span>';
                    echo '  </div>';
                    echo '  <div class="product-info">';
                    echo '    <div class="product-name">' . htmlspecialchars($producto['nombre']) . '</div>';
                    echo '    <div class="product-description">' . htmlspecialchars($producto['descripcion']) . '</div>';
                    echo '    <div class="product-footer">';
                    echo '      <div class="product-price">$' . number_format($producto['precio'], 0) . '</div>';
                    echo '      <form method="POST" style="margin: 0;">';
                    echo '        <input type="hidden" name="producto_id" value="' . $producto['id'] . '">';
                    echo '        <input type="hidden" name="nombre" value="' . htmlspecialchars($producto['nombre']) . '">';
                    echo '        <input type="hidden" name="precio" value="' . $producto['precio'] . '">';
                    echo '        <button type="submit" name="agregar_carrito" class="btn-add-cart">+ Agregar</button>';
                    echo '      </form>';
                    echo '    </div>';
                    echo '  </div>';
                    echo '</div>';
                }
                
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="empty-cart">';
            echo '<h2>No hay productos disponibles</h2>';
            echo '</div>';
        }
        ?>
    </div>

    <div class="main-footer">
        <p>&copy; 2025 Frutería Gourmet - Todos los derechos reservados</p>
        <p>Productos frescos y naturales</p>
    </div>

    <script>
        function openCart() {
            document.getElementById('cartSidebar').classList.add('active');
            document.getElementById('overlay').classList.add('active');
        }

        function closeCart() {
            document.getElementById('cartSidebar').classList.remove('active');
            document.getElementById('overlay').classList.remove('active');
        }

        function finalizarCompra() {
            alert('¡Gracias por tu compra! En breve nos pondremos en contacto contigo.\n\nEsta es una demostración del sistema.');
            window.location.href = '?vaciar=1';
        }

        // Mostrar carrito al agregar producto
        <?php if (isset($_POST['agregar_carrito'])): ?>
            openCart();
        <?php endif; ?>
    </script>
</body>
</html>