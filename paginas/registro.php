<?php
$usuario_existente = false;
if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $contraseña = isset($_POST['contraseña']) ? password_hash($_POST['contraseña'], PASSWORD_BCRYPT) : '';
    $celular = $_POST['celular'] ?? '';
    $edad = $_POST['edad'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $conexion = new mysqli('localhost', 'root', '', 'fundacion');
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
    $sql = "SELECT * FROM usuarios WHERE correo = '$correo' OR usuario = '$usuario'";
    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        $usuario_existente = true;
    } else {
        $sql = "INSERT INTO usuarios (nombre, correo, usuario, contraseña, celular, edad, sexo) 
                VALUES ('$nombre', '$correo', '$usuario', '$contraseña', '$celular', '$edad', '$sexo')";
        if ($conexion->query($sql) === TRUE) {
            $registro_exitoso = true;
        } else {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
    }
    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="/proyecto/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Registro</h2>
        <form action="registro.php" method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="contraseña" placeholder="Contraseña" required>
            <input type="text" name="celular" placeholder="Celular" required>
            <input type="number" name="edad" placeholder="Edad" required min="1">
            <p>
            <label for="sexo">Sexo:</label>
            <select name="sexo" required>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
            </select>
            <select name="rol" required>
                <option value="2">Usuario</option>
                <option value="3">Voluntario</option>
                <option value="1">Administrador</option>
            </select>
            <p>
            <button type="submit" name="registrar">Registrar</button>
            <p><a href="/proyecto/html/login.html">Inicia sesión</a></p>
        </form>
        <?php if ($usuario_existente): ?>
            <p>El correo o el usuario ya existe. Por favor, usa otro.</p>
        <?php endif; ?>
        <?php if (isset($registro_exitoso) && $registro_exitoso): ?>
            <p>Registro exitoso</p>
        <?php endif; ?>
    </div>
</body>
</html>