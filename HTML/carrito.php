<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Carrito</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <?php
            include '../PHP/conexion_BD.php';

            if (isset($_SESSION['usuario_id'])) {
                $idUsuario = $_SESSION['usuario_id'];

                // Procesar actualización de cantidad
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProducto'], $_POST['cantidad'])) {
                    $idProducto = (int)$_POST['idProducto'];
                    $nuevaCantidad = (int)$_POST['cantidad'];

                    if ($nuevaCantidad == 0) {
                        $deleteQuery = "
                            DELETE FROM Carrito 
                            WHERE Carrito_UsuarioID = $idUsuario AND Carrito_ProductoID = $idProducto
                        ";
                        mysqli_query($conexion, $deleteQuery);
                    } else {
                        $nuevaCantidad = max(1, $nuevaCantidad);
                        $updateQuery = "
                            UPDATE Carrito 
                            SET Carrito_Cantidad = $nuevaCantidad 
                            WHERE Carrito_UsuarioID = $idUsuario AND Carrito_ProductoID = $idProducto
                        ";
                        mysqli_query($conexion, $updateQuery);
                    }
                }

                // Procesar compra (botón Continuar compra)
                if (isset($_GET['comprar']) && $_GET['comprar'] == '1') {
                    // Obtener puntos totales de los productos en carrito
                    $puntosQuery = "
                        SELECT SUM(p.Producto_Puntaje * c.Carrito_Cantidad) AS totalPuntos
                        FROM Carrito c
                        INNER JOIN Productos p ON c.Carrito_ProductoID = p.ProductoID
                        WHERE c.Carrito_UsuarioID = $idUsuario
                    ";
                    $resPuntos = mysqli_query($conexion, $puntosQuery);
                    $totalPuntos = 0;
                    if ($resPuntos && $row = mysqli_fetch_assoc($resPuntos)) {
                        $totalPuntos = (int)$row['totalPuntos'];
                    }

                    // Actualizar puntos en Usuarios
                    $updatePuntos = "
                        UPDATE Usuarios
                        SET Usuario_Puntos = Usuario_Puntos + $totalPuntos
                        WHERE UsuarioID = $idUsuario
                    ";
                    mysqli_query($conexion, $updatePuntos);

                    // Vaciar carrito
                    $vaciarCarrito = "
                        DELETE FROM Carrito WHERE Carrito_UsuarioID = $idUsuario
                    ";
                    mysqli_query($conexion, $vaciarCarrito);

                    echo "
                        <script>
                            alert('Compra finalizada. Se agregaron $totalPuntos puntos a la tarjeta.');
                            window.location.href = 'carrito.php';
                        </script>
                    ";
                    exit;
                }

                // Consulta para mostrar carrito
                $query = "
                    SELECT 
                        p.ProductoID,
                        p.Producto_Nombre, 
                        p.Producto_Imagen, 
                        p.Producto_Precio, 
                        p.Producto_Puntaje,
                        c.Carrito_Cantidad,
                        (p.Producto_Precio * c.Carrito_Cantidad) AS precioTotal,
                        (p.Producto_Puntaje * c.Carrito_Cantidad) AS puntosTotal
                    FROM 
                        Carrito c
                    INNER JOIN 
                        Productos p ON c.Carrito_ProductoID = p.ProductoID
                    WHERE 
                        c.Carrito_UsuarioID = $idUsuario
                ";
                $resultado = mysqli_query($conexion, $query);

                if (mysqli_num_rows($resultado) > 0) {
                    $totalCarrito = 0;
                    $totalPuntosCarrito = 0;
            ?>
                    <h2 class="text-center">Carrito de Compras</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Ilustración</th>
                                    <th>Precio Individual</th>
                                    <th>Puntaje por Unidad</th>
                                    <th>Cantidad</th>
                                    <th>Precio Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="carrito-tabla">
                                <?php
                                while ($producto = mysqli_fetch_assoc($resultado)) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($producto['Producto_Nombre']) . '</td>';
                                    echo '<td><img src="' . htmlspecialchars($producto['Producto_Imagen']) . '" class="img-fluid" style="width: 100px;" alt="' . htmlspecialchars($producto['Producto_Nombre']) . '"></td>';
                                    echo '<td>$' . number_format($producto['Producto_Precio'], 2) . '</td>';
                                    echo '<td>' . number_format($producto['Producto_Puntaje']) . '</td>';
                                    echo '<td>' . $producto['Carrito_Cantidad'] . '</td>';
                                    echo '<td>$' . number_format($producto['precioTotal'], 2) . '</td>';
                                    echo '<td>
                                        <form method="POST" class="d-flex gap-1">
                                            <input type="hidden" name="idProducto" value="' . $producto['ProductoID'] . '">
                                            <button type="submit" name="cantidad" value="' . ($producto['Carrito_Cantidad'] - 1) . '" class="btn btn-sm btn-danger" title="Disminuir cantidad">-</button>
                                            <span class="px-2 align-self-center">' . $producto['Carrito_Cantidad'] . '</span>
                                            <button type="submit" name="cantidad" value="' . ($producto['Carrito_Cantidad'] + 1) . '" class="btn btn-sm btn-success" title="Aumentar cantidad">+</button>
                                        </form>
                                    </td>';
                                    echo '</tr>';

                                    $totalCarrito += $producto['precioTotal'];
                                    $totalPuntosCarrito += $producto['puntosTotal'];
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Total:</strong></td>
                                    <td>$<?php echo number_format($totalCarrito, 2); ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Total Puntaje:</strong></td>
                                    <td><?php echo number_format($totalPuntosCarrito); ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="carrito.php?comprar=1" class="btn btn-primary">
                            <i class="fas fa-shopping-cart"></i> Continuar compra
                        </a>
                    </div>

            <?php
                } else {
                    echo '
                        <h2 class="text-center">Carrito de Compras</h2>
                        <p class="text-center">No tienes productos en tu carrito.</p>
                    ';
                }
            } else {
                echo '
                    <h2 class="text-center">Carrito de Compras</h2>
                    <p class="text-center">Por favor, inicia sesión para ver tu carrito.</p>
                ';
            }

            mysqli_close($conexion);
            ?>
        </div>

        <br> <?php include '../Layout/footer.php'; ?>
    </div>

    <script>
        function comprar() {
            if (confirm("¿Desea finalizar la compra?")) {
                window.location.href = "../PHP/procesarCompra.php";
            }
        }
    </script>
</body>
</html>
