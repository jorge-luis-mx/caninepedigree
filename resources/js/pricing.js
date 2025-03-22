import { Utils } from "./utils";
export function pricing(){

    const utils = new Utils();

    document.addEventListener('DOMContentLoaded', function() {
       
        // Obtener todos los enlaces de servicio y los artículos correspondientes
        const firstLiWithAirport = document.querySelector('.custom-tabs ul a[data-airport]');
        const tabs = document.querySelectorAll('.custom-slider-pricing .item-service');
        const articles = document.querySelectorAll('.container-render-pricing');
        const tabListItems = document.querySelectorAll('.custom-slider-pricing  a'); 

        if (firstLiWithAirport) {
            
            const airportId = firstLiWithAirport.getAttribute('data-airport');
            // Mostrar el primer artículo al cargar la página
            showArticle(airportId);

        }

        // Agregar el evento click a cada pestaña
        tabs.forEach(tab => {
            tab.addEventListener('click', function(event) {
                event.preventDefault(); // Evita que el enlace navegue
    
                // Obtener el atributo href del enlace que contiene el ID del artículo a mostrar
                const articleId = tab.getAttribute('href');

                // Remover la clase 'is-active' de todos los elementos li
                removeActiveClass();
    
                // Agregar la clase 'is-active' al li correspondiente
                const currentLi = tab.closest('a');
                if (currentLi) {
                    currentLi.classList.add('is-active');
                }
    

    
                // Mostrar el artículo correspondiente
                showArticle(articleId);
            });
        });
    

        // Función para mostrar el artículo correspondiente y activar la pestaña
        function showArticle(id) {
            
            // Ocultar todos los artículos
            hideAllArticles();
  
            // Mostrar el artículo con el id correspondiente
            const targetArticle = document.getElementById(id);
            if (targetArticle) {
                targetArticle.style.display = 'block';
            }
        }

        
        // Función para ocultar todos los artículos
        function hideAllArticles() {
            
            articles.forEach(article => {
                article.style.display = 'none';

            });
        }

        // Función para remover la clase 'is-active' de todos los elementos li
        function removeActiveClass() {
    
            tabListItems.forEach(li => {
                li.classList.remove('is-active'); // Remover 'is-active' del li
            });

        }




        // Selecciona todos los inputs con las clases 'oneWay' y 'roundTrip'
        document.querySelectorAll('.oneWay, .roundTrip').forEach(input => {
            

            input.addEventListener('input', function(e) {

                // Detecta si el input es 'oneWay' o 'roundTrip'
                const type = e.target.classList.contains('oneWay') ? 'oneWay' : 'roundTrip';

                // Encuentra los elementos padre para obtener los datos necesarios
                const serviceElement = e.target.closest('.price-container');
                
                const retentionRate = serviceElement.getAttribute('data-retention');
                // Encuentra los otros inputs en la misma celda
                const oneWayInput = serviceElement.querySelector('.oneWay');
                const roundTripInput = serviceElement.querySelector('.roundTrip');
                const oneWayPaidInput = serviceElement.querySelector('input[name="pr_oneway"][readonly]');
                const roundTripPaidInput = serviceElement.querySelector('input[name="pr_roundTrip"][readonly]');

                // Obtiene los valores de los inputs
                const oneWayValue = oneWayInput ? parseFloat(oneWayInput.value) || 0 : 0;
                const roundTripValue = roundTripInput ? parseFloat(roundTripInput.value) || 0 : 0;
                               

                // Calcula los valores después de la retención
                const oneWayPaid = oneWayValue - (oneWayValue * retentionRate);
                const roundTripPaid = roundTripValue - (roundTripValue * retentionRate);
                // Función para limpiar comas y verificar si el valor es numérico
                function cleanAndValidate(value) {
                    if (typeof value === 'string') {
                        value = value.replace(/,/g, ''); // Elimina las comas
                    }
                    return !isNaN(value) ? parseFloat(value) : null; // Devuelve el número o null si no es válido
                }

                // Verifica y asigna valores a los inputs
                if (oneWayPaidInput) {
                    const cleanedOneWayPaid = cleanAndValidate(oneWayPaid);
                    if (cleanedOneWayPaid !== null) {
                        oneWayPaidInput.value = cleanedOneWayPaid.toFixed(2);
                    }
                }

                if (roundTripPaidInput) {
                    const cleanedRoundTripPaid = cleanAndValidate(roundTripPaid);
                    if (cleanedRoundTripPaid !== null) {
                        roundTripPaidInput.value = cleanedRoundTripPaid.toFixed(2);
                    }
                }
                
            });

            input.addEventListener('change', function(e) {
                // Detecta si el input es 'oneWay' o 'roundTrip'
                const type = e.target.classList.contains('oneWay') ? 'oneWay' : 'roundTrip';
                
                // Encuentra los elementos padre para obtener los datos necesarios
                const airportElement = e.target.closest('.container-render-pricing');
                const poligonElement = e.target.closest('.custom-container-card');
                const serviceElement = e.target.closest('.price-container');

                // Extrae los identificadores de aeropuerto, polígono y servicio
                const airportId = airportElement ? airportElement.id : null;
                const mapId = airportElement ? airportElement.dataset.map : null; 
                const poligonId = poligonElement ? poligonElement.dataset.poligon : null;
                const serviceId = serviceElement ? serviceElement.dataset.service : null;

                // Encuentra los otros inputs en la misma celda
                const oneWayInput = serviceElement.querySelector('.oneWay');
                const roundTripInput = serviceElement.querySelector('.roundTrip');

                // Obtiene los valores de ambos inputs
                const oneWayValue = oneWayInput ? oneWayInput.value : null;
                const roundTripValue = roundTripInput ? roundTripInput.value : null;

                const pricingData = {
                    airport: parseInt(airportId),
                    service: parseInt(serviceId),
                    map: parseInt(mapId),
                    poligon:poligonId,
                    inputs: {
                        oneWay: oneWayValue ? Number(parseFloat(oneWayValue).toFixed(2)) : null,
                        roundTrip: roundTripValue ? Number(parseFloat(roundTripValue).toFixed(2)) : null
                    }
                };
         
                const isValid = validatePricingData(pricingData);
                if (isValid) {
                     objets.storePricing(e,pricingData);
                } 
                
            });
        });


    });


    function validatePricingData(pricingData) {
        // Validar que 'airport', 'map' y 'service' sean enteros y no null o vacío
        if (
            !Number.isInteger(pricingData.airport) || pricingData.airport === null ||
            !Number.isInteger(pricingData.map) || pricingData.map === null ||
            !Number.isInteger(pricingData.service) || pricingData.service === null
        ) {
            return false; // O puedes lanzar un error usando `throw new Error("Error en la validación de aeropuertos")`
        }
    
        // Validar que los valores de 'inputs' sean números válidos con dos decimales o null
        const { oneWay, roundTrip } = pricingData.inputs;
    
        const isOneWayValid = oneWay === null || !isNaN(oneWay) && Number.isFinite(oneWay);
        const isRoundTripValid = roundTrip === null || !isNaN(roundTrip) && Number.isFinite(roundTrip);
    
        if (!isOneWayValid || !isRoundTripValid) {
            return false; // O puedes lanzar un error
        }
    
        // Todos los chequeos pasaron
        return true;
    }


    
    const objets = {

        storePricing: function(e,pricings) {
            const notification = document.querySelector('.notification-dinamy');

            e.preventDefault();
            //request POST
            let url ='/pricing/store';
            utils.requestPOST(url, pricings)
            .then(response => {
                //clearInputs(airportDiv);
                if (response.status === 200) {
                    
                    Swal.fire({
                        text: "Yeah! The Oneway:"+pricings.inputs.oneWay+" & Roundtrip:"+pricings.inputs.roundTrip+" fare for the selected service and area has been successfully updated",
                        icon: "success",
                        confirmButtonColor: "#4CAF50", 
                    });
                    
                    //progres fecht dinamic
                    fetch('/progress/serch')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la petición');
                        }
                        return response.json();
                    })
                    .then(data => {
                
                        let responseProgress = utils.findProgress(data)
                        if (responseProgress!= null) {
                            let progress = document.getElementById('progresInfo');
                            if (progress) {
                                progress.innerHTML =`
                                        <span>${responseProgress}%</span>
                                        <p>Configuration Progress</p>
                                    `;
                            }
                        }

                    })
                    .catch(error => console.error('Hubo un error:', error));

                } else {
                    Swal.fire({
                        title: "Error Registered Pricing",
                        text: response.message,
                        icon: "error",
                        confirmButtonColor: "#4CAF50", 
                        
                    }).then((result) => {
                        if (result.isConfirmed);
                    });
  
                }
                
                
            })
            .catch(error => {


            });
            
        }
    }

    // Función de validación para inputs
    function validateInput(inputElement) {
        const value = inputElement.value;
        const isValidNumber = /^\d+(\.\d{1,2})?$/.test(value); // Validar si es un número válido con 2 decimales como máximo
        if (value === '' || !isValidNumber) {
            inputElement.value = value.slice(0, -1); // Elimina el último carácter
        }

    }
    
    // Escuchar el evento 'input' para eliminar el borde rojo cuando sea válido
    document.querySelectorAll('input[name="pr_oneway"], input[name="pr_roundTrip"]').forEach(input => {
        input.addEventListener('input', function() {
            validateInput(this);

        });
    });
    


}