<x-app-layout>

   <h1 class="is-size-4">{{__('messages.main.dogDetails.title')}}</h1>

   @if(!empty($dog))



      <div class="card buttons-container">
         <a href="{{ route('dog.sales.create',['id' => $dog['id']])}}">
            <button class="button has-text-white has-background-warning">Dog sale</button>
         </a>
         @if($dog['sex']==='F')
            <a href="{{ route('puppies.index',['id' => $dog['id']] )}}">
               <button class="button has-text-white has-background-warning">Add Puppies</button>
            </a>
            <a href="{{ route('breeding.create',['id' => $dog['id']] )}}">
               <button class="button has-text-white has-background-warning">Add Breeding</button>
            </a>
         @endif
         <!-- <a target="_blank" href="{{ route('certificates.pdf', ['id' => $dog['id'],'type'=>'registration']) }}">
            <button class="button has-text-white has-background-warning">{{__('messages.main.dogDetails.btnRegistration')}}</button>
         </a>
         <a target="_blank" href="{{ route('certificates.pdf', ['id' => $dog['id'],'type'=>'pedigree']) }}">
            <button class="button has-text-white has-background-warning">{{__('messages.main.dogDetails.btnPedigree')}}</button>
         </a> -->

         <a href="#" 
            data-id="{{ $dog['id'] }}" data-type="registration" onclick="generateCertificateFromData(this)">
            <button class="button has-text-white has-background-warning">{{ __('messages.main.dogDetails.btnRegistration') }}</button>
            
         </a>

         <a href="#"
            data-id="{{ $dog['id'] }}" data-type="pedigree" onclick="generateCertificateFromData(this)">
            
            <button class="button has-text-white has-background-warning">{{ __('messages.main.dogDetails.btnPedigree') }}</button>
         </a>

      </div>

   <x-card-details-dog :dog="$dog"/>


   @else
   <div class="notification is-warning">
      No dog information available.
   </div>
   @endif
   
   @if($completedBreedings->count())
      <h3 class="title is-5 mt-4">Completed Breedings</h3>
      @foreach($completedBreedings as $breeding)
         <div class="box mb-4" style="border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
            <ul class="is-flex is-align-items-center is-justify-content-space-between is-flex-wrap-wrap" style="list-style: none; padding: 0; margin: 0;">
               <li>
                  @if($breeding->photos->count())
                     @php
                      $dog_id = ($breeding->male_dog_id === $dog['dog_id'])
                        ? ($breeding->femaleDog->dog_id ?? $breeding->maleDog->dog_id)
                        : ($breeding->maleDog->dog_id ?? $breeding->femaleDog->dog_id);

                        $dogPedigree = md5($dog_id);
                        $male = md5($breeding->male_dog_id);
                        $female = md5($breeding->femaleDog->dog_id)
                     @endphp
                  <a href="/pedigrees/{{ $dogPedigree}}">
                     <figure class="image" style="max-width: 130px;border: 1px solid #ddd; ">
                        @if ($mainPhoto = $breeding->photos->firstWhere('is_main', 1))
                           <img src="{{ asset($mainPhoto->photo_url) }}" 
                                 alt="Foto principal" 
                                 style="border-radius: 10px; width: 100%; height: auto; display: block;padding: 10px;">
                        @endif
                     </figure>
                  </a>

                  @else
                     <p class="has-text-grey-light is-italic">No photo available</p>
                  @endif
               </li>

               <li>
                  @if($breeding->male_dog_id === $dog['dog_id'])
                     <!-- <p class="has-text-link mb-1 is-flex is-flex-direction-column">
                        <strong>Female:</strong>
                        <a href="/pedigrees/{{ $female}}" 
                           class="has-text-link has-text-weight-semibold is-underlined">
                           {{ $breeding->femaleDog->name ?? 'Unknown' }}
                        </a>
                     </p> -->
                  @else
                     <!-- <p class="has-text-link mb-1 is-flex is-flex-direction-column">
                        <strong>Male:</strong>
                        <a href="/pedigrees/{{ $male }}" 
                           class="has-text-link has-text-weight-semibold is-underlined">
                           <span class="has-text-link">
                              {{ $breeding->maleDog->name ?? 'Unknown' }}
                           </span>
                        </a>
                     </p> -->
                  @endif
               </li>

               <li>
                  <p class="has-text-link mb-1 is-flex is-flex-direction-column">
                     <strong>Female:</strong>
                     <a href="/pedigrees/{{ $female}}" 
                        class="has-text-link has-text-weight-semibold is-underlined">
                        {{ $breeding->femaleDog->name ?? 'Unknown' }}
                     </a>
                  </p>
               </li>

               <li>
                  <p class="has-text-link mb-1 is-flex is-flex-direction-column">
                     <strong>Male:</strong>
                     <a href="/pedigrees/{{ $male }}" 
                        class="has-text-link has-text-weight-semibold is-underlined">
                        <span class="has-text-link">
                           {{ $breeding->maleDog->name ?? 'Unknown' }}
                        </span>
                     </a>
                  </p>
               </li>

               <li>
                  <p class="mb-0 is-flex is-flex-direction-column">
                     <strong>Breeding date:</strong>
                     <span class="has-text-grey-dark">
                        {{ $breeding->created_at->format('d/m/Y') }}
                     </span>
                  </p>
               </li>
            </ul>
         </div>
      @endforeach
   @endif

<script>
function generateCertificateFromData(el) {
    let dogId = el.dataset.id;
    let type = el.dataset.type;

    let includeFlag = confirm("Do you want to print the certificate with the Kennel Name?");
    let flagValue = includeFlag ? 1 : 0;
    let url = `/certificates/${dogId}/pdf/${type}?flag=${flagValue}`;

    window.open(url, '_blank');
}
</script>



</x-app-layout>