<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Administrar Clientes</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Administrar Clientes</h2><hr>
            <a href="./clientesCreate.php" class="btn btn-success mb-3">
                <i class="fas fa-plus"></i> Registrar Cliente
            </a>

            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <span class="me-2">Mostrando</span>
                            <select name="items_per_page" class="form-select w-auto me-2" onchange="this.form.submit()">
                                <option value="10" <?php echo isset($_GET['items_per_page']) && $_GET['items_per_page'] == 10 ? 'selected' : ''; ?>>10</option>
                                <option value="25" <?php echo isset($_GET['items_per_page']) && $_GET['items_per_page'] == 25 ? 'selected' : ''; ?>>25</option>
                                <option value="50" <?php echo isset($_GET['items_per_page']) && $_GET['items_per_page'] == 50 ? 'selected' : ''; ?>>50</option>
                            </select>
                            <span class="ms-2">por página</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Buscar cliente" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>
            </form>

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
                    <!-- Contenido dinámico por JavaScript -->
                </tbody>
            </table>

            <?php
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $items_per_page = isset($_GET['items_per_page']) ? (int)$_GET['items_per_page'] : 10;
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $prev_page = max(1, $current_page - 1);
                $next_page = $current_page + 1;

                // Inicializamos total_pages en 1, se actualizará con JS luego
                $total_pages = 1;
            ?>

            <!-- Paginación -->
            <div class="d-flex justify-content-between">
                <div>
                    <p id="pagination-text">Mostrando 1 de 1 páginas</p>
                </div>
                <div>
                    <ul class="pagination">
                        <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $prev_page; ?>&search=<?php echo urlencode($search); ?>&items_per_page=<?php echo $items_per_page; ?>">Anterior</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $next_page; ?>&search=<?php echo urlencode($search); ?>&items_per_page=<?php echo $items_per_page; ?>">Siguiente</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <br> <?php include '../Layout/footer.php'; ?>
    </div>

    <!-- Script para consumir la API -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const params = new URLSearchParams(window.location.search);
            const search = params.get('search') || '';
            const itemsPerPage = parseInt(params.get('items_per_page')) || 10;
            const currentPage = parseInt(params.get('page')) || 1;
            const offset = (currentPage - 1) * itemsPerPage;

            // Cargar datos paginados
            fetch(`../PHP/API/clientes.php?search=${encodeURIComponent(search)}&limit=${itemsPerPage}&offset=${offset}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.querySelector('tbody');
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td colspan="5" class="text-center">No se encontraron resultados</td>`;
                        tbody.appendChild(tr);
                    } else {
                        data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td class="text-center">${row.UsuarioID}</td>
                                <td>${row.Usuario_Nombre} ${row.Usuario_Apellidos}</td>
                                <td>${row.Usuario_Email}</td>
                                <td>${row.Usuario_Telefono}</td>
                                <td class="text-center">
                                    <a href="./clientesEdit.php?id=${row.UsuarioID}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="./clientesDelete.php?id=${row.UsuarioID}" class="btn btn-sm btn-danger">
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
            fetch(`../PHP/API/clientes.php?search=${encodeURIComponent(search)}&count=true`)
                .then(res => res.json())
                .then(data => {
                    const totalRecords = data.total;
                    const totalPages = Math.max(1, Math.ceil(totalRecords / itemsPerPage));
                    const paginationText = document.getElementById('pagination-text');
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
