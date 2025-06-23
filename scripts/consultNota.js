document.addEventListener("DOMContentLoaded", function () {
    // Función para cargar todas las notas de venta
    function loadNotes(filter = {}) {
        fetch('consult-datanote.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(filter)
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                displayNotes(data);
            })
            .catch(error => console.error('Error:', error));
    }

    // Función para mostrar las notas de venta en la tabla
    function displayNotes(notes) {
        const tableBody = document.getElementById('notesTableBody');
        tableBody.innerHTML = ''; // Limpiar la tabla antes de insertar nuevas filas

        if (notes.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="4">No se encontraron notas de venta</td></tr>';
            return;
        }

        notes.forEach(note => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${note.note_name}</td>
                <td>${note.client_name}</td>
                <td>${note.folio}</td>
                <td>${note.date}</td>
                <td class="text-center"><button id="follow-up" class="btn btn-success" onclick="window.location.href='seguimiento-nota.php?folio=${note.folio}'">Continuar</button></td>
                `;

            tableBody.appendChild(row);
        });
    }

    // Función para aplicar filtros de búsqueda
    function searchNotes() {
        const clientName = document.getElementById('clientName')?.value.trim() || '';
        const folio = document.getElementById('folio')?.value.trim() || '';
        const noteName = document.getElementById('noteName')?.value.trim() || '';
        const date = document.getElementById('date')?.value.trim() || '';

        const filter = {};
        if (clientName) filter.client_name = clientName;
        if (folio) filter.folio = folio;
        if (noteName) filter.note_name = noteName;
        if (date) filter.date = date;

        loadNotes(filter);
    }

    // Asignar evento al botón de búsqueda
    document.getElementById('searchBtn').addEventListener('click', searchNotes);

    // Cargar todas las notas de venta cuando se carga la página
    loadNotes();
});
