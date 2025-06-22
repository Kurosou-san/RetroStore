<?php
require_once '../../PHP/conexion_BD.php'; // Aquí se conecta $conexion (MySQLi)

header("Content-Type: application/json");

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT ProductoID, Producto_Nombre, Producto_Codigo, Producto_Descripcion, Producto_Precio, Producto_Stock, Producto_Estado 
          FROM Productos 
          WHERE Producto_Nombre LIKE ?";

$stmt = $conexion->prepare($query);
$param = "%" . $search . "%";
$stmt->bind_param("s", $param);
$stmt->execute();

$result = $stmt->get_result();
$productos = [];

while ($fila = $result->fetch_assoc()) {
    $productos[] = $fila;
}

echo json_encode($productos);
?>
