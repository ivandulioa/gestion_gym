<?php
include 'db.php';

date_default_timezone_set('America/Mexico_City');

// Buscar membresías si se ha enviado un término de búsqueda
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$sql = "SELECT * FROM membresias";
if ($buscar) {
    $sql .= " WHERE nombre LIKE '%$buscar%' OR descripcion LIKE '%$buscar%'";
}
$result = $conn->query($sql);

// Calcular el total de precios de membresías renovadas en el día actual
$total_renovadas_hoy = $conn->query("
    SELECT SUM(precio) AS total 
    FROM membresias 
    WHERE updated_at = CURDATE()
")->fetch_assoc()['total'] ?? 0;

// Calcular el total de precios de membresías activas en el día actual
$total_activas_hoy = $conn->query("
    SELECT SUM(precio) AS total 
    FROM membresias 
    WHERE (fecha_termino >= CURDATE() OR fecha_termino = '0000-00-00')
")->fetch_assoc()['total'] ?? 0;

$fecha_actual = date('d-m-Y');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Membresías</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .table-danger {
            background-color: #f8d7da !important;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left"></i> Regresar a Inicio
        </a>

        <h1 class="text-center mb-4">Lista de Membresías</h1>
        
        <!-- Mostrar totales del día -->
        <div class="mb-4">
            <h5><strong>Total de precio de membresías renovadas hoy (<?php echo $fecha_actual; ?>):</strong></h5>
            <p>$<?php echo number_format($total_renovadas_hoy, 2); ?></p>

            <h5><strong>Total de precio de membresías activas hoy (<?php echo $fecha_actual; ?>):</strong></h5>
            <p>$<?php echo number_format($total_activas_hoy, 2); ?></p>
        </div>
        
        <!-- Formulario de búsqueda -->
        <form method="GET" action="" class="mb-4">
            <div class="input-group">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar membresías por nombre o descripción" value="<?php echo htmlspecialchars($buscar); ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <a href="crear_membresia.php" class="btn btn-success mb-3">Nueva Membresía</a>
        
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Duración</th>
                    <th>Inicio</th>
                    <th>Término</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php 
                            // Determinar si la membresía está caducada
                            $fecha_termino = $row['fecha_termino'];
                            $caducada = ($fecha_termino !== '0000-00-00' && $fecha_termino < date('Y-m-d'));
                        ?>
                        <tr class="<?php echo $caducada ? 'table-danger' : ''; ?>">
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td>$<?php echo number_format($row['precio'], 2); ?></td>
                            <td>
                                <?php echo $row['duracion'] . ' '; ?>
                                <?php 
                                    switch ($row['tipo_duracion']) {
                                        case 'dias': echo 'días'; break;
                                        case 'semanas': echo 'semanas'; break;
                                        case 'meses': echo 'meses'; break;
                                        case 'anios': echo 'años'; break;
                                        case 'promocion': echo 'promoción'; break;
                                    }
                                ?>
                            </td>
                            <td><?php echo date('d-m-Y', strtotime($row['fecha_inicio'])); ?></td>
                            <td>
                                <?php echo $fecha_termino !== '0000-00-00' ? date('d-m-Y', strtotime($fecha_termino)) : 'Indefinido'; ?>
                            </td>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td>
                                <a href="editar_membresia.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="eliminar_membresia.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta membresía?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No se encontraron membresías.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
