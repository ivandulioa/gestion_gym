<?php
include 'db.php';


// Obtener los productos del inventario para mostrarlos
$sql_productos = "SELECT * FROM productos";
$productos = $conn->query($sql_productos);

// Manejo del registro de ventas
if (isset($_POST['registrar_venta'])) {
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];

    $producto_sql = "SELECT * FROM productos WHERE id_producto = $id_producto";
    $producto = $conn->query($producto_sql)->fetch_assoc();

    if ($producto && $producto['cantidad'] >= $cantidad) {
        $subtotal = $cantidad * $producto['precio_publico'];
        $sql_venta = "INSERT INTO ventas (id_producto, descripcion, cantidad, precio, fecha)
                      VALUES ($id_producto, '{$producto['nombre_producto']}', $cantidad, {$producto['precio_publico']}, NOW())";
        $conn->query($sql_venta);

        $nueva_cantidad = $producto['cantidad'] - $cantidad;
        $sql_update = "UPDATE productos SET cantidad = $nueva_cantidad WHERE id_producto = $id_producto";
        $conn->query($sql_update);

        echo "<script>alert('Venta registrada con éxito.');</script>";
    } else {
        echo "<script>alert('Cantidad insuficiente en el inventario.');</script>";
    }
}

// Filtrar ventas por periodo
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'diario';
switch ($periodo) {
    case 'diario':
        $sql_ventas = "SELECT * FROM ventas WHERE DATE(fecha) = CURDATE()";
        break;
    case 'semanal':
        $sql_ventas = "SELECT * FROM ventas WHERE YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    case 'mensual':
        $sql_ventas = "SELECT * FROM ventas WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())";
        break;
    default:
        $sql_ventas = "SELECT * FROM ventas";
        break;
}

// Obtener el total de ventas del día
$sql_total_ventas = "SELECT SUM(cantidad * precio) AS total_ventas FROM ventas WHERE DATE(fecha) = CURDATE()";
$resultado_total_ventas = $conn->query($sql_total_ventas);
$total_ventas = $resultado_total_ventas->fetch_assoc()['total_ventas'] ?? 0;

$ventas = $conn->query($sql_ventas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Ventas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left"></i> Regresar a Inicio
        </a>
        <h2 class="text-center mb-4">Control de Ventas</h2>



        <!-- Registro de ventas -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Registrar Venta</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="id_producto" class="form-label">Producto</label>
                        <select name="id_producto" id="id_producto" class="form-select" required>
                            <option value="">Seleccione un producto</option>
                            <?php
                            $productos = $conn->query("SELECT * FROM productos");
                            while ($producto = $productos->fetch_assoc()) {
                                echo "<option value='{$producto['id_producto']}'>{$producto['nombre_producto']} (Disponibles: {$producto['cantidad']})</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                    </div>
                    <button type="submit" name="registrar_venta" class="btn btn-primary">Registrar Venta</button>
                </form>
            </div>
        </div>

        <!-- Mostrar Total de Ventas del Día -->
        <div class="alert alert-info">
            <strong>Total de ventas del día:</strong> $<?php echo number_format($total_ventas, 2); ?>
        </div>

        <!-- Visualización de ventas -->
        <form method="GET" action="" class="mb-4">
            <div class="input-group">
                <select name="periodo" class="form-select">
                    <option value="diario" <?php echo $periodo === 'diario' ? 'selected' : ''; ?>>Diario</option>
                    <option value="semanal" <?php echo $periodo === 'semanal' ? 'selected' : ''; ?>>Semanal</option>
                    <option value="mensual" <?php echo $periodo === 'mensual' ? 'selected' : ''; ?>>Mensual</option>
                </select>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>

        <h5>Ventas (<?php echo ucfirst($periodo); ?>)</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($ventas->num_rows > 0) {
                    while ($venta = $ventas->fetch_assoc()) {
                        $total = $venta['cantidad'] * $venta['precio'];
                        echo "<tr>
                            <td>{$venta['id_venta']}</td>
                            <td>{$venta['descripcion']}</td>
                            <td>{$venta['cantidad']}</td>
                            <td>\${$venta['precio']}</td>
                            <td>\${$total}</td>
                            <td>{$venta['fecha']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No hay ventas registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
