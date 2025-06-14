<?php
include 'conexion_BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['BeneficioID'];

    $stmt = $conexion->prepare("DELETE FROM Beneficios WHERE BeneficioID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Beneficio eliminado correctamente.');
                window.location.href = '../Admin/beneficiosView.php';
            </script>";
    } else {
        echo "<script>
                alert('Error al eliminar el beneficio: " . $stmt->error . "');
                window.history.back();
            </script>";
    }

    $stmt->close();
    $conexion->close();
}
?>
