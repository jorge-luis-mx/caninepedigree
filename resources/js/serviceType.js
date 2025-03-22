import { Utils } from "./utils";
export function serviceType(){

    const utils = new Utils();

    document.addEventListener('DOMContentLoaded', function() {
       
        // Obtener todos los enlaces de servicio y los artículos correspondientes
        const firstLiWithAirport = document.querySelector('.tabs ul li[data-airport]');
        const tabs = document.querySelectorAll('.custom-slider .item-service');
        const articles = document.querySelectorAll('.content-service');
        const tabListItems = document.querySelectorAll('.custom-slider li'); 

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
                const currentLi = tab.closest('li');
                if (currentLi) {
                    currentLi.classList.add('is-active');
                }
    
                // Agregar la clase 'current' al enlace actual
                tab.classList.add('current');
    
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
            tabs.forEach(tab => {
                tab.classList.remove('current'); // Remover 'current' del enlace
            });
        }



        
        document.querySelectorAll('.custom-column').forEach(column => {
            column.addEventListener('click', function(e) {
                // Evita que el evento se propague a los elementos hijos
                e.stopPropagation();

                const airport = this.getAttribute('data-airport');
                const provider = this.getAttribute('data-provider');
                const service = this.getAttribute('data-service-type');
                const status = this.getAttribute('data-checkbox');
                
                let data = {
                    "airport": airport,
                    "provider": provider,
                    "services": [
                        {
                            "serviceType": service,
                            "status": status =='0'?'1':'0'
                        }
                    ]
                    
                    
                };

                Swal.fire({
                    title: "Are you sure?",
                    text: data.services[0].status==1?"Are you sure you want to add this type of service?":"Are you sure you want to remove this type of service?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#4CAF50",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Action!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        objets.storeService(e, data,column);
                    }
                });

                
            });
        });

    });
    




    


    const objets = {

        storeService: function(e,data,targetElement) {
            e.preventDefault();

            const notification = document.querySelector('.notification-dinamy');
            
            //exeptions rules
            const exceptions = [] ;
            const validations =true; //utils.validaObjets(data,exceptions); 
            if (validations===true) {
                //request POST
                let url ='/service/store';
                utils.requestPOST(url, data)
                .then(response => {
                    // location.reload();

                    if (response.status==200) {

                        const icon_checkbox = targetElement.querySelector('.icon-checkbox');

                        if (data.services[0].status === '1') {
                            targetElement.classList.remove('custom-column-opacity');
                            targetElement.setAttribute('data-checkbox', '1');
                             icon_checkbox.classList.remove('is-hidden');

                        } else {
                            targetElement.classList.add('custom-column-opacity');
                            targetElement.setAttribute('data-checkbox', '0');
                            icon_checkbox.classList.add('is-hidden');

                        }
                        
                        Swal.fire({
                            title: "Success!",
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
                    }
                })
                .catch(error => {

                });
            }

        }

    }

}