
@props(['dog'])

@if(isset($dog) && !empty($dog))

<div class="container-details card">
   <div class="container-imagen">
      <img src="{{ asset('assets/img/dogs-image.jpg') }}" />
   </div>
   <div class="content-details">
      <p class="title is-3 mt-2">{{ $dog['name']}}</p>
      <ul class="is-flex is-align-items-center mb-3">
         <li>
            <span>{{$dog['owner']['name']}}</span><br>
            <small>Owner</small>
         </li>
         <li>
            <span>{{$dog['number']}}</span><br>
            <small>Reg.No</small>
         </li>
      </ul>
      <ul class="is-flex is-align-items-center mb-3">

         <li>
            <span>{{$dog['breed']}}</span><br>
            <small>Breed</small>
         </li>
         <li>
            <span>{{$dog['color']}}</span><br>
            <small>Color</small>
         </li>
         <li>
            <span>{{$dog['sex']=='M'?'Male':'Female' }}</span><br>
            <small>Sex</small>
         </li>
         <li>
            <span>{{ \Carbon\Carbon::parse($dog['birthdate'])->format('F d, Y') }}</span><br>
            <small>Date of Birth</small>
         </li>
         <li>
            <span>{{ \Carbon\Carbon::parse($dog['date'])->format('F d, Y') }}</span><br>
            <small>Registered On</small>
         </li>
      </ul>
   </div>
</div>
@endif

