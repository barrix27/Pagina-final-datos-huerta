<?php


if (isset($_POST['btn_alta'])) {


    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "huerta_db";


    $conn = new mysqli($servername, $username, $password, $dbname, 3307);


    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }


    // DATOS DEL FORMULARIO
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];


    // INSERTAR DATOS USANDO PREPARED STATEMENTS
    $stmt = $conn->prepare("INSERT INTO usuarios (id, nombre, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $id, $nombre, $email, $contrasena);


    if ($stmt->execute()) {
        echo "
        <script>
            alert('🌱 Usuario registrado correctamente');
            window.location='mostrar.php';
        </script>";
    } else {
        echo "<script>alert('Error al registrar: " . addslashes($conn->error) . "');</script>";
    }


    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Usuario | ProA</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <style>
        :root {
            --bg-main: #f0f4f1;
            --primary: #2e7d32;
            --primary-dark: #1b5e20;
            --primary-light: #e8f5e9;
            --accent: #4caf50;
            --text-main: #1c2d1f;
            --text-muted: #607363;
            --card-bg: rgba(255, 255, 255, 0.9);
            --border-color: #d2e3d4;
            --radius-lg: 24px;
            --radius-md: 14px;
            --shadow-md: 0 12px 32px rgba(46, 125, 50, 0.1);
        }


        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }


        body {
            background-color: var(--bg-main);
            background-image: 
                radial-gradient(at 20% 20%, rgba(76, 175, 80, 0.08) 0px, transparent 50%),
                radial-gradient(at 80% 80%, rgba(46, 125, 50, 0.08) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 24px;
            color: var(--text-main);
        }


        .form-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            padding: 40px 36px;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 440px;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }


        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }


        .form-header {
            text-align: center;
            margin-bottom: 28px;
        }


        .form-header h2 {
            color: var(--primary-dark);
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 6px;
        }


        .form-header p {
            color: var(--text-muted);
            font-size: 14px;
        }


        .input-group {
            margin-bottom: 18px;
        }


        label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-main);
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }


        input[type="number"],
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            outline: none;
            transition: all 0.25s ease;
            font-size: 15px;
            background: white;
            color: var(--text-main);
        }


        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.15);
        }


        .btn-submit {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 16px;
            border-radius: var(--radius-md);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
            margin-top: 10px;
        }


        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }


        .btn-volver {
            margin-top: 20px;
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }


        .btn-volver:hover {
            background: white;
            color: var(--primary-dark);
        }
    </style>
</head>


<body>


    <div class="form-card">
        <div class="form-header">
            <h2>🌱 Alta de Usuario</h2>
            <p>Ingrese los datos para la nueva cuenta</p>
        </div>


        <form action="" method="POST">
            <div class="input-group">
                <label for="id">ID de Usuario</label>
                <input id="id" type="number" name="id" placeholder="Ej: 101" required>
            </div>


            <div class="input-group">
                <label for="nombre">Nombre Completo</label>
                <input id="nombre" type="text" name="nombre" placeholder="Ej: Juan Pérez" required>
            </div>


            <div class="input-group">
                <label for="email">Correo Electrónico</label>
                <input id="email" type="email" name="email" placeholder="ejemplo@correo.com" required>
            </div>


            <div class="input-group">
                <label for="contrasena">Contraseña</label>
                <input id="contrasena" type="password" name="contrasena" placeholder="••••••••" required>
            </div>


            <input type="submit" class="btn-submit" name="btn_alta" value="Registrar Usuario">
        </form>
    </div>


    <button class="btn-volver" onclick="history.back()">
        ← Volver Atrás
    </button>


</body>
</html>
