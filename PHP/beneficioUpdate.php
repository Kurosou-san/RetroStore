<?php
include 'conexion_BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id           = (int)$_POST['BeneficioID'];
    $empresa      = trim($_POST['Empresa_Nombre']);
    $descripcion  = $_POST['Beneficio_Descripcion'] ?? null;
    $activo       = (int)$_POST['Beneficio_Activo'];

    $stmt = $conexion->prepare("
        UPDATE Beneficios 
        SET Empresa_Nombre = ?, Beneficio_Descripcion = ?, Beneficio_Activo = ?
        WHERE BeneficioID = ?
    ");

    $stmt->bind_param("ssii", $empresa, $descripcion, $activo, $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Beneficio actualizado correctamente.');
                window.location.href = '../Admin/beneficiosView.php';
            </script>";
    } else {
        echo "<script>
                alert('Error al actualizar el beneficio: " . $stmt->error . "');
                window.history.back();
            </script>";
    }

    $stmt->close();
    $conexion->close();
}
?>
