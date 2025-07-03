<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Administrar Productos</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?> <!-- Navbar -->
        <div class="container mt-4">
            <h2>Administrar Productos</h2><hr>
            <a href="./productosCreate.php" class="btn btn-success mb-3">
                <i class="fas fa-plus"></i> Registrar Producto
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

            <table class="table table-bordered table-striped">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Contenido dinámico por JavaScript -->
                </tbody>
            </table>
            
            <!-- Paginación -->
            <div class="d-flex justify-content-between">
                <div>
                    <p id="pagination-info"></p>
                </div>
                <div>
                    <ul class="pagination">
                        <?php
                            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $items_per_page = isset($_GET['items_per_page']) ? (int)$_GET['items_per_page'] : 10;
                            $search = isset($_GET['search']) ? $_GET['search'] : '';
                            $prev_page = max(1, $current_page - 1);
                            $next_page = $current_page + 1;
                        ?>
                        <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $prev_page; ?>&search=<?php echo $search; ?>&items_per_page=<?php echo $items_per_page; ?>">Anterior</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $next_page; ?>&search=<?php echo $search; ?>&items_per_page=<?php echo $items_per_page; ?>">Siguiente</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <br> <?php include '../Layout/footer.php'; ?> <!-- Footer -->
    </div>
    <!-- Script de API Productos -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const params = new URLSearchParams(window.location.search);
            const search = params.get('search') || '';
            const itemsPerPage = parseInt(params.get('items_per_page')) || 10;
            const currentPage = parseInt(params.get('page')) || 1;
            const offset = (currentPage - 1) * itemsPerPage;

            // Cargar datos paginados
            fetch(`../PHP/API/productos.php?search=${encodeURIComponent(search)}&limit=${itemsPerPage}&offset=${offset}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.querySelector('tbody');
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td colspan="6" class="text-center">No se encontraron resultados</td>`;
                        tbody.appendChild(tr);
                    } else {
                        data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td class="text-center">${row.ProductoID}</td>
                                <td>${row.Producto_Nombre}</td>
                                <td>${row.Producto_Codigo}</td>
                                <td>${row.Producto_Descripcion}</td>
                                <td>${row.Producto_Precio}</td>
                                <td>${row.Producto_Stock}</td>
                                <td class="text-center">${row.Producto_Estado === 'Disponible' ? 'Disponible' : 'Agotado'}</td>
                                <td class="text-center">
                                    <a href="./productosEdit.php?id=${row.ProductoID}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="./productosDelete.php?id=${row.ProductoID}" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                    }
                })
                .catch(err => {
                    console.error('Error al obtener datos de la API:', err);
                });

            // Obtener cantidad total de registros
            fetch(`../PHP/API/productos.php?search=${encodeURIComponent(search)}&count=true`)
                .then(res => res.json())
                .then(data => {
                    const totalRecords = data.total;
                    const totalPages = Math.max(1, Math.ceil(totalRecords / itemsPerPage));
                    const paginationText = document.getElementById('pagination-info');
                    paginationText.textContent = `Mostrando página ${currentPage} de ${totalPages}`;

                    // Actualizar botones de paginación
                    const prevBtn = document.querySelector('.pagination .page-item:first-child');
                    const nextBtn = document.querySelector('.pagination .page-item:last-child');

                    if (currentPage <= 1) {
                        prevBtn.classList.add('disabled');
                    } else {
                        prevBtn.classList.remove('disabled');
                    }

                    if (currentPage >= totalPages) {
                        nextBtn.classList.add('disabled');
                    } else {
                        nextBtn.classList.remove('disabled');
                    }
                })
                .catch(err => {
                    console.error('Error al obtener total de registros:', err);
                });
        });
    </script>
</body>
</html>
