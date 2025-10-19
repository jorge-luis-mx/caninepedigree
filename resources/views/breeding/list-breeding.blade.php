
<x-app-layout>


    <h1 class="is-size-4">Complete Breeding</h1>

   <div class="container mt-6">
        
        @if(!empty($breedings))

            <input id="searchInput" class="input " type="text" placeholder="Buscar por nombre..." onkeyup="filterBreedings()">
            <table class="table is-striped is-fullwidth mt-4">
                <thead>
                    <tr>
                        <th>Female</th>
                        <th>Male</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="breedingTableBody"></tbody>
            </table>
            <div id="pagination" class="mt-4"></div>

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
                <td>
                    <button class="button is-success is-small has-text-white" onclick="completeBreeding(${breeding.request_id},'completed')">
                        Complete Breeding
                    </button>
                    <button class="button is-danger is-small has-text-white" onclick="completeBreeding(${breeding.request_id},'cancelled')">
                        Cancel Breeding
                    </button>
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
    }

    window.filterBreedings = function() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const filtered = breedings.filter(b =>
            b.female_dog.name.toLowerCase().includes(search) ||
            b.male_dog.name.toLowerCase().includes(search)
        );
        populateTable(filtered);
    };

    // window.completeBreeding = function(requestId) {
    //     if (confirm('¿Deseas completar esta cruza?')) {
    //         fetch(`/breeding/complete/${requestId}`, {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //             }
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {

    //                 Swal.fire({
    //                     title: 'Breeding Completed!',
    //                     text: 'The breeding request has been successfully registered.',
    //                     icon: 'success',
    //                     confirmButtonText: 'Got it',
    //                     confirmButtonColor: '#28a745',
    //                     background: '#f0fff5',
    //                     iconColor: '#28a745',
    //                     allowOutsideClick: false,
    //                     backdrop: true,
    //                     timer: 3500,
    //                     timerProgressBar: true
    //                 });

                    
    //                 breedings = breedings.filter(b => b.request_id !== requestId);
    //                 populateTable(breedings);

    //             } else {

    //             Swal.fire({
    //                 title: 'Oops!',
    //                 text: data.message || 'Something went wrong. Please try again.',
    //                 icon: 'warning',
    //                 confirmButtonText: 'Understood',
    //                 confirmButtonColor: '#e6a100', // warm yellow tone
    //                 background: '#fffbea',
    //                 iconColor: '#e6a100',
    //                 allowOutsideClick: false,
    //                 backdrop: true,
    //                 timer: 4000,
    //                 timerProgressBar: true
    //             });


    //             }
    //         });
    //     }
    // }


    window.completeBreeding = function(requestId, status) {

        if (confirm('¿Deseas completar esta cruza?')) {

            fetch(`/breeding/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    request_id: requestId,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {

                    if (data.success) {

                        Swal.fire({
                            title: 'Breeding Approbed!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Got it',
                            confirmButtonColor: '#28a745',
                            background: '#f0fff5',
                            iconColor: '#28a745',
                            allowOutsideClick: false,
                            backdrop: true,
                            timer: 3500,
                            timerProgressBar: true
                        });

                        breedings = breedings.filter(b => b.request_id !== requestId);
                        populateTable(breedings);

                    } else {

                        Swal.fire({
                            title: 'Oops!',
                            text: data.message,
                            icon: 'warning',
                            confirmButtonText: 'Understood',
                            confirmButtonColor: '#e6a100', // warm yellow tone
                            background: '#fffbea',
                            iconColor: '#e6a100',
                            allowOutsideClick: false,
                            backdrop: true,
                            timer: 4000,
                            timerProgressBar: true
                        });

                    }
            })
            .catch(error => {
                console.error('Error al enviar los datos:', error);
            });
        }
    }


    document.addEventListener('DOMContentLoaded', () => {
        populateTable(breedings);
    });
</script>

   @endpush

</x-app-layout>