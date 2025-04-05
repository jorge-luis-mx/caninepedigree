
@props(['dogDetails'])

@if(isset($dogDetails) && !empty($dogDetails))
<div class="container-details card">
   <div class="container-imagen">
      <img src="https://placedog.net/400/400?id=1" />
   </div>
   <div class="content-details">
      <p class="title is-3 mt-2">{{ $dogDetails->name}}</p>
      <ul class="is-flex is-align-items-center mb-3">
         <li>
            <span>Jorge Luis</span><br>
            <small>Owner</small>
         </li>
         <li>
            <span>{{$dogDetails->reg_no}}</span><br>
            <small>Reg.No</small>
         </li>
      </ul>
      <ul class="is-flex is-align-items-center mb-3">

         <li>
            <span>Pit Bull</span><br>
            <small>Breed</small>
         </li>
         <li>
            <span>Red</span><br>
            <small>Color</small>
         </li>
         <li>
            <span>Male</span><br>
            <small>Sex</small>
         </li>
         <li>
            <span>March 26, 2025</span><br>
            <small>Date of Birth</small>
         </li>
         <li>
            <span>March 30, 2025</span><br>
            <small>Registered On</small>
         </li>
      </ul>
   </div>
</div>
@endif