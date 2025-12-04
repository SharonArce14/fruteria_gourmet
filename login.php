<?php
session_start();
require_once 'config/database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conectarDB();
    
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT id, username, password, nombre FROM usuarios WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nombre'] = $user['nombre'];
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $error = "Usuario o contrasena incorrectos";
        }
    } else {
        $error = "Usuario o contrasena incorrectos";
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Fruteria Gourmet</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container { width: 100%; max-width: 400px; padding: 20px; }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .login-box h1 {
            text-align: center;
            color: #667eea;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .login-box h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 20px;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }
        .btn:hover { background-color: #5568d3; }
        .help-text {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>🍓 Fruteria Gourmet</h1>
            <h2>Iniciar Sesion</h2>
            
            <?php if ($error): ?>
                <div class="alert"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contrasena</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Ingresar</button>
            </form>
            
            <p class="help-text">Usuario: admin | Contrasena: admin123</p>
        </div>
    </div>
</body>
</html>