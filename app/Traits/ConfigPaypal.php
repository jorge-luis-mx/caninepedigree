<?php

namespace App\Traits;


trait ConfigPaypal
{

   function configPaypal()
   {

      $config = [
         'URL' => [
            'base' => $baseUrl = config('app.url'),
            'services' => [
               'orderCreate' => '/payments/paypal/create',
               'orderGet' => 'api/getOrderDetails.php',
               'orderPatch' => 'api/patchOrder.php',
               'orderCapture' => '/payments/paypal/capture'
            ],
            'redirectsUrls' => [
               'returnUrl' => '/payments/paypal/paid',
               'cancelUrl' => '/paid?ppp=error',
               'pendingUrl' => '/paid?ppp=pending',
            ]
         ],
         'PAYPAL_ENV' =>  'production',
         'CREATE_ORDER_URL' => null,
         'CONFIRM_ORDER_URL' => '/payments/paypal/payed',
         'PENDING_ORDER_URL' => '/payments/paypal/payed-pending'
      ];

      return $config;
   }

}
