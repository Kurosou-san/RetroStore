<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Eliminar Producto</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <!-- Inicio del Código -->
    <div class="wrapper">
        <!-- BARRA DE NAVEGACIÓN -->
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Eliminar Producto</h2><hr>
            <?php
                include '../PHP/conexion_BD.php';

                if (!isset($_GET['id'])) {
                    die("ID de producto no especificado.");
                }

                $productoID = intval($_GET['id']);
                $query = "SELECT * FROM Productos WHERE ProductoID = $productoID";
                $result = mysqli_query($conexion, $query);

                if (!$result || mysqli_num_rows($result) === 0) {
                    die("Producto no encontrado.");
                }

                $row = mysqli_fetch_assoc($result);
            ?>
            <form action="../PHP/productoDelete.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="ProductoID" value="<?php echo $row['ProductoID']; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Código del Producto</label>
                        <input class="form-control" name="Producto_Codigo" readonly value="<?php echo htmlspecialchars($row['Producto_Codigo']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="Producto_Nombre" readonly value="<?php echo htmlspecialchars($row['Producto_Nombre']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="Producto_Descripcion" readonly rows="3"><?php echo htmlspecialchars($row['Producto_Descripcion']); ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Categoría</label>
                        <input class="form-control" name="Producto_Categoria" readonly value="<?php echo htmlspecialchars($row['Producto_Categoria']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Puntaje</label>
                        <input class="form-control" type="number" name="Producto_Puntaje" readonly value="<?php echo htmlspecialchars($row['Producto_Puntaje']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Precio</label>
                        <input class="form-control" type="number" step="0.01" name="Producto_Precio" readonly value="<?php echo $row['Producto_Precio']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stock</label>
                        <input class="form-control" type="number" name="Producto_Stock" readonly value="<?php echo $row['Producto_Stock']; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select class="form-control" name="Producto_Estado" readonly>
                            <option value="Disponible" <?php if ($row['Producto_Estado'] === 'Disponible') echo 'selected'; ?>>Disponible</option>
                            <option value="Agotado" <?php if ($row['Producto_Estado'] === 'Agotado') echo 'selected'; ?>>Agotado</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Imagen</label><br>
                            <?php if ($row['Producto_Imagen']) { ?>
                                <img src="<?php echo '../ruta_a_imagenes/' . htmlspecialchars($row['Producto_Imagen']); ?>" 
                                    alt="Vista previa" 
                                    style="max-width: 200px; margin-top: 10px;">
                            <?php } else { ?>
                                <p>Sin imagen</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-danger" type="submit">
                        <i class="fas fa-trash-alt"></i> Eliminar Producto
                    </button>
                    <a href="../Admin/productosView.php" class="btn btn-secondary ms-2">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <!-- PIE DE PÁGINA -->
        <br> <?php include '../Layout/footer.php'; ?>
    </div>

    <!-- Fin del Código -->
    <!-- Scritps Adicionales -->
    <script>
        function mostrarVistaPrevia(event) {
            const input = event.target;
            const preview = document.getElementById('preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
