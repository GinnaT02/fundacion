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
//mysqli_close($conexion);
?>




































<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
    <link rel="stylesheet" href="../css/informes.css">

    <title>Menú</title>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.dashboard').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }

        function showInforme(sectionId) {
            document.querySelectorAll('.informe').forEach(section => {
                section.classList.remove('activeInforme');
            });
            document.getElementById(sectionId).classList.add('activeInforme');
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
            <li onclick="showSection('informes')">Informes</li>
        </ul>
    </div>
    <div class="content">


        <!-- MODULO HISTORIA CLINICA -->
        <!-- MODULO HISTORIA CLINICA -->
<div id="historiaClinica" class="dashboard">
    <h1>Historia Clínica</h1>
    <button onclick="showFormHistoria()" class="boton">Agregar historia clínica</button>

    <!-- Tabla de historias clínicas -->
    <div id="tablaHistoriaClinica" style="margin-top: 20px;">
        <?php
        $sql_historia = "SELECT hc.id_historia_clinica, hc.fecha_chequeo, hc.peso, hc.tratamiento, hc.observaciones, hc.cuidados, r.nombre AS animal_nombre 
                         FROM historia_clinica hc
                         JOIN rescatados r ON hc.id_animal = r.id_animal";

        $resultado_historia = $conexion->query($sql_historia);

        if ($resultado_historia && $resultado_historia->num_rows > 0) {
            echo "<table border='1' style='width:100%; text-align:center; border-collapse: collapse;'>";
            echo "<tr style='background-color:#f2f2f2;'>
                    <th>Fecha Chequeo</th>
                    <th>Peso</th>
                    <th>Tratamiento</th>
                    <th>Observaciones</th>
                    <th>Cuidados</th>
                    <th>Animal</th>
                    <th>Acciones</th>
                  </tr>";

            while ($fila = $resultado_historia->fetch_assoc()) {
                echo "<tr>
                        <td>{$fila['fecha_chequeo']}</td>
                        <td>{$fila['peso']}</td>
                        <td>{$fila['tratamiento']}</td>
                        <td>{$fila['observaciones']}</td>
                        <td>{$fila['cuidados']}</td>
                        <td>{$fila['animal_nombre']}</td>
                        <td>
                            <button class='editar' onclick='editarHistoria(this)'>Editar</button>
                            <button class='eliminar' onclick='eliminarHistoria(this, {$fila['id_historia_clinica']})'>Eliminar</button>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay registros aún en historia clínica.</p>";
        }
        ?>
    </div>

    <!-- Formulario oculto para agregar nueva historia clínica -->
    <div id="formularioHistoria" style="display: none; margin-top: 20px;">
        <form method="POST" action="menu.php">
            <input type="hidden" name="accion" value="insertar_historia">

            <label>Fecha de Chequeo:</label><br>
            <input type="date" name="fecha_chequeo" required><br><br>

            <label>Peso:</label><br>
            <input type="text" name="peso" required><br><br>

            <label>Tratamiento:</label><br>
            <textarea name="tratamiento" required></textarea><br><br>

            <label>Observaciones:</label><br>
            <textarea name="observaciones" required></textarea><br><br>

            <label>Cuidados:</label><br>
            <textarea name="cuidados" required></textarea><br><br>

            <label>Animal:</label><br>
            <select name="id_animal" required>
                <option value="">Seleccione un animal</option>
                <?php
                $sql_animales = "SELECT id_animal, nombre FROM rescatados";
                $resultado_animales = $conexion->query($sql_animales);
                while ($animal = $resultado_animales->fetch_assoc()) {
                    echo "<option value='{$animal['id_animal']}'>{$animal['nombre']}</option>";
                }
                ?>
            </select><br><br>

            <input type="submit" value="Guardar Historia Clínica">
        </form>
    </div>
</div>

<!-- Script para mostrar u ocultar el formulario -->
<script>
function showFormHistoria() {
    const form = document.getElementById("formularioHistoria");
    form.style.display = (form.style.display === "none") ? "block" : "none";
}
</script>

<?php
// Procesar formulario para insertar historia clínica
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'insertar_historia') {
    $fecha = $conexion->real_escape_string($_POST['fecha_chequeo']);
    $peso = $conexion->real_escape_string($_POST['peso']);
    $tratamiento = $conexion->real_escape_string($_POST['tratamiento']);
    $observaciones = $conexion->real_escape_string($_POST['observaciones']);
    $cuidados = $conexion->real_escape_string($_POST['cuidados']);
    $id_animal = (int) $_POST['id_animal'];

    $sql_insert = "INSERT INTO historia_clinica (fecha_chequeo, peso, tratamiento, observaciones, cuidados, id_animal)
                   VALUES ('$fecha', '$peso', '$tratamiento', '$observaciones', '$cuidados', $id_animal)";

    if ($conexion->query($sql_insert) === TRUE) {
        echo "<script>('Historia clínica guardada correctamente.'); window.location.href='menu.php';</script>";
    } else {
        echo "Error al guardar la historia clínica: " . $conexion->error;
    }
}
?>


        
        <!-- ---------------------------------------------- -->


<!-- MODULO ADOPTANTES -->
<?php
// Conectar a la base de datos
$servidor = "127.0.0.1";
$usuario = "root";
$contrasena = "";
$base_datos = "fundacion";
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    if ($accion == "eliminar") {
        if (!empty($_POST['id_adopcion'])) {
            $id_adopcion = (int) $_POST['id_adopcion'];
            $sql_delete = "DELETE FROM adopciones WHERE id_adopcion = $id_adopcion";

            if ($conexion->query($sql_delete) === TRUE) {
                echo "Registro eliminado correctamente";
            } else {
                echo "Error al eliminar: " . $conexion->error;
            }
        } else {
            echo "Error: Falta el ID de adopción.";
        }
        exit;
    }

    // Validar campos solo para insertar o editar
    if ($accion == "insertar" || $accion == "editar") {
        if (empty($_POST['nombre']) || empty($_POST['direccion']) || empty($_POST['correo']) || 
            empty($_POST['telefono']) || empty($_POST['edad']) || empty($_POST['fecha_adopcion']) || 
            empty($_POST['id_animal'])) {
            echo "Error: Todos los campos son obligatorios.";
            exit;
        }
    }

    if ($accion == "insertar") {
        $nombre = $conexion->real_escape_string($_POST['nombre']);
        $direccion = $conexion->real_escape_string($_POST['direccion']);
        $correo = $conexion->real_escape_string($_POST['correo']);
        $telefono = $conexion->real_escape_string($_POST['telefono']);
        $edad = (int) $_POST['edad'];
        $fecha_adopcion = $conexion->real_escape_string($_POST['fecha_adopcion']);
        $observaciones = $conexion->real_escape_string($_POST['observaciones_generales']);
        $id_animal = (int) $_POST['id_animal'];

        $sql_insert = "INSERT INTO adopciones (nombre, direccion, correo, telefono, edad, fecha_adopcion, observaciones_generales, id_animal) 
                       VALUES ('$nombre', '$direccion', '$correo', '$telefono', $edad, '$fecha_adopcion', '$observaciones', $id_animal)";

        if ($conexion->query($sql_insert) === TRUE) {
            echo "<script>('Adoptante registrado exitosamente'); window.location.href='menu.php';</script>";
        } else {
            echo "Error al insertar: " . $conexion->error;
        }
    }

}
?>

<div id="adoptantes" class="dashboard">
    <h1>Personas adoptantes</h1>
    <button onclick="showForm()" class="boton">Ingresar adoptantes</button>

    <div id="tablaAdopciones" style="margin-top: 20px;">
        <?php
        $sql = "SELECT a.id_adopcion, a.nombre, a.direccion, a.correo, a.telefono, a.edad, a.fecha_adopcion, a.observaciones_generales, r.nombre AS animal_nombre 
                FROM adopciones a 
                JOIN rescatados r ON a.id_animal = r.id_animal";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            echo "<table border='1' style='width:100%; text-align:center; border-collapse: collapse;'>";
            echo "<tr style='background-color:#f2f2f2;'>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Edad</th>
                    <th>Fecha de adopción</th>
                    <th>Observaciones</th>
                    <th>Animal Adoptado</th>
                    <th>Acciones</th>
                  </tr>";

            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>
                        <td>{$fila['nombre']}</td>
                        <td>{$fila['direccion']}</td>
                        <td>{$fila['correo']}</td>
                        <td>{$fila['telefono']}</td>
                        <td>{$fila['edad']}</td>
                        <td>{$fila['fecha_adopcion']}</td>
                        <td>{$fila['observaciones_generales']}</td>
                        <td>{$fila['animal_nombre']}</td>
                        <td>
                            <button class='editar' onclick='editarFila(this)'>Editar</button>
                            <button class='eliminar' onclick='eliminarFila(this, {$fila['id_adopcion']})'>Eliminar</button>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay registros aún.</p>";
        }
        ?>
    </div>
</div>

<!-- MODULO INFORMES -->

<?php
// Conectar a la base de datos
$servidor = "127.0.0.1";
$usuario = "root";
$contrasena = "";
$base_datos = "fundacion";
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}
?>

<div id="informes" class="dashboard">
    <h1>Informes</h1>
    <div class="botones">
    <button id="boton1" onclick="showInforme('modulo1')">Historia Clinica</button>
    <button id="boton2" onclick="showInforme('modulo2')">Adoptantes</button>
    <button id="boton3" onclick="showInforme('modulo3')">Rescatados</button>
</div>
    
    <div id="modulo1" class="informe" >
        <h2 class="titulosInformes">Historia Clinica</h2>
        <table border='1' style='width:100%; text-align:center; border-collapse: collapse;'>
            <tr>
                <th>Columna 1</th>
                <th>Columna 2</th>
                <th>Columna 3</th>
                <th>Columna 4</th>
                <th>Columna 5</th>
            </tr>
            <?php
            // Consulta SQL para la tabla 1
            // $sql1 = "SELECT col1, col2, col3, col4, col5 FROM tabla1";
            // $resultado1 = $conexion->query($sql1);
            // if ($resultado1->num_rows > 0) {
            //     while ($fila = $resultado1->fetch_assoc()) {
            //         echo "<tr>
            //                 <td>{$fila['col1']}</td>
            //                 <td>{$fila['col2']}</td>
            //                 <td>{$fila['col3']}</td>
            //                 <td>{$fila['col4']}</td>
            //                 <td>{$fila['col5']}</td>
            //               </tr>";
            //     }
            // }
            ?>
        </table>
    </div>

    <div id="modulo2" class="informe" >
        <h2 class="titulosInformes">Adoptantes</h2>
        <table border='1' style='width:50%; text-align:center; border-collapse: collapse;'>
            <tr>
                <th class="camposInformes">    Tiempo     </th>
                <th class="camposInformes">Cantidad de adoptados</th>
            </tr>
            <?php
            
            $consulta = "SELECT * FROM adopciones";
            $registros = $conexion->query($consulta);
            $adoptadosDia = 0;
            $adoptadosSemana = 0;
             $adoptadosMes = 0;
             $adoptadosAnio = 0;
             $adoptadosMasDeUno = 0;
             
             $hoy = new DateTime(); 
             $adoptantes = []; 
             
             foreach ($registros as $adopcion) {
                 $fechaAdopcion = new DateTime($adopcion['fecha_adopcion']); 
                 $diferenciaDias = $hoy->diff($fechaAdopcion)->days; 
                
                 if ($fechaAdopcion = $hoy) {
                     $adoptadosDia++;
                    }
                    
                    if ($diferenciaDias <= 7) {
                        $adoptadosSemana++;
                    }
                    
                    if ($hoy->format('Y-m') == $fechaAdopcion->format('Y-m')) {
                        $adoptadosMes++;
                    }
                    
                    if ($hoy->format('Y') == $fechaAdopcion->format('Y')) {
                        $adoptadosAnio++;
                    }
                    
                    $nombre = $adopcion['nombre'];
                    if (!isset($adoptantes[$nombre])) {
                        $adoptantes[$nombre] = 1;
                    } else {
                        $adoptantes[$nombre]++;
                        if ($adoptantes[$nombre] == 2) { 
                            $adoptadosMasDeUno++;
                        }
                    }
                }
             
             if ($registros->num_rows > 0) {
                echo "<tr>
                            <td class='camposInformes'>Adoptados ultimo dia</td>
                            <td class='camposInformes'>$adoptadosDia</td>
                      </tr>
                      <tr>
                            <td class='camposInformes'>Adoptados ultima semana</td>
                            <td class='camposInformes'>$adoptadosSemana</td>
                      </tr>
                      <tr>
                            <td class='camposInformes'>Adoptados ultimo mes</td>
                            <td class='camposInformes'>$adoptadosMes</td>
                      </tr>
                      <tr>
                            <td class='camposInformes'>Adoptados ultimo año</td>
                            <td class='camposInformes'>$adoptadosAnio</td>
                      </tr>
                      <tr>
                            <td class='camposInformes'>Adoptante > 1 rescatado</td>
                            <td class='camposInformes'>$adoptadosMasDeUno</td>
                      </tr>";
             }
            ?>
        </table>
    </div>

    <div id="modulo3" class="informe" >
        <h2 class="titulosInformes">Rescatados</h2>
        <table border='1' style='width:100%; text-align:center; border-collapse: collapse;'>
        <tr>
                <th class="camposInformes">    Tiempo     </th>
                <th class="camposInformes">Cantidad de rescatados</th>
            </tr>
            <?php
            
            $consulta = "SELECT * FROM rescatados";
            $registros2 = $conexion->query($consulta);
            $rescatadosDia = 0;
            $rescatadosSemana = 0;
            $rescatadosMes = 0;
            $rescatadosAnio = 0;
            $rescatadosCondiciones = 0;
             
             $hoy = new DateTime(); 
             $rescatados = []; 
             
             foreach ($registros2 as $rescate) {
                 $fecha = new DateTime($rescate['fecha']); 
                 $diferenciaDias = $hoy->diff($fecha)->days; 
                
                 if ($fecha = $hoy) {
                     $rescatadosDia++;
                    }
                    
                    if ($diferenciaDias <= 7 && $fecha <= $hoy) {
                        $rescatadosSemana++;
                    }
                    
                    if ($hoy->format('Y-m') == $fecha->format('Y-m')) {
                        $rescatadosMes++;
                    }
                    
                    if ($hoy->format('Y') == $fecha->format('Y')) {
                        $rescatadosAnio++;
                    }
                    
                    $condiciones_e = $rescate['condiciones_e'];

                    if (!isset($rescatados[$condiciones_e])) {
                        $rescatados[$condiciones_e] = 1;
                    } else {
                        $rescatados[$condiciones_e]++;
                    }
                    
                    // Si el rescate tiene "Si" en condiciones especiales, contar
                    if ($condiciones_e == 'Si') { 
                        $rescatadosCondiciones++;
                    }
                    

                }
             
             if ($registros->num_rows > 0) {
                echo "<tr>
                            <td class='camposInformes'>Rescatados ultimo dia</td>
                            <td class='camposInformes'>$rescatadosDia</td>
                      </tr>
                      <tr>
                            <td class='camposInformes'>Rescatados ultima semana</td>
                            <td class='camposInformes'>$rescatadosSemana</td>
                      </tr>
                      <tr>
                            <td class='camposInformes'>Rescatados ultimo mes</td>
                            <td class='camposInformes'>$rescatadosMes</td>
                      </tr>
                      <tr>
                            <td class='camposInformes'>Rescatados ultimo año</td>
                            <td class='camposInformes'>$rescatadosAnio</td>
                      </tr>
                      <tr>
                            <td class='camposInformes'>Rescatados Condiciones</td>
                            <td class='camposInformes'>$rescatadosCondiciones</td>
                      </tr>";
             }
            ?>
        </table>
    </div>
</div>

<!-- FORMULARIO ADOPTANTES -->
<div id="formularioAdoptantes" class="dashboard" style="display:none;">
    <center>
        <form method="POST" action="">
            <h3>Formulario de Adopción</h3>
            <input type="hidden" name="accion" value="insertar">
            <input type="text" name="nombre" placeholder="Nombre del adoptante" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <input type="number" name="edad" placeholder="Edad" required>
            <input type="date" name="fecha_adopcion" required>
            <textarea name="observaciones_generales" placeholder="Observaciones"></textarea>
            
            <select name="id_animal" required>
                <option value="">Seleccione un animal</option>
                <?php
                $sql_rescatados = "SELECT id_animal, nombre FROM rescatados";
                $resultado_rescatados = $conexion->query($sql_rescatados);
                while ($fila = $resultado_rescatados->fetch_assoc()) {
                    echo "<option value='{$fila['id_animal']}'>{$fila['nombre']}</option>";
                }
                ?>
            </select>
            
            <button type="submit">Guardar</button>
        </form>
    </center>
</div>
<script>
function eliminarFila(boton, id) {
    if (confirm("¿Estás seguro de eliminar esta adopción?")) {
        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ accion: "eliminar", id_adopcion: id })
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes("Registro eliminado correctamente")) {
                boton.closest("tr").remove();
            } else {
                alert(data);
            }
        })
        .catch(error => {
            alert("Error: " + error);
        });
    }
}
</script>


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