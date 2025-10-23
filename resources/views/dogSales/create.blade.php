<x-app-layout>

   <section class="section">
      <div class="container">
         <h3 class="title is-3">Sell or Transfer Dog: {{ $dog->name }}</h3>

         <form id="dog-sale-form" method="POST" action="{{ route('dog.sales.store') }}">
            @csrf
            <input type="hidden" name="dog_id" value="{{ $dog->dog_id }}">

            <div class="field">
               <label class="label">Email of new owner</label>
               <div class="control">
                  <input class="input" type="email" name="buyer_email" placeholder="e.g. buyer@example.com" required>
               </div>
            </div>

            <div class="field">
               <label class="label">Price (optional)</label>
               <div class="control">
                  <input class="input" type="number" name="price" step="0.01" placeholder="0.00" min="0">
               </div>
            </div>

            <div class="field">
               <label class="label">Payment method</label>
               <div class="control">
                  <div class="select is-fullwidth">
                     <select name="payment_method" required>
                        <option value="cash">Cash</option>
                        <!-- <option value="online">Online</option> -->
                        <option value="transfer">Bank Transfer</option>
                     </select>
                  </div>
               </div>
            </div>

            <div class="field mt-4 mb-4">
               <div class="control">
                  <button type="submit" class="button  mt-3 p-2 btn-general">
                     <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" viewBox="0 0 36 36">
                        <path fill="#fff" d="M22.28 26.07a1 1 0 0 0-.71.29L19 28.94V16.68a1 1 0 1 0-2 0v12.26l-2.57-2.57A1 1 0 0 0 13 27.78l5 5l5-5a1 1 0 0 0-.71-1.71Z" class="clr-i-outline--badged clr-i-outline-path-1--badged" />
                        <path fill="#fff" d="M19.87 4.69a8.8 8.8 0 0 1 2.68.42a7.5 7.5 0 0 1 .5-1.94a10.8 10.8 0 0 0-3.18-.48a10.47 10.47 0 0 0-9.6 6.1A9.65 9.65 0 0 0 10.89 28a3 3 0 0 1 0-2A7.65 7.65 0 0 1 11 10.74h.67l.23-.63a8.43 8.43 0 0 1 7.97-5.42" class="clr-i-outline--badged clr-i-outline-path-2--badged" />
                        <path fill="#fff" d="M30.92 13.44a7.1 7.1 0 0 1-2.63-.14v.23l-.08.72l.65.3A6 6 0 0 1 26.38 26h-1.29a3 3 0 0 1 0 2h1.28a8 8 0 0 0 4.54-14.61Z" class="clr-i-outline--badged clr-i-outline-path-3--badged" />
                        <circle cx="30" cy="6" r="5" fill="#fff" class="clr-i-outline--badged clr-i-outline-path-4--badged clr-i-badge" />
                        <path fill="none" d="M0 0h36v36H0z" />
                     </svg>
                     <span class="ml-1">Confirm sale</span>
                  </button>
               </div>
            </div>

         </form>
      </div>
   </section>

@push('scripts')
   <script>
      document.addEventListener('DOMContentLoaded', function () {
         const form = document.getElementById('dog-sale-form');

         form.addEventListener('submit', function (event) {
            event.preventDefault(); // Evita enviar el formulario inmediatamente

            Swal.fire({
                  title: 'Are you sure?',
                  text: "You are about to sell or transfer this dog. This action cannot be undone.",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#28a745',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, confirm sale',
                  cancelButtonText: 'Cancel'
            }).then((result) => {
                  if (result.isConfirmed) {
                     // Envía el formulario si confirma
                     form.submit();
                  }
            });
         });
      });
   </script>
@endpush
</x-app-layout>