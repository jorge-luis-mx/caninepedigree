<x-app-layout>
    <h1 class="is-size-4">Sent Requests</h1>

    <div class="container mt-6">
        @if(!empty($send_request) && count($send_request) > 0)

            <input id="searchInput" class="input" type="text" placeholder="Search by name..." onkeyup="filterSentRequests()">
            <div class="table-container">
                <table class="table is-striped is-fullwidth mt-4">
                    <thead>
                        <tr>
                            <th>Female</th>
                            <th>Male</th>
                            <th>Status</th>
                            <th>Request Date</th>
                        </tr>
                    </thead>
                    <tbody id="sentTableBody"></tbody>
                </table>
            </div>
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
            console.log(requestsToShow);
            const tableBody = document.getElementById('sentTableBody');
            tableBody.innerHTML = '';

            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginated = requestsToShow.slice(start, end);

            paginated.forEach(request => {
                const row = document.createElement('tr');

                // Formatear fecha: YYYY-MM-DD
                const date = new Date(request.created_at);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // getMonth() empieza en 0
                const day = String(date.getDate()).padStart(2, '0');
                const formattedDate = `${year}-${month}-${day}`;

                row.innerHTML = `
                    <td>${request.female_dog.alias_dog}</td>
                    <td>${request.male_dog.alias_dog}</td>
                    <td>
                        <span class="tag ${
                            request.status === 'pending' ? 'is-pending' :
                            request.status === 'approved' ? 'is-approved' :
                            request.status === 'rejected' ? 'is-rejected' :
                            request.status === 'cancelled' ? 'is-cancelled' :
                            request.status === 'completed' ? 'is-completed' :
                            'is-light'
                            }">
                            ${request.status}
                        </span>
                    </td>
                    <td>${formattedDate}</td>
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
                req.female_dog.alias_dog.toLowerCase().includes(currentSearch) ||
                req.male_dog.alias_dog.toLowerCase().includes(currentSearch)
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
