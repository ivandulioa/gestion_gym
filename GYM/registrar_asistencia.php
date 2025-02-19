<?php
include 'db.php';

// Configurar la zona horaria
date_default_timezone_set('America/Mexico_City');
$fecha_actual = date('Y-m-d'); // Fecha actual del sistema

// Procesar el registro de asistencia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];

    // Consultar la membresía activa más reciente
    $sql_membresia = "
        SELECT m.fecha_termino 
        FROM usuarios_membresias AS um
        INNER JOIN membresias AS m ON um.id = m.id
        WHERE um.id_usuario = $id_usuario
        ORDER BY m.fecha_termino DESC
        LIMIT 1
    ";
    $resultado_membresia = $conn->query($sql_membresia);

    if ($resultado_membresia->num_rows > 0) {
        $membresia = $resultado_membresia->fetch_assoc();
        $fecha_termino = $membresia['fecha_termino'];

        // Formatear la fecha de término (d/m/Y)
        $fecha_termino_formateada = date('d/m/Y', strtotime($fecha_termino));

        if ($fecha_termino >= $fecha_actual) {
            // Calcular los días restantes
            $dias_restantes = (strtotime($fecha_termino) - strtotime($fecha_actual)) / 86400;

            // Registrar asistencia
            $sql = "INSERT INTO asistencias (id_usuario) VALUES ($id_usuario)";
            if ($conn->query($sql) === TRUE) {
                $mensaje = "Asistencia registrada con éxito. Le quedan $dias_restantes día(s) de membresía. Su fecha de termino es $fecha_termino_formateada";
            } else {
                $mensaje = "Error al registrar la asistencia: " . $conn->error;
            }
        } else {
            // Membresía caducada
            $mensaje = "No se puede registrar asistencia. Su membresía ha caducado (vencimiento: $fecha_termino).";
        }
    } else {
        // No tiene membresía activa
        $mensaje = "El usuario no tiene una membresía activa.";
    }

    echo "<script>alert('$mensaje'); window.location.href = 'registrar_asistencia.php';</script>";
}

// Buscar usuarios si se ha enviado el término de búsqueda
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$usuarios = $conn->query("
    SELECT id_usuario, nombre, apellido 
    FROM usuarios 
    WHERE CONCAT(nombre, ' ', apellido) LIKE '%$buscar%'
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Asistencia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left"></i> Regresar a Inicio
        </a>
        <div class="card">
            <div class="card-header bg-success text-white">
                <h2 class="text-center">Registrar Asistencia</h2>
            </div>
            <div class="card-body">
                <!-- Formulario de búsqueda -->
                <form method="GET" action="" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar usuario por nombre o apellido" value="<?php echo htmlspecialchars($buscar); ?>">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>

                <!-- Resultados de búsqueda -->
                <?php if ($buscar): ?>
                    <h5>Resultados para "<?php echo htmlspecialchars($buscar); ?>"</h5>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($usuarios->num_rows > 0): ?>
                                <?php while ($row = $usuarios->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['nombre'] . " " . $row['apellido']; ?></td>
                                        <td>
                                            <form method="POST" action="">
                                                <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario']; ?>">
                                                <button type="submit" class="btn btn-success">Registrar Asistencia</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-center">No se encontraron usuarios.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <!-- Selección directa de usuario -->
                <h5>Registrar asistencia manual</h5>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="id_usuario" class="form-label">Seleccionar Usuario</label>
                        <select class="form-select" id="id_usuario" name="id_usuario" required>
                            <option value="" disabled selected>Selecciona un usuario</option>
                            <?php
                            // Obtener todos los usuarios si no hay búsqueda activa
                            if (!$buscar) {
                                $usuarios_todos = $conn->query("SELECT id_usuario, nombre, apellido FROM usuarios");
                                while ($row = $usuarios_todos->fetch_assoc()) {
                                    echo "<option value='" . $row['id_usuario'] . "'>" . $row['nombre'] . " " . $row['apellido'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Registrar Asistencia</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
