<?php
session_start();
include("conexion.php");

$mensaje = "";

// Si el usuario viene de hacer clic en "Agregar a Favoritos" sin sesión
if (isset($_GET['msg']) && $_GET['msg'] == 'favoritos') {
    $mensaje = "<div class='alert alert-warning'>Para agregar productos a tus favoritos, debes iniciar sesión.</div>";
}

// Validar cuando se presiona el botón "Ingresar"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        // Verificar si la contraseña ingresada coincide con la guardada
        if (password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            header("Location: index.php"); // Redirige al catálogo con la sesión iniciada
            exit();
        } else {
            $mensaje = "<div class='alert alert-danger'>Contraseña incorrecta.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-danger'>El correo no está registrado.</div>";
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
<body class="bg-light">

    <div class="container mt-5" style="max-width: 450px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="fw-bold text-center mb-3">Iniciar Sesión</h3>
                
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
                    <a href="registro.php" class="text-decoration-none">¿No tienes cuenta? Regístrate aquí</a>
                    <br>
                    <a href="index.php" class="text-muted small">Volver al catálogo</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>