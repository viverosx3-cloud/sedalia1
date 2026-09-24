<?php
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = trim($_POST['nombre']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($nombre) && !empty($email) && !empty($password)) {
        // Encriptar la contraseña
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Verificar si el correo ya existe
        $checkEmail = "SELECT id FROM usuarios WHERE email = '$email'";
        $resCheck = $conexion->query($checkEmail);

        if ($resCheck->num_rows > 0) {
            $mensaje = "<div class='alert alert-danger'>El correo electrónico ya está registrado.</div>";
        } else {
            // Guardar usuario en la base de datos
            $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$passwordHash')";

            if ($conexion->query($sql) === TRUE) {
                $mensaje = "<div class='alert alert-success'>¡Cuenta creada con éxito! <a href='login.php' class='fw-bold'>Inicia sesión aquí</a></div>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Ocurrió un error al registrar la cuenta.</div>";
            }
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
    <title>Sedalia - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">

    <div class="container" style="max-width: 420px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="fw-bold text-center mb-1">Crear Cuenta</h3>
                <p class="text-muted text-center small mb-4">Únete a Sedalia para guardar tus productos</p>
                
                <?php echo $mensaje; ?>

                <form method="POST" action="registro.php">
                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 mb-3">Registrarme</button>
                </form>

                <div class="text-center">
                    <a href="login.php" class="text-decoration-none small">¿Ya tienes cuenta? Inicia sesión</a>
                    <br>
                    <a href="index.php" class="text-muted small">Volver al catálogo</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>