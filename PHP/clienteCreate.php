<?php
    include 'conexion_BD.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre     = $_POST['Usuario_Nombre'];
        $apellidos  = $_POST['Usuario_Apellidos'];
        $telefono   = $_POST['Usuario_Telefono'];
        $email      = $_POST['Usuario_Email'];
        $password   = $_POST['Usuario_Contraseña'];
        $genero     = isset($_POST['Usuario_Genero']) ? $_POST['Usuario_Genero'] : null;
        $fechaNac   = isset($_POST['Usuario_FechaNacimiento']) ? $_POST['Usuario_FechaNacimiento'] : null;
        $direccion  = isset($_POST['Usuario_Direccion']) ? $_POST['Usuario_Direccion'] : null;
        $ciudad     = isset($_POST['Usuario_Ciudad']) ? $_POST['Usuario_Ciudad'] : null;
        $estado     = isset($_POST['Usuario_Estado']) ? $_POST['Usuario_Estado'] : null;

        // Validar email existente
        $verificarEmail = mysqli_query($conexion, "SELECT UsuarioID FROM Usuarios WHERE Usuario_Email = '$email'");
        if (mysqli_num_rows($verificarEmail) > 0) {
            echo '
                <script>
                    alert("Este correo ya fue registrado, intenta con otro");
                    window.location = "../index.php";
                </script>
            ';
            exit();
        }

        // Validar teléfono existente
        $verificarTelefono = mysqli_query($conexion, "SELECT UsuarioID FROM Usuarios WHERE Usuario_Telefono = '$telefono'");
        if (mysqli_num_rows($verificarTelefono) > 0) {
            echo '
                <script>
                    alert("Este número de teléfono ya está registrado, intenta con otro");
                    window.location = "../index.php";
                </script>
            ';
            exit();
        }

        // Encriptar contraseña
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Generar tarjeta única con formato 0000-0000-0000-0000
        function generarTarjetaUnica($conexion) {
            do {
                $bloques = [];
                for ($i = 0; $i < 4; $i++) {
                    $bloques[] = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                }
                $tarjeta = implode('-', $bloques);

                $consulta = mysqli_query($conexion, "SELECT UsuarioID FROM Usuarios WHERE Usuario_Tarjeta = '$tarjeta'");
            } while (mysqli_num_rows($consulta) > 0);

            return $tarjeta;
        }

        $tarjetaGenerada = generarTarjetaUnica($conexion);
        $puntosIniciales = 250;

        // Preparar consulta con campos adicionales
        $query = "INSERT INTO Usuarios (
            Usuario_Nombre, Usuario_Apellidos, Usuario_Telefono, Usuario_Email,
            Usuario_Contraseña, Usuario_Genero, Usuario_FechaNacimiento,
            Usuario_Direccion, Usuario_Ciudad, Usuario_Estado,
            Usuario_Tarjeta, Usuario_Puntos
        ) VALUES (
            '$nombre', '$apellidos', '$telefono', '$email',
            '$passwordHash', '$genero', '$fechaNac',
            '$direccion', '$ciudad', '$estado',
            '$tarjetaGenerada', $puntosIniciales
        )";

        $ejecutar = mysqli_query($conexion, $query);

        if ($ejecutar) {
            echo '
            <script>
                alert("Usuario registrado exitosamente.\\nTarjeta asignada: ' . $tarjetaGenerada . '\\nPuntos iniciales: ' . $puntosIniciales . '");
                window.location = "../Admin/clientesView.php";
            </script>
            ';
        } else {
            echo '
            <script>
                alert("Error al registrar usuario. Por favor, intenta de nuevo.");
                window.history.back();
            </script>
            ';
        }

        mysqli_close($conexion);
    } else {
        echo '
            <script>
                alert("Método de solicitud no válido");
                window.history.back();
            </script>
        ';
        exit();
    }
?>
