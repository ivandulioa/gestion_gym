<?php
include 'db.php';

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date('Y-m-d');

// Procesar el registro de horas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_empleado'], $_POST['nombre_empleado'])) {
    $id_empleado = $_POST['id_empleado'];
    $nombre_empleado = $_POST['nombre_empleado'];
    $fecha_hora_actual = date('d-m-Y H:i:s');

    $sql_registro = "SELECT * FROM registro_horas WHERE id_empleado = $id_empleado AND hora_salida IS NULL ORDER BY hora_entrada DESC LIMIT 1";
    $resultado_registro = $conn->query($sql_registro);

    if ($resultado_registro->num_rows > 0) {
        $sql_salida = "UPDATE registro_horas SET hora_salida = NOW() WHERE id_empleado = $id_empleado AND hora_salida IS NULL";
        if ($conn->query($sql_salida) === TRUE) {
            $mensaje = "Hora de salida registrada con éxito.";
            $tipo = "success";
        } else {
            $mensaje = "Error al registrar la hora de salida: " . $conn->error;
            $tipo = "error";
        }
    } else {
        $sql_entrada = "INSERT INTO registro_horas (id_empleado, hora_entrada) VALUES ($id_empleado, NOW())";
        if ($conn->query($sql_entrada) === TRUE) {
            $mensaje = "Hora de entrada registrada con éxito.";
            $tipo = "success";
        } else {
            $mensaje = "Error al registrar la hora de entrada: " . $conn->error;
            $tipo = "error";
        }
    }

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '$mensaje',
                text: 'Fecha y hora: $fecha_hora_actual',
                icon: '$tipo'
            }).then(() => {
                window.location.href = 'registro_horas.php';
            });
        });
    </script>";
}

$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$fecha_filtro = isset($_GET['fecha']) ? $_GET['fecha'] : '';

$empleados = $conn->query("SELECT id_empleado, nombre, apellido FROM empleados WHERE CONCAT(nombre, ' ', apellido) LIKE '%$buscar%'");

$consulta_registros = "SELECT e.nombre, e.apellido, DATE_FORMAT(r.hora_entrada, '%d/%m/%Y %H:%i:%s') AS hora_entrada, DATE_FORMAT(r.hora_salida, '%d/%m/%Y %H:%i:%s') AS hora_salida FROM registro_horas r JOIN empleados e ON r.id_empleado = e.id_empleado";
if ($fecha_filtro) {
    $consulta_registros .= " WHERE DATE(r.hora_entrada) = '$fecha_filtro'";
}
$consulta_registros .= " ORDER BY r.hora_entrada DESC";
$registros = $conn->query($consulta_registros);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Horas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Registro de Horas</h2>
    <a href="index.php" class="btn btn-secondary mb-3">Regresar a Inicio</a>

    <form method="GET" class="mb-4">
        <div class="input-group mb-3">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar empleado por nombre o apellido" value="<?php echo htmlspecialchars($buscar); ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
        <div class="input-group mb-3">
            <input type="date" name="fecha" class="form-control" value="<?php echo htmlspecialchars($fecha_filtro); ?>">
            <button type="submit" class="btn btn-info">Filtrar por Fecha</button>
        </div>
    </form>

    <h5>Seleccionar Empleado</h5>
    <form method="POST">
        <select class="form-select mb-3" name="id_empleado" required>
            <option value="" disabled selected>Selecciona un empleado</option>
            <?php while ($row = $empleados->fetch_assoc()): ?>
                <option value="<?php echo $row['id_empleado']; ?>">
                    <?php echo $row['nombre'] . " " . $row['apellido']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <input type="hidden" name="nombre_empleado" value="<?php echo $row['nombre'] . ' ' . $row['apellido']; ?>">
        <button type="submit" class="btn btn-success">Registrar Hora</button>
    </form>

    <h5 class="mt-5">Registros de Horas</h5>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Hora de Entrada</th>
            <th>Hora de Salida</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $registros->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['nombre'] . " " . $row['apellido']; ?></td>
                <td><?php echo $row['hora_entrada']; ?></td>
                <td><?php echo $row['hora_salida'] ? $row['hora_salida'] : 'Pendiente'; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
