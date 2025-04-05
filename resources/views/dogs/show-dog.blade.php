<x-app-layout>


   <h1 class="is-size-4">Dog Profile</h1>

   <div class="columns is-multiline">



      <div class="column">

         <div class="container-btn-actions is-flex is-justify-content-flex-end is-align-items-center m-2">
            <button class="button is-link mr-2">Generate Certificate</button>
            
            <a href="{{ route('pediree.showPedigree', ['id' => $dog->dog_id]) }}"><button class="button is-link">Generate Pedigree</button></a>
         </div>


         @if(!empty($dog))
            <div class="card mt-5" style="box-shadow: none;">
               <div class="card-content">
                     <!-- <div class="media">
                        <div class="media-left">
                           <figure class="image is-128x128">
                                 <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/000_American_Pit_Bull_Terrier.jpg/1024px-000_American_Pit_Bull_Terrier.jpg" 
                                    alt="Photo of {{ $dog->name }}">
                           </figure>
                        </div>
                        <div class="media-content">
                           <p class="title is-3">{{ $dog->name }}</p>
                           <p class="subtitle is-6 mt-3"><strong>{{ $dog->breed }}</strong> <br><small style="font-style: italic; color:#b1b1b2;">Breed</small> </p>
                        </div>
                     </div> -->
                     <div class="header is-flex">
                        <figure class="image is-128x128 is-3by2">
                                 <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/000_American_Pit_Bull_Terrier.jpg/1024px-000_American_Pit_Bull_Terrier.jpg" 
                                    alt="Photo of {{ $dog->name }}">
                        </figure>
                        <div class="media-content ml-3">
                           <p class="title is-3">{{ $dog->name }}</p>
                           <p class="subtitle is-6 mt-3"><strong>{{ $dog->breed }}</strong> <br><small style="font-style: italic; color:#b1b1b2;">Breed</small> </p>
                        </div>
                     </div>
                     <div class="details mt-5">
                        <p><span>{{ $dog->reg_no }}</span><br> <small style="font-style: italic; color:#b1b1b2;">Reg.No</small></p>
                        <ul class="is-flex is-align-items-center mb-3" style="list-style: none; margin: 0; padding: 0; gap: 60px;">
                           <li style="text-align: left;"><span>{{ ucfirst($dog->sex)=='M'?'Macho':'Hembra' }}</span><br><small style="font-style: italic; color:#b1b1b2;">Sex</small></li>
                           <li style="text-align: left;"><span>{{ $dog->color }}</span><br><small style="font-style: italic; color:#b1b1b2;">Color</small></li>
                           <li style="text-align: left;"><span>{{ $dog->birthdate->format('F j, Y')}}</span><br><small style="font-style: italic; color:#b1b1b2;">Date of Birth</small></li>
                           <li style="text-align: left;"><span>{{ $dog->currentOwner->name}}</span><br><small style="font-style: italic; color:#b1b1b2;">Owner</small></li>
                           <li style="text-align: left;"><span>{{ \Carbon\Carbon::parse($dog->created_at)->format('F j, Y') }}</span><br><small style="font-style: italic; color:#b1b1b2;">Registered On</small></li>
                        </ul>
                        <!-- <ul class="is-flex is-align-items-center mt-4" style="list-style: none; margin: 0; padding: 0; gap: 60px; ">
                           <li style="text-align: left;"><span>{{ $dog->currentOwner->name}}</span><br><small style="font-style: italic; color:#b1b1b2;">Owner</small></li>
                           <li style="text-align: left;"><span>{{ \Carbon\Carbon::parse($dog->created_at)->format('F j, Y') }}</span><br><small style="font-style: italic; color:#b1b1b2;">Registered On</small></li>
                        </ul> -->
                     </div>

               </div>
            </div>
         @else
            <div class="notification is-warning">
                  No dog information available.
            </div>
         @endif



      </div>
   </div>

</x-app-layout>