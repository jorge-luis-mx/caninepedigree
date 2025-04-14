import { Utils } from './utils.js'; 

export  function breedingRequest() {


   document.addEventListener("DOMContentLoaded", () => {

      const saveBtn = document.querySelector(".saveBreedingRequest");
      if (saveBtn) {
         saveBtn.addEventListener("click", e => {
            e.preventDefault();

            const form = document.getElementById("breeding_form");
            const formData = new FormData(form);
            const errors = validateBreedingForm(formData);
            if (errors.length > 0) {
               return;
            }

            // Convertir FormData a objeto plano
            const data = {};
            formData.forEach((value, key) => {
                  data[key] = value;
            });

            objets.saveBreedingRequest(e, form, data);
            
         });
      }

   });


   const objets = {


      saveBreedingRequest(e, form, data) {

         fetch(form.action, {
            method: form.method.toUpperCase(),
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())

        .then(result => {

        // Limpiar errores previos
        clearErrors(form);
         if (result.status === 'success') {
            Swal.fire({
                title: 'Breeding request sent!',
                text: 'Your request has been submitted successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                backdrop: true
            });

         } else if (result.errors) {
            
            showErrors(form, result.errors);
         }
            // console.log("Success:", data);
            // alert("Breeding request submitted successfully.");
            // form.reset();
        })
        .catch(err => {
            console.error("Error al enviar el formulario:", err);
            alert("An error occurred. Please try again.");
        });

      }
      
   }

   function validateBreedingForm(formData) {
      const errors = [];
      const fields = {
          other_dog_name: {
              el: document.querySelector('[name="other_dog_name"]'),
              error: "The other dog's name is required.",
          },
          other_owner_email: {
              el: document.querySelector('[name="other_owner_email"]'),
              error: "The other owner's email is invalid.",
          },
          comments: {
              el: document.querySelector('[name="comments"]'),
              error: "Comments may only contain letters, numbers, spaces, and basic punctuation.",
          },
      };
  
      // Clear previous errors
      Object.values(fields).forEach(({ el }) => {
          if (el) {
              el.classList.remove("input-error");
              const errorEl = el.nextElementSibling;
              if (errorEl && errorEl.classList.contains("error-msg")) {
                  errorEl.remove();
              }
          }
      });
  
      // Get field values
      const other_dog_name = formData.get("other_dog_name")?.trim();
      const other_owner_email = formData.get("other_owner_email")?.trim();
      const comments = formData.get("comments")?.trim();
  
      // Validate name
      if (!other_dog_name) {
          showError(fields.other_dog_name.el, fields.other_dog_name.error);
          errors.push(fields.other_dog_name.error);
      }
  
      // Validate email
      if (
          !other_owner_email ||
          !/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/.test(other_owner_email)
      ) {
          showError(fields.other_owner_email.el, fields.other_owner_email.error);
          errors.push(fields.other_owner_email.error);
      }
  
      // Validate comments if filled
      if (comments) {
          const pattern = /^[\p{L}\d\s.,!?¡¿()\-'"°áéíóúÁÉÍÓÚñÑ]+$/u;
          if (!pattern.test(comments)) {
              showError(fields.comments.el, fields.comments.error);
              errors.push(fields.comments.error);
          }
      }
  
      return errors;
  }
  
  function showError(input, message) {
      if (!input) return;
  
      input.classList.add("input-error");
  
      const errorEl = document.createElement("div");
      errorEl.classList.add("error-msg");
      errorEl.style.color = "red";
      errorEl.style.fontSize = "0.875rem";
      errorEl.textContent = message;
  
      if (!input.nextElementSibling || !input.nextElementSibling.classList.contains("error-msg")) {
          input.insertAdjacentElement("afterend", errorEl);
      }
  
      input.addEventListener("input", () => {
          input.classList.remove("input-error");
          const nextEl = input.nextElementSibling;
          if (nextEl && nextEl.classList.contains("error-msg")) {
              nextEl.remove();
          }
      }, { once: true });
  }
  

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