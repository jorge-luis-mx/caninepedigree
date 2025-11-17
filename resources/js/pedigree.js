import { Utils } from './utils.js'; 

export  function pedigree() {

   document.addEventListener('DOMContentLoaded', function () {
      
    // 🔸 Verifica si esta vista tiene los elementos del módulo
    const searchBtn = document.querySelector('.btn-search-dog-pedigree');
    const inputField = document.querySelector('input[name="dog"]');
    const container = document.getElementById("list-dogs");

    // Si no hay ninguno, no ejecutes el código
    if (!searchBtn || !inputField || !container) return;

      // const searchBtn = document.querySelector('.btn-search-dog-pedigree');
      // const inputField = document.querySelector('input[name="dog"]');


      function handleSearchEvent(e) {
         let inputValue = inputField.value.trim(); // trim aquí sí tiene efecto si se reasigna
         objets.handleSearch(e, inputValue);
      }
      if (searchBtn) {
         searchBtn.addEventListener('click', handleSearchEvent);
      }
       
      if (inputField) {
         inputField.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
              e.preventDefault(); // evita que se envíe el formulario si está dentro de uno
              handleSearchEvent(e);
            }
         });
      }
       

      const objets ={

         handleSearch: function(e,inputValue) {
            e.preventDefault();

            fetch(`/dogs/search/${inputValue}`)
            .then(res => res.json())
            .then(data => {

               if (data.status === 200) {
                  const container = document.getElementById("list-dogs");
                  container.innerHTML = '';
                
                  let dogs = data.data;
                
                  if (!Array.isArray(dogs)) dogs = [dogs];
                
                  if (!dogs.length) {
                    container.innerHTML = '<li class="no-results">No dogs found.</li>';
                    container.style.display = 'block';
                    return;
                  }
                
                  dogs.forEach(dog => {

                     let sex = dog.sex == 'M' ? 'Male' : 'Female';
                     let dogName = `${dog.aliasDog} <span class="dog-sex is-size-7">(${sex})</span>`;
                     
                     const li = document.createElement('li');
                     li.classList.add('dog-item'); // esta clase la usaremos para estilo
                   
                     // Hacemos todo el <li> clickeable
                     li.addEventListener('click', () => {
                       window.location.href = `/pedigrees/${dog.dog_hash}`;
                     });
                   
                     li.innerHTML = dogName; // Texto directamente en el li
                     container.appendChild(li);
                   });
                   
                
                  container.style.display = 'block';
               }else{

                  inputField.value = '';
                  Swal.fire({
                     icon: 'warning',
                     title: 'Oops!',
                     text: 'No matches found for your search.',
                     confirmButtonText: 'Got it'
                   });
                   

               }
                
            })
            .catch(err => console.error(`Error al buscar`, err));
         },


      }

   });
}