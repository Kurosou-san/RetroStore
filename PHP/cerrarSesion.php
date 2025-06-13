<?php
    session_start();
    session_destroy();  // Destruir la sesión antes de redirigir
    echo '
        <script>
            alert("Cerrando sesión por el Usuario..");
            window.location = "../index.php";  // Redirige al inicio de sesión
        </script>
    ';
?>
