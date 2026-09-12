[11:32 a. m., 11/9/2026] Ximena V.: <?php
include("conexion.php");

$secciones = ['Shampoo', 'Acondicionador', 'Crema para peinar', 'Gel'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedalia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <!-- BARRA DE NAVEGACIÓN -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🌀 Sedalia</a>
        <div class="ms-auto d-f…
[12:06 p. m., 11/9/2026] Ximena V.: <?php
include("conexion.php");

$mensaje = "";

// Verificar si el usuario envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = $_POST['nombre'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encriptamos la contraseña por seguridad

    // Guardar en la base de datos sedalia1_db
    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password')";

    if ($conexion->query($sql) === TRUE) {
        $mensaje = "<div class='alert alert-success'>¡Cuenta creada con éxito! <a href='login.php'>Inicia sesión aquí</a></div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error: El correo ya está registrado.</div>";
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
<body class="bg-light">

    <div class="container mt-5" style="max-width: 450px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="fw-bold text-center mb-3">Crear Cuenta en Sedalia</h3>
                
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
                    <a href="login.php" class="text-decoration-none">¿Ya tienes cuenta? Inicia sesión</a>
                    <br>
                    <a href="index.php" class="text-muted small">Volver al catálogo</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>