<?php
include 'db.php';

// Verificar si se ha recibido un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM usuarios WHERE id_usuario = $id";

    if ($conn->query($sql) === TRUE) {
        echo "
            <script>
                alert('Usuario eliminado correctamente.');
                window.location.href = 'usuarios.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Error al eliminar el usuario: " . $conn->error . "');
                window.location.href = 'usuarios.php';
            </script>
        ";
    }
} else {
    echo "
        <script>
            alert('ID no proporcionado.');
            window.location.href = 'usuarios.php';
        </script>
    ";
}
?>
