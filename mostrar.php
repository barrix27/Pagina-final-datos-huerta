<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "huerta_db";


$conn = new mysqli($servername, $username, $password, $dbname, 3307);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


// ==========================================
// ELIMINAR REGISTRO
// ==========================================
if (isset($_GET['eliminar'])) {
    $id_eliminar = $_GET['eliminar'];


    $stmt_baja = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt_baja->bind_param("i", $id_eliminar);


    if ($stmt_baja->execute()) {
        echo "<script>
                alert('🌱 Registro eliminado correctamente');
                window.location='mostrar.php';
              </script>";
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
    $stmt_baja->close();
}


// ==========================================
// MODIFICAR REGISTRO
// ==========================================
if (isset($_POST['btn_modificar'])) {
    $id_update = $_POST['id'];
    $nombre_update = $_POST['nombre'];
    $email_update = $_POST['email'];
    $password_update = $_POST['password'];


    $stmt_update = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ?, password = ? WHERE id = ?");
    $stmt_update->bind_param("sssi", $nombre_update, $email_update, $password_update, $id_update);


    if ($stmt_update->execute()) {
        echo "<script>
                alert('🌱 Registro actualizado correctamente');
                window.location='mostrar.php';
              </script>";
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
    $stmt_update->close();
}


// ==========================================
// CARGAR DATOS PARA EDITAR
// ==========================================
$row_edit = null;


if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];


    $stmt_buscar = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt_buscar->bind_param("i", $id_editar);
    $stmt_buscar->execute();
    $res_buscar = $stmt_buscar->get_result();


    if ($res_buscar->num_rows > 0) {
        $row_edit = $res_buscar->fetch_assoc();
    }
    $stmt_buscar->close();
}
?>


<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Registrados | ProA</title>


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
            --danger: #d32f2f;
            --danger-light: #ffebee;
            --text-main: #1c2d1f;
            --text-muted: #607363;
            --card-bg: rgba(255, 255, 255, 0.9);
            --border-color: #d2e3d4;
            --radius-lg: 24px;
            --radius-md: 14px;
            --shadow-sm: 0 4px 12px rgba(0,0,0,0.03);
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
                radial-gradient(at 10% 10%, rgba(76, 175, 80, 0.08) 0px, transparent 50%),
                radial-gradient(at 90% 90%, rgba(46, 125, 50, 0.08) 0px, transparent 50%);
            min-height: 100vh;
            padding: 40px 20px;
            color: var(--text-main);
        }


        .container {
            max-width: 1050px;
            margin: 0 auto;
        }


        .page-header {
            margin-bottom: 28px;
        }


        h2 {
            color: var(--primary-dark);
            font-size: 28px;
            font-weight: 800;
        }


        /* FORMULARIO EDITAR */
        .form-edit-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            padding: 32px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            max-width: 480px;
            margin: 0 auto 32px auto;
            position: relative;
            overflow: hidden;
        }


        .form-edit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }


        .form-edit-card h3 {
            color: var(--primary-dark);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }


        .input-group {
            margin-bottom: 18px;
        }


        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
            font-size: 13px;
            color: var(--text-main);
            text-transform: uppercase;
        }


        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            outline: none;
            font-size: 15px;
            background: white;
        }


        .btn-save {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }


        .cancelar {
            display: block;
            text-align: center;
            margin-top: 14px;
            color: var(--text-muted);
            text-decoration: none;
        }


        /* TABLA */
        .table-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }


        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }


        th {
            background: #e1ebe3;
            color: var(--text-main);
            padding: 16px 20px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            text-align: left;
        }


        td {
            padding: 16px 20px;
            font-size: 14px;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-color);
        }


        .censored {
            font-family: monospace;
            letter-spacing: 2px;
            color: var(--text-muted);
            background: rgba(0,0,0,0.05);
            padding: 2px 8px;
            border-radius: 6px;
        }


        .actions-cell {
            display: flex;
            gap: 8px;
        }


        .btn {
            padding: 8px 14px;
            border-radius: var(--radius-md);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
        }


        .btn-edit {
            background: var(--primary-light);
            color: var(--primary-dark);
        }


        .btn-delete {
            background: var(--danger-light);
            color: var(--danger);
        }


        .btn-volver {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            padding: 10px 18px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 24px;
        }
    </style>
</head>


<body>


<div class="container">


    <!-- FORMULARIO EDITAR -->
    <?php if ($row_edit): ?>
        <div class="form-edit-card">
            <h3>✏️ Modificar Usuario (ID: ***<?php echo substr($row_edit['id'], -2); ?>)</h3>


            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $row_edit['id']; ?>">


                <div class="input-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($row_edit['nombre']); ?>" required>
                </div>


                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($row_edit['email']); ?>" required>
                </div>


                <div class="input-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" value="<?php echo htmlspecialchars($row_edit['password']); ?>" required>
                </div>


                <input type="submit" class="btn-save" name="btn_modificar" value="Guardar Cambios">
                <a class="cancelar" href="mostrar.php">Cancelar</a>
            </form>
        </div>
    <?php endif; ?>


    <div class="page-header">
        <h2>📋 Usuarios Registrados</h2>
    </div>


    <div class="table-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID (Censurado)</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Contraseña</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, nombre, email, password FROM usuarios";
                    $resultado = $conn->query($sql);


                    if ($resultado && $resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_assoc()) {
                            // Función para censurar ID (ejemplo: '101' -> '***01')
                            $id_string = (string)$fila['id'];
                            $id_censurado = strlen($id_string) > 2 ? '***' . substr($id_string, -2) : '***';


                            echo "<tr>";
                            echo "<td><span class='censored'>" . $id_censurado . "</span></td>";
                            echo "<td><strong>" . htmlspecialchars($fila['nombre']) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($fila['email']) . "</td>";
                            echo "<td><span class='censored'>••••••••</span></td>";
                            echo "<td class='actions-cell'>
                                    <a class='btn btn-edit' href='?editar=" . $fila['id'] . "'>✏️ Modificar</a>
                                    <a class='btn btn-delete' href='?eliminar=" . $fila['id'] . "' onclick='return confirm(\"¿Seguro que deseas eliminar este usuario?\")'>🗑️ Eliminar</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr>
                                <td colspan='5' style='text-align: center; color: var(--text-muted); padding: 24px;'>
                                    No hay registros guardados actualmente.
                                </td>
                              </tr>";
                    }


                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>


    <button class="btn-volver" onclick="history.back()">
        ← Volver Atrás
    </button>


</div>


</body>
</html>
