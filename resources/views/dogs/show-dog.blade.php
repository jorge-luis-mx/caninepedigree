<x-app-layout>

   <h1 class="is-size-4">{{__('messages.main.dogDetails.title')}}</h1>

   @if(!empty($dog))



   <div class="card is-flex is-flex-direction-row is-justify-content-flex-end mb-4" style="box-shadow: none;">
      <a target="_blank" href="{{ route('certificates.pdf', ['id' => $dog['id'],'type'=>'registration']) }}"><button class="button has-text-white has-background-warning">{{__('messages.main.dogDetails.btnRegistration')}}</button></a>
      <a target="_blank" href="{{ route('certificates.pdf', ['id' => $dog['id'],'type'=>'pedigree']) }}"><button class="button has-text-white has-background-warning">{{__('messages.main.dogDetails.btnPedigree')}}</button></a>
   </div>
   <x-card-details-dog :dog="$dog"/>


   @else
   <div class="notification is-warning">
      No dog information available.
   </div>
   @endif
</x-app-layout>