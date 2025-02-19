<?php
include 'db.php';

// Verificar si se ha recibido un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM membresias WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "
            <script>
                alert('Membresía eliminada correctamente.');
                window.location.href = 'membresias.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Error al eliminar la membresía: " . $conn->error . "');
                window.location.href = 'membresias.php';
            </script>
        ";
    }
} else {
    echo "
        <script>
            alert('ID no proporcionado.');
            window.location.href = 'membresias.php';
        </script>
    ";
}
?>
