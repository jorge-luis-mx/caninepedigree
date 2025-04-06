import { Utils } from './utils.js'; 

export  function pedigree() {

   document.addEventListener('DOMContentLoaded', function () {

      const searchBtn = document.querySelector('.btn-search-dog');
      const inputField = document.querySelector('input[name="dog"]');
  
      searchBtn.addEventListener('click', function (e) {
        let inputValue = inputField.value;
        inputValue.trim();

        objets.handleSearch(e,inputValue);

      //   console.log('Valor del input:', inputValue);
        // Puedes usar el valor como desees, por ejemplo enviarlo a una función o redirigir
      });


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
                    const li = document.createElement('li');
                
                    const link = document.createElement('a');
                    link.href = `/dogs/${dog.dog_id}`;  // ruta dinámica con el ID
                    link.textContent = dog.name;        // nombre del perro
                
                    li.appendChild(link);
                    container.appendChild(li);
                  });
                
                  container.style.display = 'block';
               }
                
            })
            .catch(err => console.error(`Error al buscar ${type}:`, err));
         },


      }

   });
}