
@props(['dog'])

@if(isset($dog) && !empty($dog))

<div class="container-details card">
   <div class="container-imagen">
      <img src="{{ asset('assets/img/dogs-image.jpg') }}" />
   </div>
   <div class="content-details">
      <p class="title is-3 mt-2">{{ $dog['aliasDog']}} </p>
      <ul class="is-flex is-align-items-center mb-3">
         <li>
            <span>{{$dog['owner']['name']}}</span><br>
            <small>{{__('messages.main.dogDetails.owner')}}</small>
         </li>
         <li>
            <span>{{$dog['number']}}</span><br>
            <small>{{__('messages.main.dogDetails.regNo')}}</small>
         </li>
      </ul>
      <ul class="is-flex is-align-items-center mb-3">

         <li>
            <span>{{$dog['breed']}}</span><br>
            <small>{{__('messages.main.dogDetails.breed')}}</small>
         </li>
         <li>
            <span>{{$dog['color']}}</span><br>
            <small>{{__('messages.main.dogDetails.color')}}</small>
         </li>
         <li>
            <span>{{$dog['sex']=='M'?'Male':'Female' }}</span><br>
            <small>{{__('messages.main.dogDetails.sex')}}</small>
         </li>
         <li>
            <span>{{ \Carbon\Carbon::parse($dog['birthdate'])->format('F d, Y') }}</span><br>
            <small>{{__('messages.main.dogDetails.date')}}</small>
         </li>
         <li>
            <span>{{ \Carbon\Carbon::parse($dog['date'])->format('F d, Y') }}</span><br>
            <small>{{__('messages.main.dogDetails.registered')}}</small>
         </li>
      </ul>
   </div>
</div>
@endif

