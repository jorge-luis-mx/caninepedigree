<x-app-layout>

   <h1 class="is-size-4">{{__('messages.main.dogs.title')}}</h1>

   <div class="columns is-multiline mt-4">

      <div class="column is-full">
         <div class="card" style="box-shadow: none;">
               <div class="card-content">
                  <!-- <h2 class="title is-4 mb-4 pb-3">{{__('messages.main.dogs.title')}}</h2> -->
                  <form action="{{ route('dogs.store') }}" method="post">
                     @csrf
                     @method('post')
                     
                     <div class="field mb-4">
                           <label class="label" for="name">Name</label>
                           <div class="control">
                              <input
                                 class="input"
                                 type="text"
                                 name="name"
                                 value=""
                                 >
                           </div>
                     </div>

                        <div class="columns is-multiline">
                              <div class="column">
                                 <div class="field mb-4">
                                    <label class="label" for="breed">Breed</label>
                                    <div class="control">
                                       <input
                                          class="input"
                                          type="text"
                                          name="breed"
                                          value=""
                                          >
                                    </div>
                                 </div>
                              </div>
                              <div class="column">
                                 <div class="field">
                                    <label class="label" for="phone">Color</label>
                                    <div class="control">
                                          <input
                                             class="input"
                                             type="text"
                                             name="color"
                                             value=""
                                             >
                                    </div>
                                 </div>
                              </div>

                              <div class="column">
                                 <div class="field">
                                    <label class="label" for="sex">Sex</label>
                                    <div class="control">
                                       <input
                                          class="input"
                                          type="text"
                                          name="sex"
                                          value=""
                                          >
                                    </div>
                                 </div>
                              </div>

                        </div>

                     <div class="columns is-multiline">



                        <div class="column is-one-fifth">
                           <div class="field">
                              <label class="label" for="birthdate">Date of Birth</label>
                                 <div class="control">
                                    <input
                                       class="input"
                                       type="date"
                                       name="birthdate"
                                       id="birthdate"
                                    >
                                 </div>
                           </div>
                        </div>

                        <div class="column">
                           <div class="columns is-multiline">

                              <div class="column">
                                 <!-- Serch sire dog -->
                                 <div class="field">
                                    <label class="label" for="sire">Sire</label>
                                    <div class="control has-icons-left">
                                       <input
                                          class="input"
                                          type="text"
                                          name="sire"
                                          id="sire"
                                          placeholder="Search sire..."
                                       >
                                       <span class="icon is-small is-left">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                             <path fill="currentColor" d="M10 2a8 8 0 1 1-5.293 14.293l-3.147 3.147a1 1 0 0 1-1.415-1.414l3.147-3.147A8 8 0 0 1 10 2zm0 2a6 6 0 1 0 0 12A6 6 0 0 0 10 4z"/>
                                          </svg>
                                       </span>
                                    </div>
                                 </div>
                                 <!-- Email sire dog -->
                                 <div class="field is-hidden">
                                    <label class="label" for="sire_email">Sire Email</label>
                                    <div class="control has-icons-left">
                                       <input
                                          class="input"
                                          type="email"
                                          name="sire_email"
                                          id="sire_email"
                                          placeholder="Enter Sire's email"
                                       >
                                       <span class="icon is-small is-left">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                             <path fill="currentColor" d="M2 4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4zm2 0v1.6l8 4.8l8-4.8V4H4zm16 2.4l-8 4.8l-8-4.8V18h16V6.4z"/>
                                          </svg>
                                       </span>
                                    </div>
                                 </div>

  
                              </div>

                              <div class="column">
                                 <!-- Serch dam dog -->
                                 <div class="field">
                                    <label class="label" for="dam">Dam</label>
                                    <div class="control has-icons-left">
                                       <input
                                          class="input"
                                          type="text"
                                          name="dam"
                                          id="dam"
                                          placeholder="Search dam..."
                                       >
                                       <span class="icon is-small is-left">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                             <path fill="currentColor" d="M10 2a8 8 0 1 1-5.293 14.293l-3.147 3.147a1 1 0 0 1-1.415-1.414l3.147-3.147A8 8 0 0 1 10 2zm0 2a6 6 0 1 0 0 12A6 6 0 0 0 10 4z"/>
                                          </svg>
                                       </span>
                                    </div>
                                 </div>
                                 <!-- Email dam dog -->
                                 <div class="field is-hidden">
                                    <label class="label" for="dam_email">Dam Email</label>
                                    <div class="control has-icons-left">
                                       <input
                                          class="input"
                                          type="email"
                                          name="dam_email"
                                          id="dam_email"
                                          placeholder="Enter Dam's email"
                                       >
                                       <span class="icon is-small is-left">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                             <path fill="currentColor" d="M2 4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4zm2 0v1.6l8 4.8l8-4.8V4H4zm16 2.4l-8 4.8l-8-4.8V18h16V6.4z"/>
                                          </svg>
                                       </span>
                                    </div>
                                 </div>

                              </div>

                           </div>
                        </div>

                     </div>



                        <!-- <div class="columns is-multiline">
                           <div class="column">
                              <div class="field">
                                 <label class="label" for="breeder">Breeder</label>
                                 <div class="control">
                                       <input
                                          class="input"
                                          type="text"
                                          name="breeder"
                                          value=""
                                          >
                                 </div>
                              </div>
                           </div>

                           <div class="column">
                              <div class="field">
                                 <label class="label" for="owner">Owner</label>
                                 <div class="control">
                                       <input
                                          class="input"
                                          type="text"
                                          name="breeder"
                                          value=""
                                          >
                                 </div>
                              </div>
                           </div>

                        </div> -->

                     <div class="field mt-4">
                           <div class="control">
                              <button type="submit" id="btnUpdateMap" class="button  mt-3 p-2 btn-general">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" viewBox="0 0 36 36"><path fill="#fff" d="M22.28 26.07a1 1 0 0 0-.71.29L19 28.94V16.68a1 1 0 1 0-2 0v12.26l-2.57-2.57A1 1 0 0 0 13 27.78l5 5l5-5a1 1 0 0 0-.71-1.71Z" class="clr-i-outline--badged clr-i-outline-path-1--badged"/><path fill="#fff" d="M19.87 4.69a8.8 8.8 0 0 1 2.68.42a7.5 7.5 0 0 1 .5-1.94a10.8 10.8 0 0 0-3.18-.48a10.47 10.47 0 0 0-9.6 6.1A9.65 9.65 0 0 0 10.89 28a3 3 0 0 1 0-2A7.65 7.65 0 0 1 11 10.74h.67l.23-.63a8.43 8.43 0 0 1 7.97-5.42" class="clr-i-outline--badged clr-i-outline-path-2--badged"/><path fill="#fff" d="M30.92 13.44a7.1 7.1 0 0 1-2.63-.14v.23l-.08.72l.65.3A6 6 0 0 1 26.38 26h-1.29a3 3 0 0 1 0 2h1.28a8 8 0 0 0 4.54-14.61Z" class="clr-i-outline--badged clr-i-outline-path-3--badged"/><circle cx="30" cy="6" r="5" fill="#fff" class="clr-i-outline--badged clr-i-outline-path-4--badged clr-i-badge"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                                 <span class="ml-1">{{ __('Save Dog') }}</span>
                              </button>
                           </div>
                     </div>
                  </form>
               </div>
         </div>
      </div>
   </div>


</x-app-layout>