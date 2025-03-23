import './bootstrap';
import Alpine from 'alpinejs';
import mainFunction from './dashboard.js';
import { airport } from './airport.js';
import { map } from './map.js';
import { serviceType } from './serviceType.js';
import { pricing } from './pricing.js';
import { operations } from './operations.js';
import { profile } from './profile.js';
import { dogs } from './dogs.js';


window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", function() {
    const scrf_login = document.getElementById('loginForm');
    if (scrf_login) {

        scrf_login.addEventListener('submit', function (event) {
            event.preventDefault(); // Evita el envío inmediato del formulario
    
            fetch('/csrf-token-refresh')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener el token CSRF');
                    }
                    return response.json();
                })
                .then(data => {
                    // Actualiza el token en la meta y en los encabezados de la solicitud
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
                    this.querySelector('input[name="_token"]').value = data.token; // si hay un input hidden _token
    
                    // Enviar el formulario después de actualizar el token
                    this.submit();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al refrescar el token CSRF. Por favor, intenta de nuevo.');
                });
        });
    }

});

mainFunction();
airport();
map();
serviceType();
pricing();
operations();
profile();
