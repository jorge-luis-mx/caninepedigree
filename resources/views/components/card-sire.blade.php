<div class="column">

   <!-- Search sire dog -->
   <div class="field searchSire">
      <label class="label" for="sire">Search Sire</label>
      <div class="control has-icons-left">
         <input
            class="input sire"
            type="text"
            name="sire"
            id="sire"
            placeholder="Search sire...">
         <!-- Campo oculto para el dog_id -->
         <input type="hidden" class="sire_id" name="sire_id">
         <span class="icon is-small is-left">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
               <path fill="currentColor" d="M10 2a8 8 0 1 1-5.293 14.293l-3.147 3.147a1 1 0 0 1-1.415-1.414l3.147-3.147A8 8 0 0 1 10 2zm0 2a6 6 0 1 0 0 12A6 6 0 0 0 10 4z" />
            </svg>
         </span>
      </div>
   </div>
   <!-- Contenedor para mostrar los resultados de la búsqueda -->
   <div id="sireResults" style="display: none;"></div>


   <div class="is-hidden sireMail">
      <!-- Email sire dog -->
      <div class="field">
         <label class="label" for="sire_email">Sire Email</label>
         <div class="control has-icons-left">
            <input
               class="input"
               type="email"
               name="sire_email"
               id="sire_email"
               placeholder="Enter Sire's email">
            <span class="icon is-small is-left">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M2 4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4zm2 0v1.6l8 4.8l8-4.8V4H4zm16 2.4l-8 4.8l-8-4.8V18h16V6.4z" />
               </svg>
            </span>
         </div>
      </div>

      <div class="field">
         <label class="label" for="description">Additional Notes</label>
         <div class="control">
            <textarea
               class="textarea"
               name="descriptionSire"
               id="descriptionSire"
               placeholder="Enter additional details..."></textarea>
         </div>
      </div>
      
   </div>

</div>