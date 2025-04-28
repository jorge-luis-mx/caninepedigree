
<x-app-layout>




   <div class="container">
        
        @if(!empty($breedings))


        <h2 class="title">Solicitudes de Cruza Pendientes</h2>

        <input id="searchInput" class="input is-small" type="text" placeholder="Buscar por nombre..." onkeyup="filterBreedings()">

        <table class="table is-striped is-fullwidth mt-4">
            <thead>
                <tr>
                    <th>Perra</th>
                    <th>Tu Perro</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="breedingTableBody"></tbody>
        </table>

        <div id="pagination" class="mt-4"></div>

        
        
        <!--         
        <div class="table-container mt-5">
            <table class="table is-fullwidth is-striped is-hoverable is-bordered">

                <tbody id="dogTableBody">
                    
                </tbody>
            </table>
        </div>
        <div id="pagination" class="mt-4 has-text-centered"></div> -->

        @else

        <div class="columns is-multiline">
            <div class="column mt-4">
                No dogs found for this profile.
            </div>
        </div>
        @endif

    </div>

   @push('scripts')

   <script>
    let breedings = @json($breedings);

    let currentPage = 1;
    const breedingsPerPage = 10;
    let currentSearchTerm = '';

    function populateTable(breedingsToDisplay) {
        const tableBody = document.getElementById('breedingTableBody');
        tableBody.innerHTML = '';

        const start = (currentPage - 1) * breedingsPerPage;
        const end = start + breedingsPerPage;
        const paginatedBreedings = breedingsToDisplay.slice(start, end);

        paginatedBreedings.forEach((breeding) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${breeding.female_dog.name}</td>
                <td>${breeding.male_dog.name}</td>
                <td><button class="button is-success is-small" onclick="completeBreeding(${breeding.request_id})">Completar Cruza</button></td>
            `;
            tableBody.appendChild(row);
        });

        renderPagination(breedingsToDisplay);
    }

    function renderPagination(breedingsList) {
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';

        const totalPages = Math.ceil(breedingsList.length / breedingsPerPage);

        if (totalPages <= 1) return;

        // Generar botones...
        // [Puedes reutilizar tu lógica anterior aquí]
    }

    window.filterBreedings = function() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const filtered = breedings.filter(b =>
            b.female_dog.name.toLowerCase().includes(search) ||
            b.male_dog.name.toLowerCase().includes(search)
        );
        populateTable(filtered);
    };

    window.completeBreeding = function(requestId) {
        if (confirm('¿Deseas completar esta cruza?')) {
            fetch(`/breeding/complete/${requestId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cruza completada con éxito');
                    breedings = breedings.filter(b => b.request_id !== requestId);
                    populateTable(breedings);
                } else {
                    alert('Error al completar la cruza');
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        populateTable(breedings);
    });
</script>

   @endpush

</x-app-layout>