<x-app-layout>

   <h1 class="is-size-4">{{__('messages.main.dogDetails.title')}}</h1>

   @if(!empty($dog))



      <div class="card buttons-container">
         <a href="{{ route('dog.sales.create',['id' => $dog['id']])}}">
            <button class="button has-text-white has-background-warning">Dog sale</button>
         </a>
         @if($dog['sex']==='F')
            <a href="{{ route('puppies.index',['id' => $dog['id']] )}}">
               <button class="button has-text-white has-background-warning">Add Puppies</button>
            </a>
            <a href="{{ route('breeding.create',['id' => $dog['id']] )}}">
               <button class="button has-text-white has-background-warning">Add Breeding</button>
            </a>
         @endif
         <!-- <a target="_blank" href="{{ route('certificates.pdf', ['id' => $dog['id'],'type'=>'registration']) }}">
            <button class="button has-text-white has-background-warning">{{__('messages.main.dogDetails.btnRegistration')}}</button>
         </a>
         <a target="_blank" href="{{ route('certificates.pdf', ['id' => $dog['id'],'type'=>'pedigree']) }}">
            <button class="button has-text-white has-background-warning">{{__('messages.main.dogDetails.btnPedigree')}}</button>
         </a> -->

         <a href="#" 
            data-id="{{ $dog['id'] }}" data-type="registration" onclick="generateCertificateFromData(this)">
            <button class="button has-text-white has-background-warning">{{ __('messages.main.dogDetails.btnRegistration') }}</button>
            
         </a>

         <a href="#"
            data-id="{{ $dog['id'] }}" data-type="pedigree" onclick="generateCertificateFromData(this)">
            
            <button class="button has-text-white has-background-warning">{{ __('messages.main.dogDetails.btnPedigree') }}</button>
         </a>

      </div>

   <x-card-details-dog :dog="$dog"/>


   @else
   <div class="notification is-warning">
      No dog information available.
   </div>
   @endif

<script>
function generateCertificateFromData(el) {
    let dogId = el.dataset.id;
    let type = el.dataset.type;

    let includeFlag = confirm("Do you want to print the certificate with the Kennel Name?");
    let flagValue = includeFlag ? 1 : 0;
    let url = `/certificates/${dogId}/pdf/${type}?flag=${flagValue}`;

    window.open(url, '_blank');
}
</script>



</x-app-layout>