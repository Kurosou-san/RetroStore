<?php
    include '../PHP/conexion_BD.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!$conexion) {
        die("Conexión fallida: " . mysqli_connect_error());
    }
?>

<div class="container mt-4">
    <?php
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];

            if ($id > 0) {
                // Consultar producto sin JOIN
                $sql = "
                    SELECT 
                        ProductoID, 
                        Producto_Nombre, 
                        Producto_Descripcion, 
                        Producto_Precio, 
                        Producto_Stock, 
                        Producto_Imagen, 
                        Producto_Categoria,
                        Producto_Puntaje 
                    FROM Productos
                    WHERE ProductoID = $id
                ";
                $resultado = $conexion->query($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    $producto = $resultado->fetch_assoc();

                    if (!isset($producto['Producto_Imagen'])) {
                        die("Los datos del producto están incompletos.");
                    }

                    $categoriaNombre = $producto['Producto_Categoria'];

                    echo '<nav aria-label="breadcrumb">';
                    echo '<ol class="breadcrumb">';
                    echo '<li class="breadcrumb-item"><a href="productos.php">Inicio</a></li>';
                    echo '<li class="breadcrumb-item"><a href="productos.php?categoria=' . urlencode($categoriaNombre) . '">' . htmlspecialchars($categoriaNombre) . '</a></li>';
                    echo '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($producto['Producto_Nombre']) . '</li>';
                    echo '</ol>';
                    echo '</nav>';
    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <img src="<?php echo htmlspecialchars($producto['Producto_Imagen']); ?>" class="img-fluid rounded mb-3" alt="<?php echo htmlspecialchars($producto['Producto_Nombre']); ?>">
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title"><?php echo htmlspecialchars($producto['Producto_Nombre']); ?></h3>
                                    <p class="card-text"><strong>Precio:</strong> $<?php echo number_format($producto['Producto_Precio'], 2); ?></p>
                                    <p class="card-text"><strong>Puntaje:</strong>  <?php echo number_format($producto['Producto_Puntaje']); ?></p>
                                    <p class="card-text"><strong>Descripción:</strong> <?php echo htmlspecialchars($producto['Producto_Descripcion']); ?></p>

                                    <?php if ($producto['Producto_Stock'] > 0) { ?>
                                        <p class="card-text text-success"><strong>Producto Disponible</strong></p>
                                        <p class="card-text"><strong>Cantidad:</strong> <?php echo $producto['Producto_Stock']; ?> unidad(es)</p>
                                    <?php } else { ?>
                                        <p class="card-text text-danger"><strong>Producto no disponible</strong></p>
                                    <?php } ?>

                                    <?php if (isset($_SESSION['usuario_id'])) { ?>
                                        <form action="../PHP/agregarCarrito.php" method="POST">
                                            <input type="hidden" name="idProducto" value="<?php echo $producto['ProductoID']; ?>">
                                            <input type="hidden" name="idUsuario" value="<?php echo $_SESSION['usuario_id']; ?>">
                                            <div class="d-flex gap-2 mt-3">
                                                <a href="comprarProducto.php?id=<?php echo $producto['ProductoID']; ?>" class="btn btn-primary">
                                                    <i class="fas fa-shopping-cart me-2"></i> Comprar Ahora
                                                </a>
                                                <button type="submit" class="btn btn-secondary">
                                                    <i class="fas fa-cart-plus me-2"></i> Añadir al Carrito
                                                </button>
                                            </div>
                                        </form>
                                        <div class="mt-4">
                                            <p class="text-muted">
                                                <strong>Devolución gratis</strong><br>
                                                Tienes 30 días desde que lo recibes.<br>
                                                <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#devolucionModal">Conocer más</a>
                                            </p>
                                        </div>
                                    <?php } else { ?>
                                        <p class="text-danger">Por favor, inicia sesión para comprar o añadir al carrito.</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h4>Productos similares</h4>
                        <div class="row">
                            <?php
                            $categoriaNombreEscapada = $conexion->real_escape_string($categoriaNombre);
                            $sqlSimilares = "
                                SELECT ProductoID, Producto_Nombre, Producto_Precio, Producto_Imagen
                                FROM Productos
                                WHERE Producto_Categoria = '$categoriaNombreEscapada' AND ProductoID != $id
                                ORDER BY RAND() LIMIT 4
                            ";
                            $resultadoSimilares = $conexion->query($sqlSimilares);

                            if ($resultadoSimilares && $resultadoSimilares->num_rows > 0) {
                                while ($similar = $resultadoSimilares->fetch_assoc()) {
                            ?>
                                    <div class="col-md-3 mb-4">
                                        <div class="card h-100 shadow-sm">
                                            <img src="<?php echo htmlspecialchars($similar['Producto_Imagen']); ?>" class="card-img-top img-fluid" alt="<?php echo htmlspecialchars($similar['Producto_Nombre']); ?>">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title text-center mb-3"><?php echo htmlspecialchars($similar['Producto_Nombre']); ?></h5>
                                                <p class="card-text text-center text-muted">$<?php echo number_format($similar['Producto_Precio'], 2); ?></p>
                                                <a href="verProducto.php?id=<?php echo $similar['ProductoID']; ?>" class="btn btn-primary mt-auto">Ver detalles</a>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "<p class='text-center text-muted'>No hay productos similares disponibles.</p>";
                            }
                            ?>
                        </div>
                    </div>
    <?php
                } else {
                    echo "<p class='text-center'>Producto no encontrado.</p>";
                }
            } else {
                echo "<p class='text-center'>ID de producto no válido.</p>";
            }
        } else {
            echo "<p class='text-center'>ID de producto no especificado.</p>";
        }

        $conexion->close();
    ?>
</div>
