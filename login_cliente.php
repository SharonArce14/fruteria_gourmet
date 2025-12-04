<?php
session_start();
require_once 'config/database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conectarDB();
    
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM clientes WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $cliente = $result->fetch_assoc();
        if (password_verify($password, $cliente['password'])) {
            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['cliente_nombre'] = $cliente['nombre'];
            $_SESSION['cliente_email'] = $cliente['email'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Correo o contraseña incorrectos";
        }
    } else {
        $error = "Correo o contraseña incorrectos";
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Cliente - Fruteria Gourmet</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .login-box h1 {
            text-align: center;
            color: #667eea;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .login-box h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 20px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #fee;
            color: #c33;
            border: 2px solid #fcc;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn:hover {
            background-color: #5568d3;
            transform: translateY(-2px);
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin: 0 10px;
        }

        .links a:hover {
            color: #5568d3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>🍓 Frutería Gourmet</h1>
            <h2>Iniciar Sesión</h2>
            
            <?php if ($error): ?>
                <div class="alert"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Ingresar</button>
            </form>
            
            <div class="links">
                <a href="registro_cliente.php">Registrarse</a> | 
                <a href="index.php">Volver al catálogo</a>
            </div>
        </div>
    </div>
</body>
</html>