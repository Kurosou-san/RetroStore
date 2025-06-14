<?php
    include 'conexion_BD.php'; // $conexion

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = intval($_POST['ProductoID']);

        $stmt = $conexion->prepare("DELETE FROM Productos WHERE ProductoID = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Producto eliminado correctamente.');
                    window.location.href = '../Admin/productosView.php';
                </script>";
        } else {
            echo "<script>
                    alert('Error al eliminar el producto: " . $stmt->error . "');
                    window.history.back();
                </script>";
        }

        $stmt->close();
        $conexion->close();
    }
?>
