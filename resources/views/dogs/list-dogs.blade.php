<x-app-layout>

   

   <div class="columns is-multiline">
      <div class="column">
         <div class="menu-add">
            <a href="{{ route('dogs.create')}}">
               <div class="card has-background-warning" style="width: 170px;box-shadow:none; padding: 8px 0px 8px 0px;" >
                  <div class="card-content" style="padding: 0; margin:0;">
                     <div class="is-flex is-justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon mr-1" viewBox="0 0 24 24"><path fill="#ffffff" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h4.2q.325-.9 1.088-1.45T12 1t1.713.55T14.8 3H19q.825 0 1.413.588T21 5v5.025q0 .425-.288.713t-.712.287t-.712-.288t-.288-.712V5H5v14h5q.425 0 .713.288T11 20t-.288.713T10 21zm0-3v1V5v6.075V11zm3-1h2.5q.425 0 .713-.288T11.5 16t-.288-.712T10.5 15H8q-.425 0-.712.288T7 16t.288.713T8 17m0-4h5q.425 0 .713-.288T14 12t-.288-.712T13 11H8q-.425 0-.712.288T7 12t.288.713T8 13m0-4h8q.425 0 .713-.288T17 8t-.288-.712T16 7H8q-.425 0-.712.288T7 8t.288.713T8 9m4-4.75q.325 0 .538-.213t.212-.537t-.213-.537T12 2.75t-.537.213t-.213.537t.213.538t.537.212M18 23q-2.075 0-3.537-1.463T13 18t1.463-3.537T18 13t3.538 1.463T23 18t-1.463 3.538T18 23m-.5-4.5v2q0 .2.15.35T18 21t.35-.15t.15-.35v-2h2q.2 0 .35-.15T21 18t-.15-.35t-.35-.15h-2v-2q0-.2-.15-.35T18 15t-.35.15t-.15.35v2h-2q-.2 0-.35.15T15 18t.15.35t.35.15z"/></svg>
                        <span class="is-block has-text-white">{{__('messages.main.dogs.btn')}}</span>
                     </div>
                  </div>
               </div>
            </a>

         </div>
      </div>
   </div>

   <div class="container">
  
        @if(!empty($dogs))
        <!-- Campo de búsqueda -->
        <div id="pagination" class="mt-4 has-text-right"></div>
        <div class="field">
            <label class="label">{{__('messages.main.dogs.search')}}</label>
            <div class="control">
                <input class="input" type="text" id="searchInput" placeholder="{{__('messages.main.dogs.placeholder')}}" oninput="filterDogs()">
            </div>
        </div>
        
        
        <div class="table-container mt-5">
            <table class="table is-fullwidth is-striped is-hoverable is-bordered">

                <tbody id="dogTableBody">
                    <!-- Aquí se insertarán las filas dinámicamente -->
                </tbody>
            </table>
        </div>
        

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

    let dogs = @json($dogs);
    const role = @json($role);
    const permissions = @json($permissions);
    
    const isAdmin = role?.name === 'Admin';
    const canDelete = permissions.some(p => p.name === 'Delete');

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
            row.appendChild(td); // Nombre del perro con enlace

            if (isAdmin && canDelete) {
                // Celda para el botón eliminar
                const actionTd = document.createElement('td');
                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Eliminar';
                deleteBtn.className = 'button is-danger is-small';
                deleteBtn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Evita que el clic afecte al evento del td
                    deleteDog(dog.dog_id);
                });
                actionTd.appendChild(deleteBtn);
                row.appendChild(actionTd);  // Botón eliminar
            }

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