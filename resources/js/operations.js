import { Utils } from './utils.js'; 

export  function operations() {

    const utils = new Utils();

    document.addEventListener('DOMContentLoaded', function() {

        const cardsOperations = document.querySelectorAll('.card-content .card-operation');
       
        cardsOperations.forEach(cardOperation => {
            cardOperation.addEventListener('click', function(e) {
                let sale = cardOperation.getAttribute('data_sale');
                let hashedId = CryptoJS.MD5(sale).toString();

                objets.showSale(e,hashedId)

                
            });
        });
    

    });


    const objets = {

        
        showSale: function(e,hashedId) {
            e.preventDefault();

            const url = `/sales/view/${hashedId}`; 
            window.open(url, "_blank");

        }

    }


}