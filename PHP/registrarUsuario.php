<?php
    include 'conexion_BD.php';

    // Verificar que el formulario fue enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener datos del formulario
        $nombre     = $_POST['Usuario_Nombre'];
        $apellidos  = $_POST['Usuario_Apellidos'];
        $telefono   = $_POST['Usuario_Telefono'];
        $email      = $_POST['Usuario_Email'];
        $password   = $_POST['Usuario_Contraseña'];

        // Validar si el correo ya existe
        $verificarEmail = mysqli_query($conexion, "SELECT * FROM Usuarios WHERE Usuario_Email='$email'");
        if (mysqli_num_rows($verificarEmail) > 0) {
            echo '
                <script>
                    alert("Este correo ya fue registrado, intenta con otro");
                    window.location = "../index.php";
                </script>
            ';
            exit();
        }

        // Validar si el teléfono ya existe
        $verificarTelefono = mysqli_query($conexion, "SELECT * FROM Usuarios WHERE Usuario_Telefono='$telefono'");
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

        // Función para generar tarjeta única con formato 0000-0000-0000-0000
        function generarTarjetaUnica($conexion) {
            do {
                $bloques = [];
                for ($i = 0; $i < 4; $i++) {
                    $bloques[] = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                }
                $tarjeta = implode('-', $bloques);

                // Verificar que no exista ya esa tarjeta
                $consulta = mysqli_query($conexion, "SELECT UsuarioID FROM Usuarios WHERE Usuario_Tarjeta = '$tarjeta'");
            } while (mysqli_num_rows($consulta) > 0);

            return $tarjeta;
        }

        // Generar la tarjeta única
        $tarjetaGenerada = generarTarjetaUnica($conexion);

        // Saldo inicial de puntos
        $puntosIniciales = 250;

        // Consulta para insertar nuevo usuario con tarjeta y puntos
        $query = "INSERT INTO Usuarios 
            (Usuario_Nombre, Usuario_Apellidos, Usuario_Telefono, Usuario_Email, Usuario_Contraseña, Usuario_Tarjeta, Usuario_Puntos, Usuario_Rol) 
            VALUES 
            ('$nombre', '$apellidos', '$telefono', '$email', '$passwordHash', '$tarjetaGenerada', $puntosIniciales, 'Cliente')";

        $ejecutar = mysqli_query($conexion, $query);

        if ($ejecutar) {
            echo '
            <script>
                alert("Usuario registrado exitosamente.\\nTarjeta asignada: ' . $tarjetaGenerada . '\\nSaldo inicial: ' . $puntosIniciales . ' puntos.");
                window.location = "../Admin/index.php";
            </script>
            ';
        } else {
            echo '
            <script>
                alert("Error al almacenar usuario, intentar de nuevo...");
                window.location = "../Admin/index.php";
            </script>
            ';
        }

        mysqli_close($conexion);
    } else {
        echo '
            <script>
                alert("Método de solicitud no válido");
                window.location = "../Admin/index.php";
            </script>
        ';
        exit();
    }
?>
