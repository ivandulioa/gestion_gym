<?php
include 'db.php';

// Configurar la zona horaria para México
date_default_timezone_set('America/Mexico_City');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $duracion = $_POST['duracion'];
    $tipo_duracion = $_POST['tipo_duracion'];
    $descripcion = $_POST['descripcion'];
    $id_usuario = $_POST['id_usuario'];

    // Fecha de inicio (fecha actual del sistema en la zona horaria de México)
    $fecha_inicio = date('Y-m-d'); // Fecha del sistema en México
    
    // Calcular la fecha de término según la duración
    switch ($tipo_duracion) {
        case 'dias':
            $fecha_termino = date('Y-m-d', strtotime("+{$duracion} days"));
            break;
        case 'semanas':
            $fecha_termino = date('Y-m-d', strtotime("+{$duracion} weeks"));
            break;
        case 'meses':
            $fecha_termino = date('Y-m-d', strtotime("+{$duracion} months"));
            break;
        case 'anios':
            $fecha_termino = date('Y-m-d', strtotime("+{$duracion} years"));
            break;
        case 'promocion':
            $fecha_termino = '0000-00-00'; // Fecha indefinida para promociones
            break;
        default:
            $fecha_termino = $fecha_inicio;
    }

    // Insertar la membresía en la base de datos
    $sql_membresia = "INSERT INTO membresias (nombre, precio, duracion, tipo_duracion, descripcion, fecha_inicio, fecha_termino) 
                      VALUES ('$nombre', '$precio', '$duracion', '$tipo_duracion', '$descripcion', '$fecha_inicio', '$fecha_termino')";

    if ($conn->query($sql_membresia) === TRUE) {
        $id = $conn->insert_id; // Obtener el ID de la membresía recién creada

        // Asociar la membresía al usuario
        $sql_asociar = "INSERT INTO usuarios_membresias (id_usuario, id) VALUES ($id_usuario, $id)";

        if ($conn->query($sql_asociar) === TRUE) {
            header("Location: membresias.php");
            exit();
        } else {
            echo "Error al asociar la membresía al usuario: " . $conn->error;
        }
    } else {
        echo "Error al crear la membresía: " . $conn->error;
    }
}

// Obtener los usuarios disponibles
$usuarios = $conn->query("SELECT id_usuario, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Membresía</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center">Crear Membresía</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio (MXN)</label>
                        <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="duracion" class="form-label">Duración</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="duracion" name="duracion" required>
                            <select class="form-select" id="tipo_duracion" name="tipo_duracion">
                                <option value="dias">Días</option>
                                <option value="semanas">Semanas</option>
                                <option value="meses" selected>Meses</option>
                                <option value="anios">Años</option>
                                <option value="promocion">Promoción</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="id_usuario" class="form-label">Usuario</label>
                        <select class="form-select" id="id_usuario" name="id_usuario" required>
                            <option value="" disabled selected>Selecciona un usuario</option>
                            <?php
                            while ($usuario = $usuarios->fetch_assoc()) {
                                echo "<option value='" . $usuario['id_usuario'] . "'>" . $usuario['nombre_completo'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Mostrar la fecha de inicio actual como valor predefinido -->
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                        <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo date('d-m-Y'); ?>" readonly>
                        <small class="form-text text-muted">La fecha de inicio será automáticamente la fecha actual del sistema.</small>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="membresias.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
