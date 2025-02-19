<?php
include 'db.php';

// Obtener el término de búsqueda
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

// Consultar el historial de asistencias, filtrando si hay búsqueda
$sql = "
    SELECT a.id_asistencia, u.nombre, u.apellido, a.fecha 
    FROM asistencias a
    JOIN usuarios u ON a.id_usuario = u.id_usuario
";
if ($buscar) {
    $sql .= " WHERE CONCAT(u.nombre, ' ', u.apellido) LIKE '%$buscar%'";
}
$sql .= " ORDER BY a.fecha DESC";

$asistencias = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Asistencias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left"></i> Regresar a Inicio
        </a>
        <h2 class="text-center mb-4">Historial de Asistencias</h2>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="" class="mb-4">
            <div class="input-group">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar usuario por nombre o apellido" value="<?php echo htmlspecialchars($buscar); ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <!-- Resultados del historial -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Asistencia</th>
                    <th>Usuario</th>
                    <th>Fecha y Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($asistencias->num_rows > 0): ?>
                    <?php while ($row = $asistencias->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_asistencia']; ?></td>
                            <td><?php echo $row['nombre'] . " " . $row['apellido']; ?></td>
                            <td><?php echo $row['fecha']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No se encontraron registros.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
