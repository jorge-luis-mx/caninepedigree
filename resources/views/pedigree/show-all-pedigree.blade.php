<x-app-layout>
   
   <h1 class="is-size-4">{{__('messages.main.pedigree.title')}}</h1>

   @php
      $arrayRole = ['Admin', 'Administrator', 'Employee'];
   @endphp

   @if(in_array($role->name, $arrayRole))

      <div class="columns is-multiline">
         <div class="column">
            <div class="menu-add">
               <a href="{{ route('pedigree.create')}}">
                  <div class="card has-background-warning" style="width: 170px;box-shadow:none; padding: 8px 0px 8px 0px;" >
                     <div class="card-content" style="padding: 0; margin:0;">
                        <div class="is-flex is-justify-content-center">
                           <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon mr-1" viewBox="0 0 24 24"><path fill="#ffffff" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h4.2q.325-.9 1.088-1.45T12 1t1.713.55T14.8 3H19q.825 0 1.413.588T21 5v5.025q0 .425-.288.713t-.712.287t-.712-.288t-.288-.712V5H5v14h5q.425 0 .713.288T11 20t-.288.713T10 21zm0-3v1V5v6.075V11zm3-1h2.5q.425 0 .713-.288T11.5 16t-.288-.712T10.5 15H8q-.425 0-.712.288T7 16t.288.713T8 17m0-4h5q.425 0 .713-.288T14 12t-.288-.712T13 11H8q-.425 0-.712.288T7 12t.288.713T8 13m0-4h8q.425 0 .713-.288T17 8t-.288-.712T16 7H8q-.425 0-.712.288T7 8t.288.713T8 9m4-4.75q.325 0 .538-.213t.212-.537t-.213-.537T12 2.75t-.537.213t-.213.537t.213.538t.537.212M18 23q-2.075 0-3.537-1.463T13 18t1.463-3.537T18 13t3.538 1.463T23 18t-1.463 3.538T18 23m-.5-4.5v2q0 .2.15.35T18 21t.35-.15t.15-.35v-2h2q.2 0 .35-.15T21 18t-.15-.35t-.35-.15h-2v-2q0-.2-.15-.35T18 15t-.35.15t-.15.35v2h-2q-.2 0-.35.15T15 18t.15.35t.35.15z"/></svg>
                           <span class="is-block has-text-white">{{__('messages.main.pedigree.btnAddPedigree')}}</span>
                        </div>
                     </div>
                  </div>
               </a>

            </div>
         </div>
      </div>

   @endif


   <div class="container-search mt-5">
      <div class="field has-addons is-align-items-center">
         <div class="control is-expanded">
            <input class="input no-radius-right" type="text" name='dog' placeholder="{{__('messages.main.pedigree.placeInput')}}" autocomplete="off" />
         </div>
         <button class="button btn-search-dog-pedigree has-text-white no-radius-left" style="background-color: orange;">
            {{__('messages.main.pedigree.btnSearch')}}
         </button>
      </div>
   </div>
   <div class="container-render-dogs">
      <ul id="list-dogs" style="list-style: none;margin:0; padding:0; margin-top:15px;"></ul>
   </div>



</x-app-layout>