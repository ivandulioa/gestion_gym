<?php
include 'db.php';

// Obtener todos los empleados
$sql = "SELECT * FROM empleados";
$result = $conn->query($sql);

// Contar total de empleados
$sql_total_empleados = "SELECT COUNT(*) AS total FROM empleados";
$result_total = $conn->query($sql_total_empleados);
$total_empleados = $result_total->fetch_assoc()['total'];


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left"></i> Regresar a Inicio
        </a>

        <h1 class="text-center mb-4">Gestión de Empleados</h1>



        <a href="crear_empleado.php" class="btn btn-success mb-3">
            <i class="fa fa-user-plus"></i> Nuevo Empleado
        </a>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Dirección</th>
                    <th>Puesto</th>
                    <th>Fecha de Ingreso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_empleado']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['apellido']; ?></td>
                        <td><?php echo $row['direccion']; ?></td>
                        <td><?php echo $row['puesto']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['fecha_ingreso'])); ?></td>
                        <td>
                            <a href="editar_empleado.php?id=<?php echo $row['id_empleado']; ?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i> Editar
                            </a>
                           <a href="eliminar_empleado.php?id=<?php echo $row['id_empleado']; ?>" class="btn btn-danger btn-sm" 
                                onclick="event.preventDefault(); 
                                eliminarEmpleado(<?php echo $row['id_empleado']; ?>)">
                                 <i class="fa fa-trash"></i> Eliminar
                            </a>

                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            function eliminarEmpleado(idEmpleado) {
                                Swal.fire({
                                    title: '¿Estás seguro?',
                                    text: "¡No podrás revertir esta acción!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí, eliminarlo',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Si el usuario confirma, redirigimos a la página de eliminación
                                        window.location.href = "eliminar_empleado.php?id=<?php echo $row['id_empleado']; ?>"
                                    }
                                });
                            }
                        </script>

                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>

<!-- ¿Te gustaría que ajuste las métricas o agregue alguna funcionalidad adicional? 🛠️ -->
