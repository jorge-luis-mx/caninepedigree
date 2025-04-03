import { Utils } from './utils.js'; 


export default function mainFunction() {

    function handleResponsiveMenu() {
        const menuHamburger = document.getElementById('menuHamburger');
        const aside = document.querySelector('aside');
        const main = document.querySelector('.main');
        const logoNormal = document.getElementById('logo-normal');
        const logoReduced = document.getElementById('logo-reduced');
        const overlay = document.getElementById('overlay');

    
        if (!menuHamburger || !aside || !main || !logoNormal || !logoReduced) return;
    
        menuHamburger.addEventListener('click', (event) => {
            event.stopPropagation(); // Evita que el evento llegue al document
    
            if (window.innerWidth < 768) {
                // Si aside está oculto (display: none), lo mostramos, si no, lo ocultamos
                aside.style.display = aside.style.display === 'block' ? 'none' : 'block';
                overlay.style.display = 'block'; 
            } else {
                // Alternar clases solo en pantallas grandes (>= 768px)
                aside.classList.toggle('reduced'); 
                main.classList.toggle('reduced');
    
                if (aside.classList.contains('reduced')) {
                    logoNormal.style.display = 'none';
                    logoReduced.style.display = 'block';
                } else {
                    logoNormal.style.display = 'block';
                    logoReduced.style.display = 'none';
                }
            }
        });
    
        // Cierra el aside cuando se hace clic fuera de él en pantallas menores a 768px
        document.addEventListener('click', (event) => {
            if (window.innerWidth < 768 && aside.style.display === 'block' && 
                !aside.contains(event.target) && !menuHamburger.contains(event.target)) {
                aside.style.display = 'none';
                
            }
        });
    
        // Manejo de los dropdowns del menú
        const drops = document.querySelectorAll('.nav-drop');
        drops.forEach(drop => {
            drop.addEventListener('click', function () {
                this.querySelector('.submenu').classList.toggle('is-visible');
                this.querySelector('.custom-icon-chevron').classList.toggle('rotate');
            });
        });
    
        // Ocultar aside al hacer clic en cualquier elemento de la lista en móviles
        const menuItems = document.querySelectorAll('.submenu li a');
        menuItems.forEach(item => {
            item.addEventListener('click', function () {
                if (window.innerWidth < 768) {
                    aside.style.display = 'none';
                    
                }
            });
        });
    
        // Asegurar que el aside esté oculto si la pantalla se redimensiona a menor de 768px
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                aside.style.display = 'block'; // Mostrar aside en pantallas grandes
            } else {
                aside.style.display = 'none';  // Ocultar aside en pantallas pequeñas
                overlay.style.display = 'none'; 
            }
        });
    
    
        // Escuchar el clic en el overlay para ocultar el menú
        overlay.addEventListener('click', () => {
            overlay.style.display = 'none';  // Ocultamos el aside al hacer clic en el overlay
        });
    }
    
    // Ejecutar la función al cargar la página
    handleResponsiveMenu();


}