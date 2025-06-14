<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Administrar Premios</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <!-- Inicio del Código -->
    <div class="wrapper">
        <!-- BARRA DE NAVEGACIÓN -->
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Administrar Premios</h2><hr>
            <a href="./premiosCreate.php" class="btn btn-success mb-3">
                <i class="fas fa-plus"></i> Registrar Premio
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
                        <input type="text" name="search" class="form-control" placeholder="Buscar premio" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    </div>
                </div>
            </form>

            <?php
                include '../PHP/conexion_BD.php';

                $items_per_page = isset($_GET['items_per_page']) ? (int)$_GET['items_per_page'] : 10;
                $search = isset($_GET['search']) ? mysqli_real_escape_string($conexion, $_GET['search']) : '';

                $query_count = "SELECT COUNT(*) FROM Premios WHERE Premio_Nombre LIKE '%$search%'";
                $result_count = mysqli_query($conexion, $query_count);
                $total_items = mysqli_fetch_row($result_count)[0];
                $total_pages = ceil($total_items / $items_per_page);

                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($current_page - 1) * $items_per_page;

                $query = "SELECT PremioID, Premio_Nombre, Premio_Descripcion, Premio_PuntosNecesarios, Premio_Disponible 
                          FROM Premios 
                          WHERE Premio_Nombre LIKE '%$search%' 
                          LIMIT $items_per_page OFFSET $offset";
                $result = mysqli_query($conexion, $query);
            ?>

            <table class="table table-bordered table-striped">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Puntos Necesarios</th>
                        <th>Disponible</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td class="text-center"><?php echo $row['PremioID']; ?></td>
                            <td><?php echo $row['Premio_Nombre']; ?></td>
                            <td><?php echo $row['Premio_Descripcion']; ?></td>
                            <td class="text-center"><?php echo $row['Premio_PuntosNecesarios']; ?></td>
                            <td class="text-center"><?php echo $row['Premio_Disponible'] ? 'Sí' : 'No'; ?></td>
                            <td class="text-center">
                                <a href="./premiosEdit.php?id=<?php echo $row['PremioID']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="./premiosDelete.php?id=<?php echo $row['PremioID']; ?>" class="btn btn-sm btn-danger">
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
</body>
</html>
