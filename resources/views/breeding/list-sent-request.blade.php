<x-app-layout>
    <h1 class="is-size-4">Sent Requests</h1>

    <div class="container mt-6">
        @if(!empty($send_request) && count($send_request) > 0)

            <input id="searchInput" class="input" type="text" placeholder="Search by name..." onkeyup="filterSentRequests()">

            <table class="table is-striped is-fullwidth mt-4">
                <thead>
                    <tr>
                        <th>Female Dog</th>
                        <th>Your Male Dog</th>
                    </tr>
                </thead>
                <tbody id="sentTableBody"></tbody>
            </table>

            <div id="pagination" class="mt-4"></div>

        @else
            <div class="columns is-multiline">
                <div class="column mt-4">
                    No sent requests found.
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        let sentRequests = @json($send_request);
        let currentPage = 1;
        const itemsPerPage = 10;
        let currentSearch = '';

        function populateSentTable(requestsToShow) {
            const tableBody = document.getElementById('sentTableBody');
            tableBody.innerHTML = '';

            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginated = requestsToShow.slice(start, end);

            paginated.forEach(request => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${request.female_dog.name}</td>
                    <td>${request.male_dog.name}</td>
                `;
                tableBody.appendChild(row);
            });

            renderPagination(requestsToShow);
        }

        function renderPagination(list) {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';

            const totalPages = Math.ceil(list.length / itemsPerPage);

            if (totalPages <= 1) return;

            for (let i = 1; i <= totalPages; i++) {
                const button = document.createElement('button');
                button.className = 'button is-small mx-1';
                button.innerText = i;
                if (i === currentPage) button.classList.add('is-info');

                button.addEventListener('click', () => {
                    currentPage = i;
                    populateSentTable(filteredRequests());
                });

                paginationContainer.appendChild(button);
            }
        }

        function filteredRequests() {
            return sentRequests.filter(req =>
                req.female_dog.name.toLowerCase().includes(currentSearch) ||
                req.male_dog.name.toLowerCase().includes(currentSearch)
            );
        }

        window.filterSentRequests = function () {
            currentSearch = document.getElementById('searchInput').value.toLowerCase();
            currentPage = 1;
            populateSentTable(filteredRequests());
        };

        document.addEventListener('DOMContentLoaded', () => {
            populateSentTable(sentRequests);
        });
    </script>
    @endpush
</x-app-layout>
