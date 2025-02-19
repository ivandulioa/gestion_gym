<?php
include 'db.php';

// Obtener los datos de la membresía a editar
$id = $_GET['id'];
$sql = "SELECT * FROM membresias WHERE id = $id";
$result = $conn->query($sql);
$membresia = $result->fetch_assoc();

// Obtener la información de la asociación relacionada
$sql_asociacion = "SELECT id FROM usuarios_membresias WHERE id = " . $membresia['id'];
$result_asociacion = $conn->query($sql_asociacion);
$asociacion = $result_asociacion->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Membresía</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h2 class="text-center">Editar Membresía</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $membresia['id']; ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $membresia['nombre']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio (MXN)</label>
                        <input type="number" class="form-control" id="precio" name="precio" step="0.01" value="<?php echo $membresia['precio']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_duracion" class="form-label">Tipo de Duración</label>
                        <select class="form-select" id="tipo_duracion" name="tipo_duracion" required>
                            <option value="dia" <?php echo ($membresia['tipo_duracion'] == 'dia') ? 'selected' : ''; ?>>Día</option>
                            <option value="semana" <?php echo ($membresia['tipo_duracion'] == 'semana') ? 'selected' : ''; ?>>Semana</option>
                            <option value="mes" <?php echo ($membresia['tipo_duracion'] == 'mes') ? 'selected' : ''; ?>>Mes</option>
                            <option value="año" <?php echo ($membresia['tipo_duracion'] == 'año') ? 'selected' : ''; ?>>Año</option>
                            <option value="promocion" <?php echo ($membresia['tipo_duracion'] == 'promocion') ? 'selected' : ''; ?>>Promoción</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="duracion" class="form-label">Duración (Número)</label>
                        <input type="number" class="form-control" id="duracion" name="duracion" value="<?php echo $membresia['duracion']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo $membresia['descripcion']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="asociacion" class="form-label">Asociación</label>
                        <input type="text" class="form-control" id="asociacion" value="<?php echo $asociacion['id']; ?>" readonly>
                    </div>
                    <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                    <a href="membresias.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
date_default_timezone_set('America/Mexico_City');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $tipo_duracion = $_POST['tipo_duracion'];
    $duracion = $_POST['duracion'];
    $descripcion = $_POST['descripcion'];
    $id = $membresia['id']; // Mantener la asociación actual

    // Fecha de inicio
    $fecha_inicio = date('Y-m-d');
    switch ($tipo_duracion) {
        case 'dia': $fecha_termino = date('Y-m-d', strtotime("+$duracion days")); break;
        case 'semana': $fecha_termino = date('Y-m-d', strtotime("+$duracion weeks")); break;
        case 'mes': $fecha_termino = date('Y-m-d', strtotime("+$duracion months")); break;
        case 'año': $fecha_termino = date('Y-m-d', strtotime("+$duracion years")); break;
        case 'promocion': $fecha_termino = '0000-00-00'; break;
        default: $fecha_termino = null; break;
    }

    $sql = "UPDATE membresias SET 
            nombre = '$nombre', 
            precio = '$precio', 
            tipo_duracion = '$tipo_duracion', 
            duracion = '$duracion', 
            fecha_inicio = '$fecha_inicio', 
            fecha_termino = '$fecha_termino', 
            descripcion = '$descripcion', 
            id = '$id'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: membresias.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
