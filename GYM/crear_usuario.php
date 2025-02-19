<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];

    // Se elimina la columna 'email' de la consulta SQL
    $sql = "INSERT INTO usuarios (nombre, apellido, telefono) VALUES ('$nombre', '$apellido', '$telefono')";
    if ($conn->query($sql) === TRUE) {
        header("Location: usuarios.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Bootstrap, Font Awesome y estilos personalizados -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center"><i class="fa fa-user-plus"></i> Crear Usuario</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                    <!-- Se elimina el campo de email -->
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                    <a href="usuarios.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
