<?php
    include '../PHP/session.php'; include '../PHP/conexion_BD.php';

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo "<script>alert('ID no válido'); window.location.href = './productosView.php';</script>";
        exit;
    }

    $id = (int)$_GET['id'];

    $stmt = $conexion->prepare("SELECT Producto_Codigo, Producto_Nombre, Producto_Descripcion, Producto_Categoria, Producto_Puntaje,
        Producto_Precio, Producto_Stock, Producto_Estado, Producto_Imagen FROM Productos WHERE ProductoID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Producto no encontrado'); window.location.href = './productosView.php';</script>";
        exit;
    }

    $producto = $result->fetch_assoc();
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Eliminar Producto</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?> <!-- Navbar -->
        <div class="container mt-4">
            <h2>Eliminar Premio</h2><hr>
            <form id="formEliminarProducto">
                <input type="hidden" name="ProductoID" value="<?php echo $id; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Código del Producto</label>
                        <input class="form-control" name="Producto_Codigo" required value="<?php echo htmlspecialchars($producto['Producto_Codigo']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="Producto_Nombre" required value="<?php echo htmlspecialchars($producto['Producto_Nombre']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="Producto_Descripcion" rows="3"><?php echo htmlspecialchars($producto['Producto_Descripcion']); ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Categoría</label>
                        <input class="form-control" name="Producto_Categoria" required value="<?php echo htmlspecialchars($producto['Producto_Categoria']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Puntaje</label>
                        <input class="form-control" type="number" name="Producto_Puntaje" required value="<?php echo htmlspecialchars($producto['Producto_Puntaje']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Precio</label>
                        <input class="form-control" type="number" step="0.01" name="Producto_Precio" required value="<?php echo $producto['Producto_Precio']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stock</label>
                        <input class="form-control" type="number" name="Producto_Stock" required value="<?php echo $producto['Producto_Stock']; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select class="form-control" name="Producto_Estado" required>
                            <option value="Disponible" <?php if ($producto['Producto_Estado'] === 'Disponible') echo 'selected'; ?>>Disponible</option>
                            <option value="Agotado" <?php if ($producto['Producto_Estado'] === 'Agotado') echo 'selected'; ?>>Agotado</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Imagen actual</label>
                        <input class="form-control" type="file" name="Producto_Imagen" accept="image/*" onchange="mostrarVistaPrevia(event)">
                        <img id="preview"
                            src="<?php echo $producto['Producto_Imagen'] ? htmlspecialchars($producto['Producto_Imagen']) : '#'; ?>"
                            alt="Vista previa"
                            style="max-width: 200px; margin-top: 10px; <?php echo $producto['Producto_Imagen'] ? '' : 'display: none;'; ?>">
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-danger" type="submit">
                        <i class="fas fa-trash-alt"></i> Eliminar Producto
                    </button>
                    <a href="./productosView.php" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
        <br> <?php include '../Layout/footer.php'; ?> <!-- Footer -->
    </div>
    <!-- Script de API Premios -->
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

        document.getElementById('formEliminarProducto').addEventListener('submit', async function (e) {
            e.preventDefault();

            const id = parseInt(document.querySelector('input[name="ProductoID"]').value);

            const response = await fetch('../PHP/API/productos.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ProductoID: id })
            });

            const result = await response.json();

            if (response.ok) {
                alert('Producto eliminado correctamente.');
                window.location.href = './productosView.php';
            } else {
                alert('Error: ' + (result.error || 'No se pudo eliminar.'));
            }
        });
    </script>
</body>
</html>