<?php
    session_start();
    include 'conexion_BD.php';

    if (isset($_POST['Usuario_Telefono'], $_POST['Usuario_Contraseña'])) {
        $numTel = mysqli_real_escape_string($conexion, $_POST['Usuario_Telefono']);
        $password = mysqli_real_escape_string($conexion, $_POST['Usuario_Contraseña']);

        // Consultar el usuario en la BD
        $query = "SELECT * FROM Usuarios WHERE Usuario_Telefono = '$numTel'";
        $result = mysqli_query($conexion, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            // Validar la contraseña
            if (password_verify($password, $row['Usuario_Contraseña'])) {
                // Guardar datos en la sesión
                $_SESSION['usuario_id'] = $row['UsuarioID']; 
                $_SESSION['usuario_nombre'] = $row['Usuario_Nombre']; 
                $_SESSION['usuario_apellido'] = $row['Usuario_Apellidos']; 
                $_SESSION['usuario_rol'] = $row['Usuario_Rol']; 

                echo '<script>
                        alert("¡Bienvenido ' . $row['Usuario_Nombre'] . ' ' . $row['Usuario_Apellidos'] . '!");
                        window.location = "../HTML/index.php";
                    </script>';
                exit;
            } else {
                echo '<script>
                        alert("Usuario o contraseña incorrectos.");
                        window.location = "../index.php";
                    </script>';
                exit;
            }
        } else {
            echo '<script>
                    alert("Usuario no encontrado.");
                    window.location = "../index.php";
                </script>';
            exit;
        }
    } else {
        echo '<script>
                alert("Por favor, completa todos los campos.");
                window.location = "../index.php";
            </script>';
        exit;
    }

    mysqli_close($conexion);
?>
