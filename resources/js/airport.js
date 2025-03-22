
import { Utils } from './utils.js'; 

export  function airport() {

    const utils = new Utils();
    const btn_airport_store = document.getElementById('btn_airport_store');
    const btn_airport_update = document.getElementById('btn_airport_update');
    

    if (btn_airport_update!= null) {
        btn_airport_update.addEventListener("click", (e) => {
            objets.updateAirport(e);
        });
    }
    

    const elementsCard = document.getElementById('airports');
    if (elementsCard) {

        elementsCard.addEventListener('click', function(e) {
            
            if (e.target.closest('#btn_airport_store')) {
                 
                objets.storeAirport(e);
            }
                
            
        });

        elementsCard.addEventListener("click", (e) => {

            const editButton = e.target.closest('.btn-edit');
            if (editButton) {
                let id = editButton.getAttribute('data-id');
                let hashedId = CryptoJS.MD5(id).toString();
                objets.editAirport(e, hashedId);
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

                      objets.destroyAirport(e, id);
                    }
                  });

                
            }
    
            const mapButton = e.target.closest('.btn-map');
            if (mapButton) {
                let id = mapButton.getAttribute('data-id');
                let hashedId = CryptoJS.MD5(id).toString();
                objets.mapAirport(e, hashedId);
            }

        });

        // Listen for change events on the elementsCard; if a checkbox with the class 'checkbox-airport' is changed,
        elementsCard.addEventListener('change', (e) => {
            if (e.target.classList.contains('checkbox-airport')) {
                const airportId = e.target.dataset.id;  
                const newStatus = e.target.checked ? 1 : 0;  
                objets.actionStatus(e,airportId,newStatus);
            }
        });

    }



    const objets = {

        storeAirport: function(e) {
            e.preventDefault();


            const form = document.getElementById('form_airport');
            const inputElement = document.getElementById('inputAirport');
            const notification = document.getElementById('dynamic-notification');

            const serializedData  = utils.serializeForm(form);
            serializedData.api_alias = inputElement.getAttribute('data-name');
            serializedData.api_ref = inputElement.getAttribute('data-reference');
            serializedData.provider = inputElement.getAttribute('data-provider');
  

            //exeptions rules
            const exeption = [] ;
            const validations = utils.validateForm(serializedData,exeption); 

            if (validations) {
                //request POST
                let url ='/airport/store';
                utils.requestPOST(url, serializedData)
                .then(response => {
                    
                    // notification.style.display = 'block';  
                    // notification.className = `notification ${response.status === 200 ? 'is-success is-light' : 'is-danger is-light'}`;  
                    // notification.innerText = response.message;  
                    // setTimeout(() => {
                    //     notification.style.display = 'none';
                    //     window.location.href = '/airport';
                    // }, 1000);  
                    
                    if (response.status === 200 ) {

                        Swal.fire({
                            title: "Registered Airport",
                            text: "The Airport identified as "+serializedData.api_alias+" has been successfully registered in your account.",
                            icon: "success",
                            //showConfirmButton: false,
                            confirmButtonColor: "#4CAF50", 
                            //timer: 1500,
                        }).then(() => {
                            window.location.href = '/airport';

                        });
                       
                    }else{
                        Swal.fire({
                            title: "Error Registered Airport",
                            text: response.message,
                            icon: "error",
                            confirmButtonColor: "#4CAF50", 
                            
                        }).then((result) => {
                            if (result.isConfirmed) {form.reset();}
                        });
                    }

                
                })
                .catch(error => {
                    notification.style.display = 'block';
                    notification.className = 'notification is-danger';
                    notification.innerText = 'An error occurred. Please try again.';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 1000);


                });
            }

        },

        editAirport:function(e,id){
            e.preventDefault();

           
            const editUrl = `/airport/edit/${id}`; 
            window.location.href = editUrl;

            //request GET
            // let url =`/airport/show/${id}`;
            // let serializedData = {};
            // utils.requestGET(url, serializedData)
            // .then(response => {
            //     console.log(response);
            // })

            // .catch(error => {
                
            //     console.error('Error:', error);
            // });
        },

        updateAirport:function(e,id){
            e.preventDefault();

            const form = document.getElementById('form_airport_update');
            const inputElement = document.getElementById('inputAirport');
            const notification = document.getElementById('dynamic-notification');

            const serializedData  = utils.serializeForm(form);
            serializedData.api_alias = inputElement.getAttribute('data-name');
            serializedData.api_ref = inputElement.getAttribute('data-reference');
            serializedData.api_airport = inputElement.getAttribute('data-airport');
  

            //exeptions rules
            const exeption = ['inputAirport'] ;
            const validations = utils.validateForm(serializedData,exeption); 


            if (validations) {
                //request PUT
                let url =`/airport/update/${serializedData.api_airport}`;
                utils.requestPUT(url, serializedData)
                .then(response => {
                    //form.reset(); 
                    if (response.status === 200) {
                        
                        Swal.fire({
                            title: "Successful Update",
                            text: "The Airport identified as "+serializedData.api_alias+" has been successfully upgraded",
                            icon: "success",
                            confirmButtonText: "OK",
                            showConfirmButton: false,
                            confirmButtonColor: "#4CAF50", 
                            timer: 1500,
                        }).then(() => {
                            window.location.href = '/airport';
                            // if (result.isConfirmed) {
                            //     window.location.href = '/airport';
                            // }else{
                            //     window.location.href = '/airport';
                            // }
                        });
                          
                        
                    }else{
                        alert('Update Failed!')
                    }
                    // document.getElementById('aliasAirport').value = '';
                    // notification.style.display = 'block';  
                    // notification.className = `notification ${response.status === 200 ? 'is-success is-light' : 'is-danger is-light'}`;  
                    // notification.innerText = response.message;  
                    // setTimeout(() => {
                    //     notification.style.display = 'none';
                    //     window.location.href = '/airport';
                    // }, 300);  

                
                })
                .catch(error => {


                    // notification.style.display = 'block';
                    // notification.className = 'notification is-danger';
                    // notification.innerText = 'An error occurred. Please try again.';
                    // setTimeout(() => {
                    //     notification.style.display = 'none';
                    // }, 300);


                });
            }


        },

        destroyAirport:function(e,id){

            let url =`/airport/${id}`;
            let serializedData = {};
            utils.requestDELETE(url, serializedData)
            .then(response => {
                if (response.status==200) {
                    const airportDiv = document.querySelector(`.custom-card-airport[data-id="${id}"]`);
                    if (airportDiv) {
                        airportDiv.remove();
        
                    }

                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
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
                    alert('Error al eliminar el registro');
                }
            })

            .catch(error => {
                
                console.error('Error:', error);
            });
        },


        mapAirport:function(e,id){

            const mapUrl = `/map/create/${id}`; 
            window.location.href = mapUrl;
        },

        actionStatus:function(e,airportId,newStatus){

            const serializedData = {
                'status':newStatus
            }

            let url =`/airport/update-status/${airportId}`;
            utils.requestPOST(url, serializedData)
            .then(response => {
                
                Swal.fire({
                    title: "Operation Completed Successfully",
                    text: "The operation was completed successfully",
                    icon: "success",
                    confirmButtonColor: "#4CAF50", 
                }).then(() => {
                    location.reload();

                });
                // alert('The operation was completed successfully');
            
            })
            // Handle errors that occur during the actionStatus call
            .catch(error => {
                console.error('Error occurred while updating airport status:', error);
                alert('An error occurred while updating the airport status. Please try again later.');
            });

        }
        
    }


}