<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $conexion = new mysqli('localhost', 'root', '', 'fundacion');
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        if (password_verify($contraseña, $fila['contraseña'])) {
                header("Location: adopta.php");
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location.href='/proyecto/html/login.html';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location.href='/proyecto/html/login.html';</script>";
    }
    $conexion->close();
}
?>