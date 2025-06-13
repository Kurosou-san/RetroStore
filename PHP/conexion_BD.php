<?php
    $conexion = mysqli_connect("localhost", "root", "", "RetroStore");

    
    if($conexion) {
        // echo "Conexion exitosa";
    } else {
        echo "Conexion fallida";
    }
    
?>