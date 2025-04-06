<x-app-layout>

<section class="section">
   <div class="container">
      <div class="columns is-multiline">
         <div class="column is-two-thirds">
               <!-- Primary option -->
               <b>Pay with Credit and Debid cards</b>
               <div class="mt-2" id="card-button-container"></div>

               <!-- Alternative option -->
               <b>Pay with a Paypal account</b>
               <div class="mt-2" id="paypal-button-container"></div>
               <input type="" id="invoice" value="{{$dog->invoice}}" hidden>
         </div>

        <!-- SUMARY -->
        <div class="column">
            <section class="card">
               <div class="card-content">
                  <p class="title">Service</p>
                  <div class="field">
                     <p>{{$dog->payment[0]->type}}</p>
                  </div>
                  <div class="field mt-4">
                     <label class="label">{{$dog->name}}</label>
                  </div>
               </div>
               <section class="hero is-link p-2">
                  <div class="pl-3 pr-3 is-flex  is-justify-content-space-between">
                        <div>Total</div>
                        <div>{{$dog->payment[0]->amount}} MXN</div>
                  </div>
               </section>
            </section>
         </div>
      </div>
   </div>
</section>

@push('scripts')


<!-- Sandbox -->
<script src="https://www.paypal.com/sdk/js?client-id=AcnIDyl2_CkrcUup_vpY1Ttraiiu-B79GGuzh2IY2fIuKcH76N9-EQ_SMtjXNUPtcdEuq_uo35_YzaF5&currency=USD"></script>
<script>
   const elInvoice = document.getElementById('invoice');
   const csrfToken = document.getElementById('csrf_token').getAttribute('data-csrf_token');

   // init data paypal credit card
   const card = 'CARD';
   const paymentCard = {
      payment: card,
      funding: [card],
      invoice: elInvoice?.value ?? '',
      style: {
         color: 'black'
      },
      redirectsUrls: {
         returnUrl: '<?= $paypal['URL']["redirectsUrls"]["returnUrl"] ?>',
         cancelUrl: '<?=  $paypal['URL']["redirectsUrls"]["cancelUrl"] ?>',
         pendingUrl: '<?= $paypal['URL']["redirectsUrls"]["pendingUrl"] ?>',
      },
      services: {
         orderCreate: '<?= $paypal['URL']['services']['orderCreate']; ?>',
         orderCapture:'<?= $paypal['URL']['services']['orderCapture']; ?>'
      },
      confirmOrderUrl: '<?= $paypal['CONFIRM_ORDER_URL']; ?>',
      pendingOrderUrl: '<?= $paypal['PENDING_ORDER_URL']; ?>',
      transactionStatus: (order, redirect, payed) => {

      }
   };

   // init data paypal acount
   const paymentPaypal = JSON.parse(JSON.stringify(paymentCard));
   paymentPaypal.payment = 'PAYPAL';
   paymentPaypal.funding = [paymentPaypal.payment, 'PAYLATER']; // 'VENMO', 'CREDIT', 'PAYLATER'
   paymentPaypal.style.color = 'gold';

   const payment = [paymentCard, paymentPaypal];

   payment.forEach((pay) => {
      const methodLower = pay.payment.toLowerCase();

      pay.funding.forEach((funding) => {

         // init object paypal...
         var button = paypal.Buttons({
            style: {
               layout: 'vertical', // horizontal | vertical
               size: 'responsive', // medium | large | responsive
               shape: 'rect', // pill | rect
               color: pay.style.color, // gold | blue | silver | black,
               fundingicons: false, // true | false,
               tagline: false // true | false,
            },
            fundingSource: paypal.FUNDING[funding],
            createOrder: function() {

               let formData = new FormData();
               formData.append('method', methodLower);
               formData.append('payment', 'paypal');
               formData.append('invoice', pay.invoice);
               //formData.append('invoiceTrans', pay.invoiceTrans);
               formData.append('return_url', pay.redirectsUrls.returnUrl + '?commit=true');
               formData.append('cancel_url', pay.redirectsUrls.cancelUrl);

               return fetch(pay.services.orderCreate, {
                  method: 'POST',
                  body: formData,
                  headers:{
                     'X-CSRF-TOKEN': csrfToken
                  }
               }).then(function(response) {
                  return response.json();
               }).then(function(resJson) {

                  // console.log(resJson);
                  //console.log('Order ID: ' + resJson.data.id);
                  return resJson.data.id;
               });
            },
            onApprove: function(data, actions) {
               return fetch(
                  pay.services.orderCapture + '/' + data.orderID, {
                     method: 'GET'
                  }
               ).then(function(res) {
                  return res.json();
               }).then(function(res) {

                  
                  if (res.data) {

                     const transactionStatus = res.data.purchase_units[0].payments.captures[0].status;
                     if (transactionStatus == 'COMPLETED') {
                        // Fetch a backend para los eventos post venta

                        const paypalFee = res.data.purchase_units[0].payments.captures[0].seller_receivable_breakdown.paypal_fee;
                        const payerEmail = res.data.payer.email_address;
                        const paid = {
                           'amount': res.data.purchase_units[0].payments.captures[0].seller_receivable_breakdown.gross_amount.value,
                           'fee': res.data.purchase_units[0].payments.captures[0].seller_receivable_breakdown.paypal_fee.value,
                           'currency': res.data.purchase_units[0].payments.captures[0].seller_receivable_breakdown.gross_amount.currency_code,
                           'transaction': res.data.purchase_units[0].payments.captures[0].id
                        };

                        const payed = new FormData();
                        payed.append('invoice', pay.invoice);
                        //payed.append('invoiceTrans', pay.invoiceTrans);
                        payed.append('email', payerEmail);
                        payed.append('amount', paid.amount);
                        payed.append('currency', paid.currency);
                        payed.append('fee', paid.fee);
                        payed.append('transaction', paid.transaction);

                        payed.append('method', methodLower);


                        fetch(pay.confirmOrderUrl, {
                              method: "post",
                              body: payed,
                              headers:{
                                 'X-CSRF-TOKEN': csrfToken
                              }
                           })
                           .then(response => response.json())
                           .then(res => {

                              if (res.error == false) {

                                 location.href = pay.redirectsUrls.returnUrl;

                              } else {

                                 alert('Sorry, something happened and we were unable to process your payment successfully, please try again later');

                              }

                           });

                     } else if (transactionStatus == 'PENDING') {
                        // Solo mandarlo a Thank you page con la leyenda de que esta en proceso.
                        const payed = new FormData();
                        payed.append('invoice', pay.invoice);
                        //payed.append('invoiceTrans', pay.invoiceTrans);
                        payed.append('method', methodLower);


                        fetch(pay.pendingOrderUrl, {
                              method: "post",
                              body: payed,
                              headers:{
                                 'X-CSRF-TOKEN': csrfToken
                              }
                           })
                           .then(response => response.json())
                           .then(res => {

                              if (res.error == false) {

                                 location.href = pay.redirectsUrls.pendingUrl;

                              }

                           });

                     } else {
                        location.href = pay.redirectsUrls.cancelUrl;
                     }
                  }


               });
            }

         });
         // end object paypal...

         // Check if the button is eligible
         if (button.isEligible()) {
            // Render the standalone button for that funding source
            button.render('#' + methodLower + '-button-container');
         }

      })

   });
</script>

@endpush


</x-app-layout>