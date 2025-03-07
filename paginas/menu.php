<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "fundacion";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

// Verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verificar si los campos están llenos
    if (
        empty($_POST['nombre']) || empty($_POST['edad']) || empty($_POST['fecha']) ||
        empty($_POST['condi']) || empty($_POST['descripcion']) || empty($_POST['estado'])
    ) {
        echo "Error: Todos los campos son obligatorios.";
    } else {
        // Recoger los datos
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $edad = (int) $_POST['edad'];  // Asegurar que es un número
        $fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
        $condiciones = mysqli_real_escape_string($conexion, $_POST['condi']);
        $descrip = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);


        // Consulta SQL corregida
        $insert = "INSERT INTO rescatados (nombre, edad, descripcion, fecha, condiciones_e, estado) 
                   VALUES ('$nombre', $edad, '$descrip', '$fecha', '$condiciones', '$estado')";

        // Ejecutar consulta
        if (mysqli_query($conexion, $insert)) {
            header("Location: menu.php");
            exit();
        } else {
            echo "Error al insertar: " . mysqli_error($conexion);
        }
    }
}
// Cerrar conexión
mysqli_close($conexion);
?>




































<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
    <title>Menú</title>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.dashboard').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }

        function showForm() {
            document.getElementById('adoptantes').classList.remove('active');
            document.getElementById('formularioAdoptantes').classList.add('active');
        }

        function showForm2() {
            document.getElementById('rescatados').classList.remove('active');
            document.getElementById('formularioRescatados').classList.add('active');
        }
    </script>
</head>

<body>
    <div class="sidebar">
        <img src="../img/logo.jpeg" alt="" class="logo">
        <ul>
            <li onclick="showSection('historiaClinica')">Historia Clínica</li>
            <li onclick="showSection('adoptantes')">Adoptantes</li>
            <li onclick="showSection('rescatados')">Rescatados</li>
        </ul>
    </div>
    <div class="content">


        <!-- MODULO HISTORIA CLINICA -->
        <div id="historiaClinica" class="dashboard active">
            holaaa
        </div>
        <!-- ---------------------------------------------- -->



        <!-- MODULO ADOPTANTES -->
        <div id="adoptantes" class="dashboard">
            <h1>Personas adoptantes</h1>
            <button onclick="showForm()" class="boton">Ingresa adoptantes</button>
        </div>
        <div id="formularioAdoptantes" class="dashboard">
            <center>
                <h3>Formulario de Historia Clínica</h3>
                <form>
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre"><br><br>
                    <label for="edad">Edad:</label>
                    <input type="number" id="edad" name="edad"><br><br>
                    <label for="descripcion">Descripción:</label><br>
                    <textarea id="descripcion" name="descripcion"></textarea><br><br>
                    <button type="submit">Guardar</button>
                </form>
            </center>
        </div>
        <!-- -------------------------------------------------------------------------------- -->



        <!-- MODULO RESCATADOS -->
        <div id="rescatados" class="dashboard">
            <h1>Animalitos Rescatados</h1>
            <button onclick="showForm2()" class="boton">Ingresa rescatados</button>
            <?php
            // Conectar a la base de datos
            $conexion = mysqli_connect("localhost", "root", "", "fundacion");

            // Verificar la conexión
            if (!$conexion) {
                die("Error en la conexión: " . mysqli_connect_error());
            }

            // Consulta para obtener los datos de la tabla "rescatados"
            $sql = "SELECT nombre, edad, fecha, condiciones_e, descripcion, estado FROM rescatados";
            $resultado = mysqli_query($conexion, $sql);

            // Mostrar los datos en una tabla justo debajo del botón
            echo "<div id='tablaRescatados' style='margin-top: 20px;'>";

            if (mysqli_num_rows($resultado) > 0) {
                echo "<table border='1' style='width:100%; text-align:center; border-collapse: collapse;'>";
                echo "<tr style='background-color:#f2f2f2;'>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Fecha de Ingreso</th>
            <th>Condiciones Especiales</th>
            <th>Descripción</th>
            <th>Listo para Adopción</th>
          </tr>";

                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>
                <td>" . $fila["nombre"] . "</td>
                <td>" . $fila["edad"] . "</td>
                <td>" . $fila["fecha"] . "</td>
                <td>" . $fila["condiciones_e"] . "</td>
                <td>" . $fila["descripcion"] . "</td>
                <td>" . $fila["estado"] . "</td>
                  <td>
                <button class='editar' onclick='editarFila(this)'>Editar</button>
                <button class='eliminar' onclick='eliminarFila(this)'>Eliminar</button>
            </td>
              </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No hay registros aún.</p>";
            }

            echo "</div>";

            // Cerrar conexión
            mysqli_close($conexion);
            ?>

        </div>
        <div id="formularioRescatados" class="dashboard">
            <center>
                <h3>Ingresa Rescatados</h3>
                <form method="POST" action="menu.php">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre"><br><br>
                    <label for="edad">Edad:</label>
                    <input type="number" id="edad" name="edad"><br><br>
                    <label for="edad">Fecha ingreso:</label>
                    <input type="date" id="edad" name="fecha"><br><br>
                    <label for="edad">Condiciones especiales</label>
                    <select name="condi" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                    <br><br><br>
                    <label for="descripcion">Descripción:</label><br>
                    <textarea id="descripcion" name="descripcion"></textarea><br><br>
                    <label for="edad">¿Listo para adopción?</label>
                    <select name="estado" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                    <br> <br><br><br>
                    <button type="submit">Guardar</button>
                </form>

            </center>
        </div>
        <!-- -------------------------------------------------------------------------------------------- -->


    </div>
</body>

</html>