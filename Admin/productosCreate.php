<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Crear Producto</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?> <!-- Navbar -->
        <div class="container mt-4">
            <h2>Crear Producto</h2><hr>
            <form id="productoForm" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Código del Producto</label>
                        <input class="form-control" name="Producto_Codigo" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="Producto_Nombre" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="Producto_Descripcion" rows="3"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Categoría</label>
                        <input class="form-control" name="Producto_Categoria" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Puntaje</label>
                        <input class="form-control" type="number" name="Producto_Puntaje" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Precio</label>
                        <input class="form-control" type="number" step="0.01" name="Producto_Precio" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stock</label>
                        <input class="form-control" type="number" name="Producto_Stock" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select class="form-control" name="Producto_Estado" required>
                            <option value="Disponible">Disponible</option>
                            <option value="Agotado">Agotado</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Imagen</label>
                        <input class="form-control" type="file" name="Producto_Imagen" accept="image/*" onchange="mostrarVistaPrevia(event)">
                        <img id="preview" src="#" alt="Vista previa" style="max-width: 200px; display: none; margin-top: 10px;">
                    </div>
                </div>

                <button class="btn btn-secondary" type="submit">
                    <i class="fas fa-save"></i> Guardar Producto
                </button>
            </form>
        </div>
        <br> <?php include '../Layout/footer.php'; ?> <!-- Footer-->
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

        document.getElementById('productoForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;
            const archivo = form.Producto_Imagen.files[0];
            let imagenURL = null;

            // Subir imagen si existe
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

            // Enviar datos para crear producto
            const data = {
                Producto_Codigo: form.Producto_Codigo.value,
                Producto_Nombre: form.Producto_Nombre.value,
                Producto_Descripcion: form.Producto_Descripcion.value,
                Producto_Categoria: form.Producto_Categoria.value,
                Producto_Puntaje: form.Producto_Puntaje.value,
                Producto_Precio: form.Producto_Precio.value,
                Producto_Stock: form.Producto_Stock.value,
                Producto_Estado: form.Producto_Estado.value,
                Producto_Imagen: imagenURL 
            };

            const response = await fetch('../PHP/API/productos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                alert('Producto creado correctamente.');
                window.location.href = './productosView.php';
            } else {
                alert('Error: ' + (result.error || 'No se pudo crear el producto.'));
            }
        });
    </script>
</body>
</html>
