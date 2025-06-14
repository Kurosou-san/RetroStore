<?php
    include 'conexion_BD.php'; // $conexion

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id          = intval($_POST['ProductoID']);
        $codigo      = $_POST['Producto_Codigo'];
        $nombre      = $_POST['Producto_Nombre'];
        $descripcion = $_POST['Producto_Descripcion'] ?? null;
        $categoria   = $_POST['Producto_Categoria'];
        $puntaje     = $_POST['Producto_Puntaje'];
        $precio      = $_POST['Producto_Precio'];
        $stock       = $_POST['Producto_Stock'];
        $estado      = $_POST['Producto_Estado'];

        $imagenRuta = null;
        if (isset($_FILES['Producto_Imagen']) && $_FILES['Producto_Imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = basename($_FILES['Producto_Imagen']['name']);
            $rutaDestino = '../Media/Store/' . $nombreArchivo;

            if (move_uploaded_file($_FILES['Producto_Imagen']['tmp_name'], $rutaDestino)) {
                $imagenRuta = $rutaDestino;
            }
        }

        if ($imagenRuta) {
            $stmt = $conexion->prepare("
                UPDATE Productos SET 
                    Producto_Codigo = ?, 
                    Producto_Nombre = ?, 
                    Producto_Descripcion = ?, 
                    Producto_Categoria = ?, 
                    Producto_Puntaje = ?, 
                    Producto_Precio = ?, 
                    Producto_Stock = ?, 
                    Producto_Imagen = ?, 
                    Producto_Estado = ?
                WHERE ProductoID = ?
            ");

            $stmt->bind_param(
                "ssssidsssi",
                $codigo,
                $nombre,
                $descripcion,
                $categoria,
                $puntaje,
                $precio,
                $stock,
                $imagenRuta,
                $estado,
                $id
            );
        } else {
            $stmt = $conexion->prepare("
                UPDATE Productos SET 
                    Producto_Codigo = ?, 
                    Producto_Nombre = ?, 
                    Producto_Descripcion = ?, 
                    Producto_Categoria = ?, 
                    Producto_Puntaje = ?, 
                    Producto_Precio = ?, 
                    Producto_Stock = ?, 
                    Producto_Estado = ?
                WHERE ProductoID = ?
            ");

            $stmt->bind_param(
                "ssssidssi",
                $codigo,
                $nombre,
                $descripcion,
                $categoria,
                $puntaje,
                $precio,
                $stock,
                $estado,
                $id
            );
        }

        if ($stmt->execute()) {
            echo "<script>
                    alert('Producto actualizado correctamente.');
                    window.location.href = '../Admin/productosView.php';
                </script>";
        } else {
            echo "<script>
                    alert('Error al actualizar el producto: " . $stmt->error . "');
                    window.history.back();
                </script>";
        }

        $stmt->close();
        $conexion->close();
    }
?>
