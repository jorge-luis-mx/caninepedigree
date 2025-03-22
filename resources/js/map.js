
import { Utils } from "./utils";
export function map(){

    const utils = new Utils();

    window.onload = function() {
        const firstMapForm = document.querySelector('.maps-form');
        if (firstMapForm) {
            firstMapForm.addEventListener('click', function(e) {
                const mapUrl = `/map/create`; 
                window.location.href = mapUrl;
            });
        }
    };


    const cardMap = document.getElementById('maps');

    if (cardMap) {

        //buttons edit and delete
        cardMap.addEventListener("click", (e) => {

            const editButton = e.target.closest('.btn-edit');
            if (editButton) {
                let id = editButton.getAttribute('data-id');
                let hashedId = CryptoJS.MD5(id).toString();
                objets.editMap(e, hashedId);
            }

            const deleteButton = e.target.closest('.btn-delete');
            if (deleteButton) {
                let id = deleteButton.getAttribute('data-id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#4CAF50",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        objets.destroyMap(e, id);
                    }
                });
                
            }
    
        });

        // change events on the cardMap; if a checkbox
        cardMap.addEventListener('change', (e) => {
            if (e.target.classList.contains('checkbox-map')) {
                const mapId = e.target.dataset.id;  
                const newStatus = e.target.checked ? 1 : 0;  
                
                objets.actionStatus(e,mapId,newStatus);
            }
        });

        // event click alias edit
        cardMap.addEventListener('click', (e) => {
            
            const span = e.target.closest('.header-alias span');
            const parentColumn = e.target.closest('.column.custom-card-map');
            
        
            if (span && span.querySelector('strong') && parentColumn) {

                const strongElement = span.querySelector('strong');
                const mapId = parentColumn.getAttribute('data-id'); 

                if (strongElement) {
                    // Crear un input para editar el texto
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = strongElement.textContent.trim();
                    input.classList.add('editable-input');
                    span.replaceChild(input, strongElement);
                    input.focus();

                    input.addEventListener('change', (e) => {
                        const newValue = input.value.trim();
                        objets.actionEditAlias(e, newValue, mapId);
                        
                        if (strongElement.tagName === "STRONG") {
                            //Antes de realizar la operación replaceChild, verifica que el elemento que deseas reemplazar sea un hijo directo del nodo padre.
                            if (span.contains(input)) {
                                span.replaceChild(strongElement, input);
                                strongElement.textContent = newValue || strongElement.textContent;
                            }

                        }

                    });

                    input.addEventListener('blur', (e) => {

                        const newValue = input.value.trim();
                       
                        if (newValue === strongElement.textContent) {
                            if (strongElement.tagName === "STRONG") {
                                if (span.contains(input)) {
                                    strongElement.textContent = newValue || strongElement.textContent;
                                    span.replaceChild(strongElement, input);
                                }

                            }
                        }

                    });

                    input.addEventListener('keydown', (event) => {
                        if (event.key === "Enter") {
                            const newValue = input.value.trim();
                            if (newValue === strongElement.textContent) {
                                if (strongElement.tagName === "INPUT") {
                                    if (span.contains(input)) {
                                        strongElement.textContent = newValue || strongElement.textContent;
                                        span.replaceChild(strongElement, input);
                                    }

                                }
                                
                            }
                        }
                    });

                    input.addEventListener('click', (event) => {
                        event.stopPropagation();  // Para evitar que el clic dentro del input cierre la edición
                    });
                }
            }
        });
        
    
        // events change update name poligons o area
        cardMap.addEventListener("change", (e) => {
            const target = e.target;
  
            // Verificar si el evento viene de un input dentro de container-inputs
            if (target.closest(".container-inputs") && target.tagName === "INPUT") {
                const parentCard = target.closest(".custom-card-map");
                const mapId = parentCard.getAttribute("data-id");
                const poligonId = target.getAttribute("data-poligon");
                const inputValue = target.value;
                // Datos a enviar en la petición
                const serializedData = {
                    mapId: mapId,
                    poligonId: poligonId,
                    value: inputValue,
                };

                objets.editAreaMap(e,serializedData);

            }
        });

        
    }






    const objets = {
         
        actionEditAlias: function( e, newValue,mapId){

            const serializedData = {
                'alias':newValue
            }

            let url =`/map/update-alias/${mapId}`;
            utils.requestPOST(url, serializedData)
            .then(response => {
                
                if (response.status==200) {
                    Swal.fire({
                        title: "Operation Completed Successfully",
                        text: "The operation was completed successfully",
                        icon: "success",
                        confirmButtonColor: "#4CAF50", 
                    });
                }
            
            })
            
            .catch(error => {
                console.error('Error occurred while updating airport status:', error);
            });
        },

        actionStatus:function(e,mapId,newStatus){

            const serializedData = {
                'status':newStatus
            }

            let url =`/map/update-status/${mapId}`;
            utils.requestPOST(url, serializedData)
            .then(response => {
                
                if (response.status==200) {
                    Swal.fire({
                        title: "Operation Completed Successfully",
                        text: "The operation was completed successfully",
                        icon: "success",
                        confirmButtonColor: "#4CAF50", 
                    });

                    //progres 
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
                }
            
            })
            // Handle errors that occur during the actionStatus call
            .catch(error => {
                console.error('Error occurred while updating airport status:', error);
                
            });

        },

        editAreaMap:function(e,serializedData){

            //exeptions rules
            const exeption = [] ;
            const validations = utils.validateForm(serializedData,exeption); 
  
            if (validations) {
                  //request PUT
                  let url =`/map/update/area/${serializedData.mapId}`;
                  utils.requestPUT(url, serializedData)
                  .then(response => {
                    
                    if (response.status===200) {
                        Swal.fire({
                            title: "Operation Completed Successfully",
                            text: "The operation was completed successfully",
                            icon: "success",
                            confirmButtonColor: "#4CAF50", 
                        });
                    }
  
                  })
                  .catch(error => {
                    console.error('Error occurred while updating airport status:', error);
                  });
            }
        },
        
        editMap:function(e,id){
            e.preventDefault();

            const editUrl = `/map/edit/${id}`; 
            window.location.href = editUrl;

        },

        destroyMap:function(e,id){

            let url =`/map/destroy/${id}`;
            let serializedData = {};
            utils.requestDELETE(url, serializedData)
            .then(response => {
                if (response.status==200) {
                    const mapDiv = document.querySelector(`.custom-card-map[data-id="${id}"]`);
                    if (mapDiv) {
                        mapDiv.remove();
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
                    
                }
            })

            .catch(error => {
                
                console.error('Error:', error);
            });
        },

    }

}