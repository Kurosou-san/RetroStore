document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const search = params.get('search') || '';
    const itemsPerPage = parseInt(params.get('items_per_page')) || 10;
    const currentPage = parseInt(params.get('page')) || 1;
    const offset = (currentPage - 1) * itemsPerPage;

    fetch(`../PHP/API/usuarios.php?search=${encodeURIComponent(search)}&limit=${itemsPerPage}&offset=${offset}`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('tbody');
            const paginationInfo = document.getElementById('pagination-info');
            tbody.innerHTML = '';

            if (!Array.isArray(data) || data.length === 0) {
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

            paginationInfo.textContent = `Página ${currentPage}`;
        })
        .catch(err => {
            console.error('Error al obtener datos de la API:', err);
        });
});
