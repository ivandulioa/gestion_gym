<?php
include 'db.php';

// Manejo de entrada de mercancía (registro nuevo)
if (isset($_POST['registrar_entrada'])) {
    $nombre_producto = $_POST['nombre_producto'];
    $cantidad = $_POST['cantidad'];
    $precio_unitario = $_POST['precio_unitario'];
    $precio_publico = $_POST['precio_publico'];

    $sql = "INSERT INTO productos (nombre_producto, cantidad, precio_unitario, precio_publico) 
            VALUES ('$nombre_producto', $cantidad, $precio_unitario, $precio_publico)";
    if ($conn->query($sql)) {
        echo "<script>alert('Entrada de mercancía registrada con éxito.');</script>";
    } else {
        echo "<script>alert('Error al registrar la mercancía: " . $conn->error . "');</script>";
    }
}

// Manejo de edición de producto
if (isset($_POST['editar_producto'])) {
    $id_producto = $_POST['id_producto'];
    $nombre_producto = $_POST['nombre_producto'];
    $cantidad = $_POST['cantidad'];
    $precio_unitario = $_POST['precio_unitario'];
    $precio_publico = $_POST['precio_publico'];

    $sql = "UPDATE productos SET 
            nombre_producto = '$nombre_producto', 
            cantidad = $cantidad, 
            precio_unitario = $precio_unitario, 
            precio_publico = $precio_publico 
            WHERE id_producto = $id_producto";

    if ($conn->query($sql)) {
        echo "<script>alert('Producto editado con éxito.');</script>";
    } else {
        echo "<script>alert('Error al editar el producto: " . $conn->error . "');</script>";
    }
}

// Manejo de eliminación de producto
if (isset($_POST['eliminar_producto'])) {
    $id_producto = $_POST['id_producto'];

    $sql = "DELETE FROM productos WHERE id_producto = $id_producto";
    if ($conn->query($sql)) {
        echo "<script>alert('Producto eliminado con éxito.');</script>";
    } else {
        echo "<script>alert('Error al eliminar el producto: " . $conn->error . "');</script>";
    }
}

// Obtener los productos del inventario para mostrarlos
$sql_productos = "SELECT * FROM productos";
$productos = $conn->query($sql_productos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left"></i> Regresar a Inicio
        </a>
        <h2 class="text-center mb-4">Inventario</h2>

        <!-- Botones para abrir los modales -->
        <div class="mb-4 text-center">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistrarEntrada">Registrar Entrada</button>
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarProducto">Editar Producto</button>
        </div>

        <!-- Modal para editar producto -->
        <div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="modalEditarProductoLabel">Editar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="id_producto" class="form-label">Seleccionar Producto</label>
                                <select name="id_producto" id="id_producto" class="form-select" required>
                                    <option value="">Seleccione un producto</option>
                                    <?php
                                    while ($producto = $productos->fetch_assoc()) {
                                        echo "<option value='{$producto['id_producto']}'>{$producto['nombre_producto']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="nombre_producto" class="form-label">Nombre del Producto</label>
                                <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="precio_unitario" class="form-label">Precio Unitario</label>
                                <input type="number" step="0.01" class="form-control" id="precio_unitario" name="precio_unitario" required>
                            </div>
                            <div class="mb-3">
                                <label for="precio_publico" class="form-label">Precio Público</label>
                                <input type="number" step="0.01" class="form-control" id="precio_publico" name="precio_publico" required>
                            </div>
                            <button type="submit" name="editar_producto" class="btn btn-warning">Editar Producto</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para eliminar producto -->
        <div class="modal fade" id="modalEliminarProducto" tabindex="-1" aria-labelledby="modalEliminarProductoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="modalEliminarProductoLabel">Eliminar Producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que deseas eliminar este producto?</p>
                            <input type="hidden" name="id_producto" id="eliminar_id_producto">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="eliminar_producto" class="btn btn-danger">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

                        <!-- Modal para registrar entrada de mercancía -->
        <div class="modal fade" id="modalRegistrarEntrada" tabindex="-1" aria-labelledby="modalRegistrarEntradaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalRegistrarEntradaLabel">Registrar Entrada de Mercancía</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nombre_producto" class="form-label">Nombre del Producto</label>
                                <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="precio_unitario" class="form-label">Precio Unitario</label>
                                <input type="number" step="0.01" class="form-control" id="precio_unitario" name="precio_unitario" required>
                            </div>
                            <div class="mb-3">
                                <label for="precio_publico" class="form-label">Precio Público</label>
                                <input type="number" step="0.01" class="form-control" id="precio_publico" name="precio_publico" required>
                            </div>
                            <button type="submit" name="registrar_entrada" class="btn btn-success">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mostrar Productos en Inventario -->
        <h5>Productos en Inventario</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Precio Público</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $productos->data_seek(0); // Reinicia el puntero del resultado
                if ($productos->num_rows > 0) {
                    while ($producto = $productos->fetch_assoc()) {
                        echo "<tr>
                                <td>{$producto['id_producto']}</td>
                                <td>{$producto['nombre_producto']}</td>
                                <td>{$producto['cantidad']}</td>
                                <td>$" . number_format($producto['precio_unitario'], 2) . "</td>
                                <td>$" . number_format($producto['precio_publico'], 2) . "</td>
                                <td>
                                    <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#modalEliminarProducto' 
                                            onclick=\"document.getElementById('eliminar_id_producto').value='{$producto['id_producto']}';\">
                                        Eliminar
                                    </button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay productos registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
