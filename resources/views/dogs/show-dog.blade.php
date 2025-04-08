<x-app-layout>


   <h1 class="is-size-4">Dog Details</h1>

   <div class="card is-flex is-flex-direction-row is-justify-content-flex-end mb-4" style="box-shadow: none;">
      <button class="button has-background-warning has-text-white mr-2">Certificate</button>
      <a href="{{ route('pediree.showPedigree', ['id' => $dog['dog']->dog_hash]) }}"><button class="button has-text-white has-background-warning">Show Pedigree</button></a>
   </div>
   @if(!empty($dog))

   <x-card-details-dog :dogDetails="$dog"/>


   @else
   <div class="notification is-warning">
      No dog information available.
   </div>
   @endif
</x-app-layout>