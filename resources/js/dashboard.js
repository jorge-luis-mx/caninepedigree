import { Utils } from './utils.js'; 


export default function mainFunction() {
    document.addEventListener("DOMContentLoaded", function() {

        // Seleccionar los elementos necesarios
        const menuHamburger = document.getElementById('menuHamburger'); // Botón de menú de hamburguesa
        const aside = document.querySelector('aside');
        const main = document.querySelector('.main');
        const logoNormal = document.getElementById('logo-normal')
        const logoReduced = document.getElementById('logo-reduced')
        if (menuHamburger) {
            // Función para alternar el tamaño del aside
            menuHamburger.addEventListener('click', () => {
                aside.classList.toggle('reduced'); // Alterna entre expandido y reducido
                main.classList.toggle('reduced'); // Alterna entre expandido y reducido
                if (aside.classList.contains('reduced')) {
                    // Si el aside está reducido, mostramos el logo reducido y ocultamos el logo normal
                    logoNormal.classList.add('is-hidden');
                    logoReduced.classList.remove('is-hidden');
                } else {
                    // Si el aside está expandido, mostramos el logo normal y ocultamos el logo reducido
                    logoNormal.classList.remove('is-hidden');
                    logoReduced.classList.add('is-hidden');
                }
            });  
        }

        const drops = document.querySelectorAll('.nav-drop');
        if (drops.length > 0) {
            
            drops.forEach(drop => {
                drop.addEventListener('click', function() {
                    // Alternar la clase is-visible para mostrar/ocultar el submenú
                    this.querySelector('.submenu').classList.toggle('is-visible');
                    
                    // Alternar la rotación del ícono
                    this.querySelector('.custom-icon-chevron').classList.toggle('rotate');
                });
            });
        }


    });
}