<?php
session_start();
include '../PHP/conexion_BD.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Verificar si el usuario está logueado
if (isset($_SESSION['usuario_id']) && isset($_POST['idProducto'])) {
    $idUsuario = (int) $_SESSION['usuario_id'];  // ID del usuario logueado
    $idProducto = (int) $_POST['idProducto'];    // ID del producto
    $cantidad = 1;

    // Validar existencia del producto
    $checkProducto = "SELECT Producto_Stock FROM Productos WHERE ProductoID = $idProducto";
    $resProducto = mysqli_query($conexion, $checkProducto);

    if ($resProducto && mysqli_num_rows($resProducto) > 0) {
        $stock = (int) mysqli_fetch_assoc($resProducto)['Producto_Stock'];

        if ($stock > 0) {
            // Verificar si ya está en el carrito
            $query = "SELECT Carrito_Cantidad FROM Carrito WHERE Carrito_UsuarioID = $idUsuario AND Carrito_ProductoID = $idProducto";
            $result = mysqli_query($conexion, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                // Actualizar cantidad (hasta el stock disponible)
                $update = "UPDATE Carrito 
                           SET Carrito_Cantidad = LEAST(Carrito_Cantidad + 1, $stock)
                           WHERE Carrito_UsuarioID = $idUsuario AND Carrito_ProductoID = $idProducto";
                mysqli_query($conexion, $update);
            } else {
                // Insertar nuevo producto en el carrito
                $insert = "INSERT INTO Carrito (Carrito_UsuarioID, Carrito_ProductoID, Carrito_Cantidad) 
                           VALUES ($idUsuario, $idProducto, $cantidad)";
                mysqli_query($conexion, $insert);
            }

            echo '
                <script>
                    alert("¡Producto añadido al carrito!");
                    window.location = "../HTML/carrito.php";
                </script>
            ';
        } else {
            echo '
                <script>
                    alert("El producto está agotado y no se puede añadir al carrito.");
                    window.history.back();
                </script>
            ';
        }
    } else {
        echo '
            <script>
                alert("Producto no encontrado.");
                window.history.back();
            </script>
        ';
    }

} else {
    echo '
        <script>
            alert("Por favor, inicie sesión para agregar productos al carrito.");
            window.location = "../index.php";
        </script>
    ';
}
?>
