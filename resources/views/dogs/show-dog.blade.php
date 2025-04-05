<x-app-layout>


   <h1 class="is-size-4">Dog Details</h1>

   <div class="card is-flex is-flex-direction-row is-justify-content-flex-end mb-4" style="box-shadow: none;">
      <button class="button has-background-warning has-text-white mr-2">Certificate</button>
      <a href="{{ route('pediree.showPedigree', ['id' => $dog->dog_id]) }}"><button class="button has-text-white has-background-warning">Show Pedigree</button></a>
   </div>
   @if(!empty($dog))

   <x-card-details-dog :dogDetails="$dog"/>

   <!-- <div class="card" style="box-shadow: none;">

      <div class="container-details">
         <div class="imagen-details">
            <figure class="image is-128x128 is-3by2">
               <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/000_American_Pit_Bull_Terrier.jpg/1024px-000_American_Pit_Bull_Terrier.jpg"
                  alt="Photo of {{ $dog->name }}">
            </figure>
         </div>
         <div class="content-details">
            <p class="title is-3 mt-2">{{ $dog->name }}</p>
            <p><span>{{ $dog->reg_no }}</span><br> <small style="font-style: italic; color:#b1b1b2;">Reg.No</small></p>
            <ul class="is-flex is-align-items-center mb-3" style="list-style: none; margin: 0; padding: 0; gap: 60px;">
               <li><span>{{ $dog->breed }}</span><br><small style="font-style: italic; color:#b1b1b2;">Breed</small></li>
               <li><span>{{ ucfirst($dog->sex)=='M'?'Macho':'Hembra' }}</span><br><small style="font-style: italic; color:#b1b1b2;">Sex</small></li>
               <li><span>{{ $dog->color }}</span><br><small style="font-style: italic; color:#b1b1b2;">Color</small></li>
               <li><span>{{ $dog->birthdate->format('F j, Y')}}</span><br><small style="font-style: italic; color:#b1b1b2;">Date of Birth</small></li>
               <li><span>{{ $dog->currentOwner->name}}</span><br><small style="font-style: italic; color:#b1b1b2;">Owner</small></li>
               <li><span>{{ \Carbon\Carbon::parse($dog->created_at)->format('F j, Y') }}</span><br><small style="font-style: italic; color:#b1b1b2;">Registered On</small></li>
            </ul>
         </div>

      </div>
   </div> -->
   @else
   <div class="notification is-warning">
      No dog information available.
   </div>
   @endif
</x-app-layout>