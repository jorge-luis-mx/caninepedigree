<x-app-layout>

<h1 class="is-size-4">Manage Request</h1>

<div class="container mt-6">
    

    <input id="searchInput" class="input" type="text" placeholder="Buscar por nombre..." onkeyup="filterBreedings()">

    <table class="table is-striped is-fullwidth mt-4">
        <thead>
            <tr>
                <th>Female Dog</th>
                <th>Your Male Dog</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="breedingTableBody"></tbody>
    </table>

    <div id="pagination" class="mt-4"></div>
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
                <td>
                    <a href="/breeding/${breeding.request_id}/upload-photos" class="button is-link is-small has-text-white">
                        Upload Breeding Photos 
                    </a>
                </td>
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

        for (let page = 1; page <= totalPages; page++) {
            const button = document.createElement('button');
            button.className = 'button is-small mx-1';
            button.innerText = page;
            button.onclick = () => {
                currentPage = page;
                populateTable(breedingsList);
            };
            paginationContainer.appendChild(button);
        }
    }

    window.filterBreedings = function() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const filtered = breedings.filter(b =>
            b.female_dog.name.toLowerCase().includes(search) ||
            b.male_dog.name.toLowerCase().includes(search)
        );
        populateTable(filtered);
    };

    document.addEventListener('DOMContentLoaded', () => {
        populateTable(breedings);
    });
</script>
@endpush

</x-app-layout>