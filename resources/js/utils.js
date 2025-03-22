let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

var url = window.location.origin;

export class Utils {

  
    async requestGET(url, datos = null) {
        try {
            // Construir la URL con los datos si existen
            if (datos !== null && typeof datos === 'object' && Object.keys(datos).length > 0) {
                // Formar la cadena de consulta con los datos proporcionados
                let queryString = Object.keys(datos).map(key => key + '=' + encodeURIComponent(datos[key])).join('&');
                url += '?' + queryString;
            }

            let respuesta = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken, // Agregar el token CSRF
                    'Content-Type': 'application/json'
                }
            });

            // Verificar si la respuesta es correcta
            if (!respuesta.ok) {
                throw new Error('Error en la petición: ' + respuesta.statusText);
            }

            return await respuesta.json();
        } catch (error) {
            console.error('Error:', error);
            return null;
        }
    }
    
    async  requestPOST(url, data) {
        try {

          let response = await fetch(url, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN':  csrfToken,
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
          });
      
          return await response.json();
        } catch (error) {
          return null;
        }
    }

    async requestPUT(url, datos = null) {
        try {
            // Verifica si los datos existen y son un objeto
            let body = null;
            if (datos !== null && typeof datos === 'object') {
                body = JSON.stringify(datos); // Convierte los datos a JSON
            }
        
            let respuesta = await fetch(url, {
                method: 'PUT', // Cambiar a método PUT
                headers: {
                    'X-CSRF-TOKEN': csrfToken, // Agrega el token CSRF
                    'Content-Type': 'application/json' // Establece el tipo de contenido
                },
                body: body // Enviar los datos en el cuerpo de la solicitud
            });
        
            if (!respuesta.ok) {
                throw new Error('Error en la petición: ' + respuesta.statusText);
            }
        
            return await respuesta.json(); // Devolver la respuesta en formato JSON
        } catch (error) {
            console.error('Error:', error);
            return null;
        }
    }


    async  requestDELETE(url, datos = null) {
      try {
        // Construir la URL con los datos si existen
        if (datos !== null && typeof datos === 'object' && Object.keys(datos).length > 0) {
          let queryString = Object.keys(datos).map(key => key + '=' + encodeURIComponent(datos[key])).join('&');
          url += '?' + queryString;
        }
    
        let respuesta = await fetch(url, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN':  csrfToken,
            'Content-Type': 'application/json'
          }
        });
    
        if (!respuesta.ok) {
          throw new Error('Error en la petición: ' + respuesta.statusText);
        }
    
        return await respuesta.json();
      } catch (error) {
        console.error('Error:', error);
        return null;
      }
    }
    
    serializeForm(form) {

        const formData = new FormData(form);
        const serialized = {};
    
        for (const [key, value] of formData.entries()) {
          serialized[key] = value;
        }
        // Recorre todos los selects para agregar aquellos que no tienen valor
        const selects = form.querySelectorAll('select');
        selects.forEach(select => {
          if (!(select.name in serialized)) {
            serialized[select.name] = ''; 
          }
        });
        return serialized;
    }

    validateForm(data,exeption) {

      
      let isValid = true;
      // Recorre los elementos del formulario
      for (const key in data) {
        if (Object.prototype.hasOwnProperty.call(data, key)) {
            const value = data[key];
            const trimmedValue =value? value.trim():'';

            // Encuentra el elemento correspondiente
            const inputElement = document.getElementById(key);

            if (inputElement) {
                // Función para remover la clase 'is-danger' si se agrega un valor
                const removeDangerClass = () => {
                    if (inputElement.tagName.toLowerCase() === 'select') {
                        const selectWrapper = inputElement.closest('.select');
                        if (selectWrapper && inputElement.value !== '') {
                            selectWrapper.classList.remove('is-danger');
                        }
                    } else {
                        if (inputElement.value.trim() !== '') {
                            inputElement.classList.remove('is-danger');
                        }
                    }
                };

                // Si es un select, escucha el evento 'change'
                if (inputElement.tagName.toLowerCase() === 'select') {
                    inputElement.addEventListener('change', removeDangerClass);
                } else {
                    // Para inputs normales, escucha el evento 'input'
                    inputElement.addEventListener('input', removeDangerClass);
                }

                // Si el valor actual está vacío, añade la clase 'is-danger'
                if (trimmedValue === '') {
                    if (!exeption.includes(key)) {
                        if (inputElement.tagName.toLowerCase() === 'select') {
                            const selectWrapper = inputElement.closest('.select');
                            if (selectWrapper) {
                                selectWrapper.classList.add('is-danger');
                            }
                        } else {
                            inputElement.classList.add('is-danger');
                        }
                        inputElement.focus();
                        isValid = false;
                    }
                }
            }
        }
      }
     

      return isValid;
    
    }

    showErrors(data){

      for (const key in data) {
          if (Object.prototype.hasOwnProperty.call(data, key)) {
              const value = data[key];
              const trimmedValue = value.trim();
              const inputElement = document.getElementById(key);
              if (inputElement) {
                  inputElement.classList.add('is-danger');
                  inputElement.focus();

              }
  
          }
      }
    }

    showErrorsRequest(errors){

      for (let field in errors) {

        
        const existingElement = document.querySelector('.' + field);
        if (existingElement) {
            existingElement.remove();
        }
        // Get the element corresponding to the error field
        const elementfield = document.getElementById(field);
        if (elementfield) {
            // Create element p for error message
            const mensajeError = document.createElement('p');
            mensajeError.className = 'help is-danger'+' '+field;
            mensajeError.textContent = errors[field];//Sets the text inside the <p> element.
            
            // Get the span element
            const spanElement = elementfield.parentNode.querySelector('span');

              // Insert error message after <span> element
              if (spanElement && !spanElement.classList.contains(field)) {
                spanElement.parentNode.insertBefore(mensajeError, spanElement.nextSibling);
              }else{
                elementfield.parentNode.insertBefore(mensajeError, elementfield.nextSibling);
              }
            
        }
      }

    }

  validaObjets(data,exceptions){

        // Validar que data sea un objeto
      if (typeof data !== 'object' || data === null) {
          return 'El dato debe ser un objeto.';
      }

      // Validar el campo provider
      if (!exceptions.includes('provider') && 
          (typeof data.provider !== 'string' || data.provider.trim() === '')) {
          return 'provider'; // Retorna el nombre del campo con error
      }

      // Validar el campo services
      if (!exceptions.includes('services')) {
        // Verificar si es un array
        if (!Array.isArray(data.services)) {
            return 'services'; // Retorna 'services' si no es un array
        }
    
        // Verificar que el array no esté vacío
        if (data.services.length === 0) {
            return 'services'; // Retorna 'services' si el array está vacío
        }
    }
      // Validar el campo airport
      if (!exceptions.includes('airport') && 
          (typeof data.airport !== 'string' || data.airport.trim() === '')) {
          return 'airport'; // Retorna el nombre del campo con error
      }

      // Si todas las validaciones pasaron
      return true; // Retorna true si no hay errores

  }


  findProgress(data){

    let arrayProgress = [];

    if (data && (Object.keys(data).length > 0 || data.length > 0)) {

        for (const item of data) {
            let progressValue = null;

            if (item.progress.progressAirport !== null && item.progress.progressAirport !== undefined && item.progress.progressAirport.toString().trim() !== '') {
                progressValue = item.progress.progressAirport;
            }
            if (item.progress.progressMap !== null && item.progress.progressMap !== undefined && item.progress.progressMap.toString().trim() !== '') {
                progressValue = item.progress.progressMap;
            }
            if (item.progress.progressService !== null && item.progress.progressService !== undefined && item.progress.progressService.toString().trim() !== '') {
                progressValue = item.progress.progressService;
            }
            if (item.progress.progressPrices !== null && item.progress.progressPrices !== undefined && item.progress.progressPrices.toString().trim() !== '') {
                progressValue = item.progress.progressPrices;
            }

            arrayProgress.push(progressValue);
            

        }

    }
    const minProgress = arrayProgress.length ? Math.min(...arrayProgress) : 0;
    return minProgress
    

  }



    
}
  
