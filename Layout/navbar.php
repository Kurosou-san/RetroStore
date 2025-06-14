<nav class="navbar navbar-expand-lg <?php echo $$navbarClass = 'custom-navbar'; ?>">
    <div class="container">
        <a class="navbar-brand" href="./index.php">
            <img src="../Media/Retro.png" alt="Logo del Programa" width="50px" height="35px" class="d-inline-block align-text-top">
            Retro Store
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="./index.php"><i class="fa-solid fa-house"></i> Inicio</a>
                </li>

                <?php if ($esAdmin): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user-gear"></i> Administrar
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="../Admin/clientesView.php"><i class="fa-solid fa-users"></i> Clientes</a></li>
                            <li><a class="dropdown-item" href="../Admin/productosView.php"><i class="fa-solid fa-box";></i> Productos</a></li>
                            <li><a class="dropdown-item" href="../Admin/premiosView.php"><i class="fa-solid fa-gift"></i> Premios</a></li>
                            <li><a class="dropdown-item" href="../Admin/beneficiosView.php"><i class="fa-solid fa-handshake"></i> Beneficios</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($esCliente): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./perfil.php"><i class="fa-solid fa-user"></i> Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../HTML/productos.php"><i class="fa-solid fa-box"></i> Catalogo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../HTML/carrito.php"><i class="fa-solid fa-cart-shopping"></i> Carrito</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./premios.php"><i class="fa-solid fa-gift"></i> Canjear</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./verBeneficios.php"><i class="fa-solid fa-handshake"></i> Beneficios</a>
                    </li>
                <?php endif; ?>


                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="fa-solid fa-right-from-bracket"></i> Salir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Modal de Confirmación para Cerrar Sesión -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirmar Cierre de Sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas cerrar sesión?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="../PHP/cerrarSesion.php" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</div>
