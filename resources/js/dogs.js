import { Utils } from './utils.js'; 

export  function dogs() {


    document.addEventListener("DOMContentLoaded", () => {
        const dogFormContainer = document.getElementById('dog-form');
    
        const handleSearch = (input, form, type) => {
            const regNo = input.value.trim();
            if (regNo.length < 3) return console.log("Debe tener al menos 3 caracteres.");
    
            fetch(`/dogs/search/${regNo}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 200) {
                        type === 'sire' ? showResults(data.data, form, type) : showResults(data.data, form, type);
                    } else {
                        input.value = '';
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
                                
                                form.querySelector(`.${type}Mail`).classList.remove('is-hidden');
                                form.querySelector(`.search${capitalize(type)}`).classList.add('is-hidden');
                            }
                        });
                    }
                })
                .catch(err => console.error(`Error al buscar ${type}:`, err));
        };
    
        const showResults = (dogs, form, type) => {
            const container = document.getElementById(`${type}Results`);
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
                item.addEventListener('click', () => selectDog(dog.dog_id, dog.name, form, type));
                container.appendChild(item);
            });
    
            container.style.display = 'block';
        };
    
        const selectDog = (id, name, form, type) => {
            form.querySelector(`input[name="${type}"]`).value = name;
            form.querySelector(`input[name="${type}_id"]`).value = id;
            document.getElementById(`${type}Results`).style.display = 'none';
        };
    
        const capitalize = str => str.charAt(0).toUpperCase() + str.slice(1);
    
        if (dogFormContainer) {
            dogFormContainer.addEventListener('keydown', e => {
                const target = e.target;
                if (['sire', 'dam'].some(cls => target.classList.contains(cls)) && e.key === 'Enter') {
                    e.preventDefault();
                    handleSearch(target, target.closest('form'), target.classList.contains('sire') ? 'sire' : 'dam');
                }
            });
    
            dogFormContainer.addEventListener('blur', e => {
                const target = e.target;
                if (target.classList.contains('sire') || target.classList.contains('dam')) {
                    handleSearch(target, target.closest('form'), target.classList.contains('sire') ? 'sire' : 'dam');
                }
            }, true);
        }
    
        const saveBtn = document.querySelector(".saveDog");
        if (saveBtn) {
            saveBtn.addEventListener("click", e => {
                e.preventDefault();
    
                const form = document.getElementById("dog-form");
                const formData = new FormData(form);
                const data = {};
    
                formData.forEach((value, key) => {
                    const el = form.querySelector(`[name="${key}"]`);
                    if (el && (!el.disabled || el.type === "hidden")) {
                        data[key] = value;
                    }
                });
    
                objets.saveDog(e, form, data);
            });
        }
    });
    
    const objets = {
        saveDog(e, form, data) {
            fetch(form.action, {
                method: form.method.toUpperCase(),
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
            // Limpiar errores previos
            clearErrors(form);

            if (result.status === 200) {
                if (result.data.rol=='admin') {
                    window.location.href = '/dogs';
                }else{
                    let id = result.data.dog_id_md;
                    const editUrl = `/payments/pay/${id}`;
                    window.location.href = editUrl;
                }
                

            } else if (result.errors) {
                // Si hay errores, mostrarlos en los inputs correspondientes
                showErrors(form, result.errors);
            }

            })
            .catch(err => {
                console.error("Error al enviar el formulario:", err);
                alert("An error occurred. Please try again.");
            });
        }
    };
    


    // Función para mostrar errores en los inputs
function showErrors(form, errors) {
    Object.keys(errors).forEach(field => {
        let input = form.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add("error"); // Agregar borde rojo

            // Verificar si ya existe un mensaje de error
            let errorMessage = input.nextElementSibling;
            if (!errorMessage || !errorMessage.classList.contains("error-message")) {
                errorMessage = document.createElement("div");
                errorMessage.classList.add("error-message");
                input.after(errorMessage);
            }

            errorMessage.textContent = errors[field][0]; // Mostrar el primer mensaje de error
        }
    });
}

// Función para limpiar errores cuando el usuario escribe
function clearErrors(form) {
    form.querySelectorAll(".error").forEach(input => {
        input.classList.remove("error");
    });

    form.querySelectorAll(".error-message").forEach(msg => {
        msg.remove();
    });
}

// Agregar eventos a los inputs para limpiar errores cuando el usuario escribe
document.addEventListener("input", function(e) {
    if (e.target.classList.contains("error")) {
        e.target.classList.remove("error");
        let errorMessage = e.target.nextElementSibling;
        if (errorMessage && errorMessage.classList.contains("error-message")) {
            errorMessage.remove();
        }
    }
});

}