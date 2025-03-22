import { Utils } from './utils.js'; 

export  function profile() {

    const utils = new Utils();
    
    document.addEventListener('DOMContentLoaded', function() {

        let btn_delete_account = document.getElementById('btn-delete-account');
        let form_delete_account = document.getElementById('form-delete-account');

        let btnProfilePayment = document.getElementById('btnProfilePayment');
        

        if (btn_delete_account) {
            
            btn_delete_account.addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: "Are you sure you want to delete your account?",
                    text: "Please note that deleting your account from the platform will be done permanently, i.e. all data related to your account will be physically deleted, consider disabling your account if you are looking for a temporary deletion.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#4CAF50",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Action!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        form_delete_account.submit();
                    }
                });

            });

        }



        //PAYMENT INFORMATION

        // Seleccionar todas las plataformas
        const platforms = document.querySelectorAll(".platform");
        let selectedPlatform = "Paypal"; // Paypal como valor por defecto

        // Al cargar la página, marcar la plataforma por defecto
        const defaultPlatform = document.querySelector(".platform.is-active");
        if (defaultPlatform) {
            selectedPlatform = defaultPlatform.querySelector("strong").innerText.trim();
        }

        // Agregar evento de clic a cada plataforma
        platforms.forEach((platform) => {
            platform.addEventListener("click", () => {
                // Remover la clase 'is-active' de todas las plataformas
                platforms.forEach(p => p.classList.remove("is-active"));
                
                // Agregar la clase 'is-active' a la plataforma seleccionada
                platform.classList.add("is-active");
                
                // Guardar la plataforma seleccionada
                selectedPlatform = platform.querySelector("strong").innerText.trim();
            });
        });




        if (btnProfilePayment) {

            btnProfilePayment.addEventListener('click',function (e) {
                e.preventDefault();

                objets.profilePayment(e,selectedPlatform);
                
            });
        }
        
    });




    const objets = {
         
        //PAYMENT INFORMATION
        profilePayment: function(e,selectedPlatform) {

            let formProfilePayment = document.getElementById('formProfilePayment');
            
            const serializedData  = utils.serializeForm(formProfilePayment);
            serializedData.selectedPlatform = selectedPlatform;
           
            const exeption = [] ;
            const validations = utils.validateForm(serializedData,exeption); 

            if (validations) {

                
                let url =`/profile/payment`;
                utils.requestPUT(url, serializedData)
                .then(response => {
                   
                    if (response.status === 200) {

                        Swal.fire({
                            title: response.message,
                            icon: "success",
                            confirmButtonColor: "#4CAF50", 
                        });
                    }


                })

                .catch(error => {

                });
            }


        }

    }


}