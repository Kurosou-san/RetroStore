<?php
session_start();
include '../PHP/conexion_BD.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../HTML/login.php");
    exit;
}

$idUsuario = $_SESSION['usuario_id'];

// Obtener los puntos totales del carrito
$queryPuntos = "
    SELECT SUM(p.Producto_Puntaje * c.Carrito_Cantidad) AS puntosTotales
    FROM Carrito c
    INNER JOIN Productos p ON c.Carrito_ProductoID = p.ProductoID
    WHERE c.Carrito_UsuarioID = '$idUsuario'
";
$resultPuntos = mysqli_query($conexion, $queryPuntos);
$puntosTotales = 0;
if ($resultPuntos && $row = mysqli_fetch_assoc($resultPuntos)) {
    $puntosTotales = (int)$row['puntosTotales'];
}

// Obtener puntos actuales del usuario
$queryUsuario = "SELECT Usuario_Puntos FROM Usuarios WHERE UsuarioID = '$idUsuario'";
$resultUsuario = mysqli_query($conexion, $queryUsuario);
$puntosUsuario = 0;
if ($resultUsuario && $rowU = mysqli_fetch_assoc($resultUsuario)) {
    $puntosUsuario = (int)$rowU['Usuario_Puntos'];
}

// Sumar puntos y actualizar
$nuevosPuntos = $puntosUsuario + $puntosTotales;
$updatePuntos = "UPDATE Usuarios SET Usuario_Puntos = $nuevosPuntos WHERE UsuarioID = '$idUsuario'";
mysqli_query($conexion, $updatePuntos);

// Vaciar carrito del usuario
$deleteCarrito = "DELETE FROM Carrito WHERE Carrito_UsuarioID = '$idUsuario'";
mysqli_query($conexion, $deleteCarrito);

mysqli_close($conexion);

echo '
    <script>
        alert("Compra finalizada. Se agregaron ' . $puntosTotales . ' puntos a la tarjeta.");
        window.location.href = "../HTML/index.php";
    </script>
';
exit;
?>
