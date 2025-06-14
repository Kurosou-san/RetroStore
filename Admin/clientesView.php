<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Administrar Clientes</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <!-- Inicio del Código -->
    <div class="wrapper">
        <!-- BARRA DE NAVEGACIÓN -->
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Administrar Clientes</h2><hr>
            <a href="./clientesCreate.php" class="btn btn-success mb-3">
                <i class="fas fa-plus"></i> Registrar Cliente
            </a>

            <!-- Formulario para buscar y seleccionar el número de elementos por página -->
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <span class="me-2">Mostrando</span>
                            <select name="items_per_page" class="form-select w-auto me-2">
                                <option value="10" <?php echo isset($_GET['items_per_page']) && $_GET['items_per_page'] == 10 ? 'selected' : ''; ?>>10</option>
                                <option value="25" <?php echo isset($_GET['items_per_page']) && $_GET['items_per_page'] == 25 ? 'selected' : ''; ?>>25</option>
                                <option value="50" <?php echo isset($_GET['items_per_page']) && $_GET['items_per_page'] == 50 ? 'selected' : ''; ?>>50</option>
                            </select>
                            <span class="ms-2">por página</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Buscar producto" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    </div>
                </div>
            </form>

            <?php
                include '../PHP/conexion_BD.php';

                $items_per_page = isset($_GET['items_per_page']) ? (int)$_GET['items_per_page'] : 10;
                $search = isset($_GET['search']) ? mysqli_real_escape_string($conexion, $_GET['search']) : '';

                $query_count = "SELECT COUNT(*) FROM Usuarios WHERE CONCAT(Usuario_Nombre, ' ', Usuario_Apellidos) LIKE '%$search%'";
                $result_count = mysqli_query($conexion, $query_count);
                $total_items = mysqli_fetch_row($result_count)[0];
                $total_pages = ceil($total_items / $items_per_page);

                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($current_page - 1) * $items_per_page;

                $query = "SELECT UsuarioID, Usuario_Nombre, Usuario_Apellidos, Usuario_Email, Usuario_Telefono 
                        FROM Usuarios 
                        WHERE CONCAT(Usuario_Nombre, ' ', Usuario_Apellidos) LIKE '%$search%' 
                        LIMIT $items_per_page OFFSET $offset";
                $result = mysqli_query($conexion, $query);
            ?>

            <table class="table table-bordered table-striped">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td class="text-center"><?php echo $row['UsuarioID']; ?></td>
                            <td><?php echo $row['Usuario_Nombre'] . ' ' . $row['Usuario_Apellidos']; ?></td>
                            <td><?php echo $row['Usuario_Email']; ?></td>
                            <td class="text-center"><?php echo $row['Usuario_Telefono']; ?></td>
                            <td class="text-center">
                                <a href="./clientesEdit.php?id=<?php echo $row['UsuarioID']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="./clientesDelete.php?id=<?php echo $row['UsuarioID']; ?>" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="d-flex justify-content-between">
                <div>
                    <p>Mostrando <?php echo $current_page; ?> de <?php echo $total_pages; ?> páginas</p>
                </div>
                <div>
                    <ul class="pagination">
                        <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&search=<?php echo $search; ?>&items_per_page=<?php echo $items_per_page; ?>">Anterior</a>
                        </li>
                        <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&search=<?php echo $search; ?>&items_per_page=<?php echo $items_per_page; ?>">Siguiente</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- PIE DE PÁGINA -->
        <br> <?php include '../Layout/footer.php'; ?>
    </div>

    <!-- Fin del Código -->
    <!-- Scritps Adicionales -->
</body>
</html>
