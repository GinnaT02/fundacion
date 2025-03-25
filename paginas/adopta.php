<?php
    $mascotas = [
        ["nombre" => "Paco", "edad" => 9, "descripcion" => "Perrito cariñoso y enérgico."],
        ["nombre" => "Milo", "edad" => 5, "descripcion" => "Gatito curioso e independiente."]
    ];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes de Adopción</title>
    <link rel="stylesheet" type="text/css" href="../css/estilo_informes.css">
</head>
<body>
    <header>
        <h1>Informes de Adopción</h1>
    </header>
    <main>
        <section>
            <h2>Filtrar Informes</h2>
            <form method="GET">
                <label for="edad">Filtrar por edad:</label>
                <select name="edad" id="edad">
                    <option value="todos">Todos</option>
                    <option value="joven">Menos de 5 años</option>
                    <option value="adulto">5 años o más</option>
                </select>
                <button type="submit">Generar Informe</button>
            </form>
        </section>
        <section>
            <h2>Lista de Mascotas</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $filtro = $_GET['edad'] ?? 'todos';
                        foreach ($mascotas as $mascota) {
                            if (($filtro == 'joven' && $mascota['edad'] >= 5) || ($filtro == 'adulto' && $mascota['edad'] < 5)) {
                                continue;
                            }
                            echo "<tr>";
                            echo "<td>{$mascota['nombre']}</td>";
                            echo "<td>{$mascota['edad']} años</td>";
                            echo "<td>{$mascota['descripcion']}</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <form method="POST" action="exportar_pdf.php">
            <button type="submit">Exportar a PDF</button>
        </form>
    </footer>
</body>
</html>

