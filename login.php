<?php
session_start();
include("conexion.php");

$mensaje = "";

if (isset($_GET['msg']) && $_GET['msg'] == 'favoritos') {
    $mensaje = "<div class='alert alert-warning'>Para agregar productos a tus favoritos, debes iniciar sesión.</div>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM usuarios WHERE email = '$email'";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            
            if (password_verify($password, $usuario['password'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                header("Location: index.php");
                exit();
            } else {
                $mensaje = "<div class='alert alert-danger'>Contraseña incorrecta.</div>";
            }
        } else {
            $mensaje = "<div class='alert alert-danger'>El correo electrónico no está registrado.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning'>Por favor completa todos los campos.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedalia - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">

    <div class="container" style="max-width: 420px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="fw-bold text-center mb-1">Iniciar Sesión</h3>
                <p class="text-muted text-center small mb-4">Ingresa a tu cuenta de Sedalia</p>
                
                <?php echo $mensaje; ?>

                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 mb-3">Ingresar</button>
                </form>

                <div class="text-center">
                    <a href="registro.php" class="text-decoration-none small">¿No tienes cuenta? Regístrate aquí</a>
                    <br>
                    <a href="index.php" class="text-muted small">Volver al catálogo</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>