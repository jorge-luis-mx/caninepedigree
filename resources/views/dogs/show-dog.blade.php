<x-app-layout>

   <h1 class="is-size-4">Dog Details</h1>

   @if(!empty($dog))



   <div class="card is-flex is-flex-direction-row is-justify-content-flex-end mb-4" style="box-shadow: none;">
      <a href="{{ route('pediree.generatePDF', ['id' => $dog['id'],'type'=>'registration']) }}"><button class="button has-text-white has-background-warning">Registration Certificate</button></a>
      <a href="{{ route('pediree.generatePDF', ['id' => $dog['id'],'type'=>'pedigree']) }}"><button class="button has-text-white has-background-warning">Pedigree Certificate</button></a>
   </div>
   <x-card-details-dog :dog="$dog"/>


   @else
   <div class="notification is-warning">
      No dog information available.
   </div>
   @endif
</x-app-layout>