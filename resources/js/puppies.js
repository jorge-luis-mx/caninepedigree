import { Utils } from './utils.js'; 

export  function puppies() {

   document.addEventListener('DOMContentLoaded', function () {
      const formPuppies = document.getElementById('formPuppies');
      let selectingDog = false;
   
      if (formPuppies) {
         // Reutilizable: búsqueda al presionar Enter
         formPuppies.addEventListener('keydown', e => {
            if (e.key === 'Enter' && e.target.classList.contains('dog-search')) {
               e.preventDefault();
               const input = e.target;
               const type = input.dataset.type;
               handleSearch(input, input.closest('form'), type);
            }
         });
   
         // Delegación: clic en cualquier botón con class btn-search
         formPuppies.addEventListener('click', e => {
            if (e.target.classList.contains('btn-search')) {
               selectingDog = true;
               const type = e.target.dataset.type;
               const input = formPuppies.querySelector(`input.dog-search[data-type="${type}"]`);
               if (input) {
                  handleSearch(input, input.closest('form'), type);
               }
            }
         });
      }
   
      const handleSearch = (input, form, type) => {
         const regNo = input.value.trim();
         fetch(`/dogs/search/${regNo}/breeding`)
            .then(res => res.json())
            .then(data => {
               if (data.status === 200) {
                  showResults(data.data, form, type);
               } else {
                  Swal.fire({
                     title: 'No results found',
                     text: 'Would you like to search in another way?',
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonText: 'Yes',
                     cancelButtonText: 'No',
                     backdrop: true,
                     allowOutsideClick: false
                  }).then(result => {
                     if (!result.isConfirmed) {
                        form.querySelector(`.${type}Email`).classList.remove('is-hidden');
                        form.querySelector(`.search-container[data-type="${type}"]`).classList.add('is-hidden');
                     }
                  });
               }
            })
            .catch(err => console.error(`Error searching ${type}:`, err));
      };
   
      const showResults = (dogs, form, type) => {
         const resultsContainer = document.getElementById(`${type}Results`);
         resultsContainer.innerHTML = '';
   
         if (!Array.isArray(dogs)) dogs = [dogs];
   
         if (!dogs.length) {
            resultsContainer.innerHTML = '<div class="no-results">No dogs found.</div>';
            resultsContainer.style.display = 'block';
            return;
         }
   
         dogs.forEach(dog => {
            const item = document.createElement('div');
            item.className = 'result-item';
            item.textContent = dog.name;
            item.dataset.dogId = dog.dog_id;
   
            item.addEventListener('mousedown', () => {
               selectingDog = true;
               selectDog(dog.dog_id, dog.name, form, type);
            });
   
            resultsContainer.appendChild(item);
         });
   
         resultsContainer.style.display = 'block';
      };
   
      const selectDog = (id, name, form, type) => {
         form.querySelector(`input[name="${type}"]`).value = name;
         form.querySelector(`input[name="${type}_id"]`).value = id;
         document.getElementById(`${type}Results`).style.display = 'none';
      };
   
      const capitalize = str => str.charAt(0).toUpperCase() + str.slice(1);
   });
   


   document.addEventListener('DOMContentLoaded', function () {
      const formPuppies = document.getElementById('formPuppies');
      let selectingDog = false;
   
      if (formPuppies) {
         // Manejo de Enter en cualquier input con .dog-search
         formPuppies.addEventListener('keydown', e => {
            const target = e.target;
            if (e.key === 'Enter' && target.classList.contains('dog-search')) {
               e.preventDefault();
               const type = target.dataset.type;
               handleSearch(target, target.closest('form'), type);
            }
         });
   
         // Clic en cualquier botón con .btn-search
         formPuppies.addEventListener('click', e => {
            if (e.target.classList.contains('btn-search')) {
               selectingDog = true;
               const type = e.target.dataset.type;
               const input = formPuppies.querySelector(`.dog-search[data-type="${type}"]`);
               if (input) {
                  handleSearch(input, input.closest('form'), type);
               }
            }
         });
      }
   
      const handleSearch = (input, form, type) => {
         const regNo = input.value.trim();
         if (!regNo) {
            alert('Please enter a value to search.');
            return;
         }
   
         fetch(`/dogs/search/${regNo}/breeding`)
            .then(res => res.json())
            .then(data => {
               if (data.status === 200) {
                  showResults(data.data, type);
               } else {
                  Swal.fire({
                     title: 'No results found',
                     text: 'Would you like to search in another way?',
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonText: 'Yes',
                     cancelButtonText: 'No',
                     backdrop: true,
                     allowOutsideClick: false
                  }).then(result => {
                     if (!result.isConfirmed) {
                        const emailField = form.querySelector(`.${type}Email`);
                        const searchContainer = form.querySelector(`.search-container[data-type="${type}"]`);
                        if (emailField && searchContainer) {
                           emailField.classList.remove('is-hidden');
                           searchContainer.classList.add('is-hidden');
                        }
                     }
                  });
               }
            })
            .catch(err => console.error(`Error searching ${type}:`, err));
      };
   
      const showResults = (dogs, type) => {
         const resultsContainer = document.getElementById(`${type}Results`);
         resultsContainer.innerHTML = '';
   
         if (!Array.isArray(dogs)) dogs = [dogs];
   
         if (!dogs.length) {
            resultsContainer.innerHTML = '<div class="no-results">No results found.</div>';
            resultsContainer.style.display = 'block';
            return;
         }
   
         dogs.forEach(dog => {
            const item = document.createElement('div');
            item.className = 'result-item';
            item.textContent = dog.name;
            item.dataset.dogId = dog.dog_id;
   
            item.addEventListener('mousedown', () => {
               selectDog(dog.dog_id, dog.name, type);
            });
   
            resultsContainer.appendChild(item);
         });
   
         resultsContainer.style.display = 'block';
      };
   
      const selectDog = (id, name, type) => {
         const input = document.querySelector(`.dog-search[data-type="${type}"]`);
         const hiddenInput = document.querySelector(`input[name="${type}_id"]`);
         const resultsContainer = document.getElementById(`${type}Results`);
   
         if (input && hiddenInput) {
            input.value = name;
            hiddenInput.value = id;
         }
   
         if (resultsContainer) {
            resultsContainer.style.display = 'none';
         }
      };
   });
   

}