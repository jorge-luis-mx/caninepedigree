
<x-app-layout>




   <div class="container">
        
        @if(!empty($breedings))
        <!-- Campo de búsqueda -->

        
        
        <div class="table-container mt-5">
            <table class="table is-fullwidth is-striped is-hoverable is-bordered">

                <tbody id="dogTableBody">
                    <!-- Aquí se insertarán las filas dinámicamente -->
                </tbody>
            </table>
        </div>
        <div id="pagination" class="mt-4 has-text-centered"></div>

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



document.addEventListener("DOMContentLoaded", function() {

    let dogs = @json($breedings);

    let currentPage = 1;
    const dogsPerPage = 10;
    let currentSearchTerm = '';
    let previousPageBeforeFilter = 1;

    // Función para llenar la tabla
    function populateTable(dogsToDisplay) {
        const tableBody = document.getElementById('dogTableBody');
        tableBody.innerHTML = '';

        const start = (currentPage - 1) * dogsPerPage;
        const end = start + dogsPerPage;
        const paginatedDogs = dogsToDisplay.slice(start, end);

        paginatedDogs.forEach((dog) => {
            let sex = dog.sex == 'M' ? 'Male' : 'Female';
            const row = document.createElement('tr');

            // Crear el td con data-href
            const td = document.createElement('td');
            td.textContent = dog.name;
            td.dataset.href = `/dogs/show/${dog.dog_hash}`;
            td.style.cursor = 'pointer'; // Para que se vea como un link
            td.classList.add('clickable-td');

            // Evento para redirección al hacer clic
            td.addEventListener('click', () => {
                window.location.href = td.dataset.href;
            });

            // Agregar el td a la fila
            row.appendChild(td);
            tableBody.appendChild(row);
            
        });



        renderPagination(dogsToDisplay);
    }

    function renderPagination(dogsList) {
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';

        const totalPages = Math.ceil(dogsList.length / dogsPerPage);

        if (totalPages <= 1) return; // No mostrar paginación si no es necesario

        const prevBtn = document.createElement('button');
        prevBtn.textContent = 'Anterior';
        prevBtn.className = 'button is-small mx-1';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => {
            currentPage--;
            populateTable(dogsList);
        };
        paginationContainer.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.textContent = i;
            pageBtn.className = `button is-small mx-1 ${i === currentPage ? 'is-primary' : ''}`;
            pageBtn.onclick = () => {
                currentPage = i;
                populateTable(dogsList);
            };
            paginationContainer.appendChild(pageBtn);
        }

        const nextBtn = document.createElement('button');
        nextBtn.textContent = 'Siguiente';
        nextBtn.className = 'button is-small mx-1';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => {
            currentPage++;
            populateTable(dogsList);
        };
        paginationContainer.appendChild(nextBtn);
    }

    window.filterDogs = function () {
        const searchInput = document.getElementById('searchInput');
        const newTerm = searchInput.value.toLowerCase();

        // Si el usuario acaba de empezar a escribir, guardamos la página actual
        if (currentSearchTerm === '' && newTerm !== '') {
            previousPageBeforeFilter = currentPage;
        }

        currentSearchTerm = newTerm;

        const filteredDogs = dogs.filter(dog =>
            dog.name.toLowerCase().includes(currentSearchTerm)
        );

        const totalPages = Math.ceil(filteredDogs.length / dogsPerPage);

        // Si estamos filtrando y la página actual no existe más, ajustar
        if (currentPage > totalPages) {
            currentPage = totalPages || 1;
        }

        // Si se borró el filtro, restaurar la página anterior
        if (currentSearchTerm === '') {
            currentPage = previousPageBeforeFilter;
        }

        populateTable(filteredDogs);
    };





    // Funciones para manejar las acciones
    window.viewDetails = function(dogId) {
        
        window.location.href = `/dogs/show/${dogId}`;
    }

    window.deleteDog = function(dogId) {
        const confirmation = confirm(`¿Estás seguro de eliminar a este perro?`);
        if (confirmation) {
            fetch(`/dogs/${dogId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Perro eliminado con éxito');
                    dogs = dogs.filter(dog => dog.dog_id !== dogId); // Corregido: usar dog.dog_id en lugar de dog.id
                    populateTable(dogs); // Refrescar la tabla
                } else {
                    alert('Error al eliminar el perro');
                }
            })
            .catch(error => alert('Error al eliminar el perro: ' + error));
        }
    }

    window.requestMating = function(dogId) {
        alert(`Solicitar cruza para ${dogs[index].name}`);
    }

    window.makePayment = function(dogId) {
        alert(`Realizar pago para ${dogs[index].name}`);
    }

    // Llamada inicial a la función para llenar la tabla con todos los perros

    //populateTable(dogs);
    populateTable(dogs.filter(dog =>

        dog.name.toLowerCase().includes(currentSearchTerm)
    ));

});


    </script>

   @endpush

</x-app-layout>