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
    <title>Editar Producto</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?> <!-- Navbar -->
        <div class="container mt-4">
            <h2>Editar Producto</h2>
            <hr>
            <form id="formEditarProducto" enctype="multipart/form-data">
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

                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>
        <br><?php include '../Layout/footer.php'; ?> <!-- Footer -->
    </div>
    <!-- Script de API Productos -->
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

        document.getElementById('formEditarProducto').addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = e.target;
            let imagenURL = "<?php echo $producto['Producto_Imagen']; ?>"; // imagen actual por defecto

            const archivo = form.Producto_Imagen.files[0];
            if (archivo) {
                const formDataImg = new FormData();
                formDataImg.append('Producto_Imagen', archivo);
                formDataImg.append('Producto_Nombre', form.Producto_Nombre.value);

                const imgResponse = await fetch('../PHP/API/productosImagen.php', {
                    method: 'POST',
                    body: formDataImg
                });

                const imgResult = await imgResponse.json();

                if (!imgResponse.ok) {
                    alert('Error al subir imagen: ' + (imgResult.error || ''));
                    return;
                }

                imagenURL = imgResult.ruta;
            }

            const data = {
                ProductoID: parseInt(form.ProductoID.value),
                Producto_Codigo: form.Producto_Codigo.value,
                Producto_Nombre: form.Producto_Nombre.value,
                Producto_Descripcion: form.Producto_Descripcion.value,
                Producto_Categoria: form.Producto_Categoria.value,
                Producto_Puntaje: parseInt(form.Producto_Puntaje.value),
                Producto_Precio: parseFloat(form.Producto_Precio.value),
                Producto_Stock: parseInt(form.Producto_Stock.value),
                Producto_Estado: form.Producto_Estado.value,
                Producto_Imagen: imagenURL
            };

            const response = await fetch('../PHP/API/productos.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                alert('Producto actualizado correctamente.');
                window.location.href = './productosView.php';
            } else {
                alert('Error: ' + (result.error || 'No se pudo actualizar el producto.'));
            }
        });
    </script>
</body>
</html>
