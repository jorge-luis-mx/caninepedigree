<div class="column">
   <!-- Serch dam dog -->
   <div class="field searchDam">
      <label class="label" for="dam">{{__('messages.main.formDog.dam')}}</label>
      <div class="is-flex is-justify-content-flex-end mt-2">
         <div class="is-hidden damButtonWrapper">
            <button type="button" class="button fancy-btn toggle-btn" data-type="dam">
               <span>➕ Not In PB (Dam)</span>
            </button>
         </div>
      </div>
      <div class="search-dam is-flex is-align-items-center">
         <div class="control has-icons-left"  style="width: 100%;">
            <input
               class="input dam"
               type="text"
               name="dam"
               id="dam"
               placeholder="">
            <!-- Campo oculto para el dog_id -->
            <input type="hidden" class="dam_id" name="dam_id">
            <span class="icon is-small is-left">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M10 2a8 8 0 1 1-5.293 14.293l-3.147 3.147a1 1 0 0 1-1.415-1.414l3.147-3.147A8 8 0 0 1 10 2zm0 2a6 6 0 1 0 0 12A6 6 0 0 0 10 4z" />
               </svg>
            </span>
         </div>
         <button type="button" class="button btn-search-dam  no-radius-left" style="background-color: #fdcd8a;color:#450b03;">
            Search Dam
         </button>
      </div>
   </div>
   <!-- Contenedor para los resultados -->
   <div id="damResults" style="display: none;"></div>
   
   <div class="is-hidden damMail">
      <!-- Email dam dog -->
      <div class="field">
         <label class="label" for="dam_email">{{__('messages.main.formDog.damEmail')}}</label>
         <div class="control has-icons-left">
            <input
               class="input"
               type="email"
               name="dam_email"
               id="dam_email"
               placeholder="{{__('messages.main.formDog.placeholderDamEmail')}}">
            <span class="icon is-small is-left">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M2 4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4zm2 0v1.6l8 4.8l8-4.8V4H4zm16 2.4l-8 4.8l-8-4.8V18h16V6.4z" />
               </svg>
            </span>
         </div>
      </div>

      <div class="field">
         <label class="label" for="description">{{__('messages.main.formDog.noteDam')}}</label>
         <div class="control">
            <textarea
               class="textarea"
               name="descriptionDam"
               id="descriptionDam"
               placeholder="{{__('messages.main.formDog.noteDamPlaceholder')}}"></textarea>
         </div>
      </div>
      
   </div>
</div>