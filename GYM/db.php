<?php
$host = "localhost";
$db = "gimnasio";
$user = "root";
$password = "";

// Conexión a la base de datos
$conn = new mysqli($host, $user, $password, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
