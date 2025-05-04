<x-app-layout>
   
   <h1 class="is-size-4">{{__('messages.main.pedigree.title')}}</h1>

   <div class="container-search mt-5">
      <div class="field has-addons is-align-items-center">
         <div class="control is-expanded">
            <input class="input no-radius-right" type="text" name='dog' placeholder="Buscar..." />
         </div>
         <button class="button btn-search-dog has-text-white no-radius-left" style="background-color: orange;">
            Search
         </button>
      </div>
   </div>
   <div class="container-render-dogs">
      <ul id="list-dogs" style="list-style: none;margin:0; padding:0; margin-top:15px;"></ul>
   </div>



</x-app-layout>