<x-app-layout>

<h1 class="is-size-4">Pedigree</h1>

<div class="card is-flex is-flex-direction-row is-justify-content-flex-end mb-4" style="box-shadow: none;">
   <button class="button has-background-warning has-text-white mr-2">Certificate</button>
   
</div>

<x-card-details-dog :dogDetails="$pedigree"/>
<x-card-pedigree :pedigree="$pedigree"/>

</x-app-layout>