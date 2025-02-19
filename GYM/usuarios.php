<?php
include 'db.php';

// Obtener todos los usuarios
$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);

// Contar membresías activas
$sql_membresias_activas = "SELECT COUNT(*) AS activas FROM membresias WHERE CURDATE() BETWEEN fecha_inicio AND fecha_termino";
$result_membresias_activas = $conn->query($sql_membresias_activas);
$membresias_activas = $result_membresias_activas->fetch_assoc()['activas'];

// Contar asistencias de hoy
$sql_asistencias_hoy = "SELECT COUNT(*) AS asistencias_hoy FROM asistencias WHERE DATE(fecha) = CURDATE()";
$result_asistencias_hoy = $conn->query($sql_asistencias_hoy);
$asistencias_hoy = $result_asistencias_hoy->fetch_assoc()['asistencias_hoy'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary, .btn-warning, .btn-danger {
            transition: background-color 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left"></i> Regresar a Inicio
        </a>

        <h1 class="text-center mb-4">Gestión de Usuarios</h1>

        <!-- Métricas principales -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total de Usuarios</h5>
                        <p class="card-text">
                            <?php
                            $sql_count = "SELECT COUNT(*) AS total FROM usuarios";
                            $result_count = $conn->query($sql_count);
                            $total = $result_count->fetch_assoc()['total'];
                            echo $total;
                            ?>
                        </p>
                        <i class="fa fa-users fa-2x float-end"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Membresías Activas</h5>
                        <p class="card-text"><?php echo $membresias_activas; ?></p>
                        <i class="fa fa-id-card fa-2x float-end"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Asistencias Hoy</h5>
                        <p class="card-text"><?php echo $asistencias_hoy; ?></p>
                        <i class="fa fa-check fa-2x float-end"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de nuevo usuario -->
        <a href="crear_usuario.php" class="btn btn-success mb-3">
            <i class="fa fa-user-plus"></i> Nuevo Usuario
        </a>

        <!-- Tabla estilizada -->
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_usuario']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['apellido']; ?></td>
                        <td><?php echo $row['telefono']; ?></td>
                        <td><?php echo date('d/ m/ Y', strtotime($row['fecha_registro'])); ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $row['id_usuario']; ?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i> Editar
                            </a>
                            <a href="eliminar_usuario.php?id=<?php echo $row['id_usuario']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">
                                <i class="fa fa-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
