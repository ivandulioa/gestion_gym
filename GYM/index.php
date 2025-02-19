<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Gimnasio</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Sistema de Gestión del Gimnasio</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="text-center"><i class="fa fa-users"></i> Usuarios</h3>
                    </div>
                    <div class="card-body text-center">
                        <p>Gestiona los usuarios registrados en el sistema.</p>
                        <a href="usuarios.php" class="btn btn-primary"><i class="fa fa-users"></i> Ir a Usuarios</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="text-center"><i class="fa fa-id-card"></i> Membresías</h3>
                    </div>
                    <div class="card-body text-center">
                        <p>Gestiona los planes de membresía del gimnasio.</p>
                        <a href="membresias.php" class="btn btn-success"><i class="fa fa-id-card"></i> Ir a Membresías</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h3 class="text-center"><i class="fa fa-clock"></i> Registro de Asistencias</h3>
                    </div>
                    <div class="card-body text-center">
                        <p>Registrar y consultar asistencias de usuarios.</p>
                        <a href="registrar_asistencia.php" class="btn btn-info">Registrar Asistencia</a>
                        <a href="historial_asistencias.php" class="btn btn-secondary">Ver Historial</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h3 class="text-center"><i class="fa fa-money"></i> Control de Ventas</h3>
                    </div>
                    <div class="card-body text-center">
                        <p>Registrar y consultar ventas.</p>
                        <a href="control_ventas.php" class="btn btn-warning">Venta</a>
                        <a href="inventario.php" class="btn btn-secondary">Ver Inventario</a>

                    </div>
                </div>
            </div>


        </div>
    </div>
</body>
</html>
