import { Utils } from './utils.js'; 

export  function breedingRequest() {


    document.addEventListener("DOMContentLoaded", () => {

        const utils = new Utils();
        const formBreeding = document.getElementById('formBreeding');
        let selectingDog = false;

        const handleSearch = (input, form, type) => {

            const regNo = input.value.trim();

            fetch(`/dogs/find/${regNo}/${type}`)
                .then(res => res.json())
                .then(data => {
                    
                    if (data.status === 200) {
                        showResults(data.data, form, type);
                    }else{
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
                                console.log(type);
                                form.querySelector(`.${type}Email`).classList.remove('is-hidden');
                                form.querySelector(`.search${capitalize(type)}`).classList.add('is-hidden');
                            }
                        });

                    }
                })
                .catch(err => console.error(`Error al buscar ${type}:`, err));
        };
        
        if (formBreeding) {

    
            formBreeding.addEventListener('keydown', e => {
                const target = e.target;
                if (target.classList.contains('dog') && e.key === 'Enter') {
                    e.preventDefault();
                    handleSearch(target, target.closest('form'), 'sire');
                }
            });

            // Agregar eventos click a los botones Search Sire y Search Dam
            const btnDog = formBreeding.querySelector('.btn-searchDog');
            if (btnDog) {
                btnDog.addEventListener('click', () => {
                    selectingDog = true; // <- evita doble ejecución desde blur
                    const inputDog = formBreeding.querySelector('input.dog');
                    if (inputDog) {
                        handleSearch(inputDog, inputDog.closest('form'), 'sire');
                    }
                });
            }
            
        }

        const showResults = (dogs, form, type) => {
            
            const container = document.getElementById(`dogResults`);
            container.innerHTML = '';
            

            if (!Array.isArray(dogs)) dogs = [dogs];
        
            if (!dogs.length) {
                container.innerHTML = '<div class="no-results">No dogs found.</div>';
                container.style.display = 'block';
                return;
            }
            
            dogs.forEach(dog => {
                const item = document.createElement('div');
                item.className = 'result-item';
                item.textContent = dog.name;
                item.dataset.dogId = dog.dog_id;
        
                // Maneja el clic correctamente
                item.addEventListener('mousedown', () => {
                    selectingDog = true;
                    // Usamos 'mousedown' en lugar de 'click' para que se registre antes de que el input pierda el foco
                    selectDog(dog.dog_id, dog.name, form, type);
                    
                });
        
                container.appendChild(item);
            });
            container.style.display = 'block';
            
        };
        
        const selectDog = (id, name, form, type) => {
            
            form.querySelector(`input[name="dog"]`).value = name;
            form.querySelector(`input[name="dog_id"]`).value = id;
            document.getElementById(`dogResults`).style.display = 'none';
        };
        const capitalize = str => str.charAt(0).toUpperCase() + str.slice(1);



        const saveBtn = document.querySelector(".saveBreedingRequest");
        if (saveBtn) {
            saveBtn.addEventListener("click", e => {
                e.preventDefault();

                const form = document.getElementById("formBreeding");
                const serializedData  = utils.serializeForm(form);
                const errors = validateDogForm(serializedData);

                if (errors.length === 0) {

                    objets.saveBreedingRequest(e, form, serializedData);
                }

            });
        }

    });


    const objets = {

         saveBreedingRequest(e, form, serializedData) {

                fetch(form.action, {
                    method: form.method.toUpperCase(),
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify(serializedData)
                })
                .then(res => res.json())

                .then(result => {

                // Limpiar errores previos
                // clearErrors(form);
                if (result.status === 'success') {
                    Swal.fire({
                        title: 'Breeding request sent!',
                        text: 'Your request has been submitted successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        backdrop: true
                    });
                    form.reset();
                }else if(result.status === 'error'){
                    Swal.fire({
                        title: 'Breeding request sent!',
                        text: result.message,
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        backdrop: true
                    });
                }else{

                    showErrors(form, result.errors);
                } 
                
                })
                .catch(err => {
                    console.error("Error al enviar el formulario:", err);
                    alert("An error occurred. Please try again.");
                });

        }
        
    }


    function validateDogForm(serializedData) {
        let hasError = false;
    
        const fields = ['my_dog_id', 'dog_id', 'dogDetails', 'dog_email'];
        fields.forEach(id => clearError(id)); // Limpia antes de validar
    
        const dog_id = serializedData.dog_id?.trim() || '';
        const dogDetails = serializedData.dogDetails?.trim() || '';
        const dog_email = serializedData.dog_email?.trim() || '';
        const my_dog_id = serializedData.my_dog_id?.trim() || '';
    
        if (!my_dog_id) {
            showError('my_dog_id', 'Debe seleccionar su propio perro.');
            hasError = true;
        }
    
        if (!dog_id) {
            showError('dog_id', 'Buscar el perro que desee cruzar');
    
            if (!dogDetails) {
                showError('dogDetails', 'Debe completar los detalles del perro.');
                hasError = true;
            }
            if (!dog_email) {
                showError('dog_email', 'Debe completar el email del dueño del otro perro.');
                hasError = true;
            } else if (!validateEmail(dog_email)) {
                showError('dog_email', 'El correo del dueño no es válido.');
                hasError = true;
            }
        }
    
        addRealTimeValidation(fields); // 👉 importante: agregamos el evento aquí
    
        return hasError ? ['Hay errores'] : [];
    }
    
    function showError(id, message) {
        const input = document.getElementById(id);
        const error = input.parentElement.querySelector('.error-message');
    
        input.classList.add('input-error');
        error.textContent = message;
    }
    
    function clearError(id) {
        const input = document.getElementById(id);
        const error = input.parentElement.querySelector('.error-message');
    
        input.classList.remove('input-error');
        if (error) {
            error.textContent = '';
        }
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function addRealTimeValidation(fields) {
        fields.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', function handler() {
                    if (input.value.trim() !== '') {
                        clearError(id);
                        input.removeEventListener('input', handler); // 🔥 Solo escucha una vez
                    }
                });
            }
        });
    }
    


   
}