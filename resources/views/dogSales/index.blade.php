<x-app-layout>

<section class="section">
  <div class="container">

    @php
      $count = count($pendingSale);
    @endphp

    <h3 class="title is-4">
      🔔 You have {{ $count }} dog{{ $count != 1 ? 's' : '' }} pending registration
    </h3>

    <p class="has-text-grey mb-4">
      Register {{ $count != 1 ? 'them' : 'it' }} now if you purchased or received {{ $count != 1 ? 'them' : 'it' }} as a gift.
    </p>

    <!-- Secure POST form -->
    <form id="registerForm" action="{{ route('salesDogs.registerOwnership') }}" method="POST">
      @csrf

      <div class="columns is-multiline">

        @foreach($pendingSale as $sale)
          <div class="column is-one-third">
            <div class="card">
              <div class="card-content">
                <p class="title is-5 mb-1">{{ $sale->dog->name }}</p>
                <p class="subtitle is-6 has-text-grey mb-2">{{ $sale->dog->reg_no }}</p>
                <p class="is-size-7 mb-1"><strong>Breed:</strong> {{ $sale->dog->breed }}</p>
                <input type="hidden" name="sales[]" value="{{ $sale->sale_id }}">
              </div>
            </div>
          </div>
        @endforeach

      </div>

      <div class="mt-4">
        <button type="submit" id="submitBtn" class="button is-link is-medium">
          🐾 Register under my name
        </button>
      </div>
    </form>
  </div>
</section>

@push('scripts')



<script>
   document.addEventListener('DOMContentLoaded', () => {
      
   const form = document.getElementById('registerForm');
   const submitBtn = document.getElementById('submitBtn');

      form.addEventListener('submit', async (e) => {
         e.preventDefault();

         const formData = new FormData(form);
         submitBtn.classList.add('is-loading');

         try {

            const response = await fetch(form.action, {
               method: "POST",
               headers: {
                  "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
               },
               body: formData
            });

            const result = await response.json();

            if (response.ok) {

               Swal.fire({
                  icon: 'success',
                  title: 'Registration complete!',
                  text: result.message || 'The dogs have been successfully registered under your name.',
                  confirmButtonText: 'OK',
                  timer: 2500,
                  timerProgressBar: true
               }).then(() => {
                  // Optionally reload after success
                  location.reload();
               });

               let id = result.data.id_hash;
               const editUrl = `/payments/pay/${id}/sale`;
               window.location.href = editUrl;
					
               form.reset();
               
            } else {

               Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: result.message || 'Something went wrong. Please try again later.',
                  confirmButtonText: 'Close'
               });
            }

         } catch (error) {

            Swal.fire({
            icon: 'error',
            title: 'Connection error',
            text: 'A network error occurred. Please check your internet connection and try again.',
            confirmButtonText: 'Close'
            });
            
         } finally {
            submitBtn.classList.remove('is-loading');
         }
      });

   });
</script>

@endpush

</x-app-layout>