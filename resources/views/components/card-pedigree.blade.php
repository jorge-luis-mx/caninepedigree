
@props(['pedigree'])

@if(isset($pedigree) && !empty($pedigree))


@php

    // Acceder al árbol genealógico completo desde el array
    $dog = $pedigree['dog'];

    $father = $pedigree['sire']['dog'] ?? null;  // Verifica si el padre existe
    $mother = $pedigree['dam']['dog'] ?? null;   // Verifica si la madre existe
    
    // Generación 3: Abuelos
    
    $fatherFather  = $pedigree['sire']['sire']['dog']?? null;
    $fatherFatherUrl = $pedigree['sire']['sire']['dog']['id']?? null;
    $fatherMother = $pedigree['sire']['dam']['dog'] ?? null;

    $motherFather = $pedigree['dam']['sire']['dog']?? null;
    $motherMother = $pedigree['dam']['dam']['dog']?? null;


    // Generación 4: Bisabuelos (8 nodos)

    // Del lado paterno:
   $bisabuelo1 = $pedigree['sire']['sire']['sire']['dog'] ?? null;   // Bisabuelo 1 (padre de abuelo paterno)
   $bisabuela1 = $pedigree['sire']['sire']['dam']['dog'] ?? null;    // Bisabuela 1 (madre de abuelo paterno)

   $bisabuelo2 = $pedigree['sire']['dam']['sire']['dog'] ?? null;    // Bisabuelo 2 (padre de abuela paterna)
   $bisabuela2 = $pedigree['sire']['dam']['dam']['dog'] ?? null;     // Bisabuela 2 (madre de abuela paterna)

   // Del lado materno:
   $bisabuelo3 = $pedigree['dam']['sire']['sire']['dog'] ?? null;    // Bisabuelo 3 (padre de abuelo materno)
   $bisabuela3 = $pedigree['dam']['sire']['dam']['dog'] ?? null;     // Bisabuela 3 (madre de abuelo materno)

   $bisabuelo4 = $pedigree['dam']['dam']['sire']['dog'] ?? null;     // Bisabuelo 4 (padre de abuela materna)
   $bisabuela4 = $pedigree['dam']['dam']['dam']['dog'] ?? null;      // Bisabuela 4 (madre de abuela materna)



   
@endphp

<div class="table-container table is-bordered is-striped is-narrow is-hoverable ">
   <table class="table">
      <tr>
         <td colspan="4">
               <div class="dog-info">
                  <img src="https://placedog.net/400/400?id=1" alt="Dog Image">
                  <div class="dog-details">
                     <p><label>Name:</label> <span>EK'S MACHOBUCK</span></p>
                     <p><label>Owner:</label> <span>Jorge luis</span></p>
                     <p><label>Reg. No:</label> <span>REG-XZU691629</span></p>
                     <p><label>Breed:</label> <span>Pit Bull</span></p>
                     <p><label>Color:</label> <span>Red</span></p>
                     <p><label>Sex:</label> <span>Male</span></p>
                     <p><label>Date of Birth:</label> <span>March 26, 2025</span></p>
                     <p><label>Registered On:</label> <span>March 30, 2025</span></p>
                  </div>
               </div>
         </td>
      </tr>
      <tr>
         <td rowspan="4" ><b>(Sire)
            @if(isset($father['id']))
               <a href="{{ route('pediree.showPedigree', ['id' => $father['id']]) }}">{{ $father['name'] ?? 'UNKNOWN' }}</a>
            @else
               <span>UNKNOWN</span>
            @endif
            </b>
         </td>
         <td rowspan="2"><b>
            @if(isset($fatherFather['id']))
               <a href="{{ route('pediree.showPedigree', ['id' => $fatherFather['id']]) }}">{{ $fatherFather['name'] ?? 'UNKNOWN' }}</a>
            @else
               <span>UNKNOWN</span>
            @endif
            </b>
         </td>
         <td>
         @if(isset($bisabuelo1['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo1['id']]) }}">{{ $bisabuelo1['name'] ?? 'UNKNOWN' }}</a>
         @else
            <span>UNKNOWN</span>
         @endif
         </td>
      </tr>
      <tr>
         <td>
         @if(isset($bisabuela1['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela1['id']]) }}">{{ $bisabuela1['name'] ?? 'UNKNOWN' }}</a>
         @else
         <span>UNKNOWN</span>
         @endif
         </td>
      </tr>
      <tr>
         <td rowspan="2"><b>
            @if(isset($fatherMother['id']))
               <a href="{{ route('pediree.showPedigree', ['id' => $fatherMother['id']]) }}">{{ $fatherMother['name'] ?? 'UNKNOWN' }}</a>
            @else
               <span>UNKNOWN</span>
            @endif
            </b>
         </td>
         <td>
         @if(isset($bisabuelo2['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo2['id']]) }}">{{ $bisabuelo2['name'] ?? 'UNKNOWN' }}</a>
         @else
         <span>UNKNOWN</span>
         @endif
         </td>
      </tr>
      <tr>
         <td>
         @if(isset($bisabuela2['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela2['id']]) }}">{{ $bisabuela2['name'] ?? 'UNKNOWN' }}</a>
         @else
         <span>UNKNOWN</span>
         @endif
         </td>
      </tr>

      <!-- dam -->
      <tr>
         <td rowspan="4"><b>(Dam) 
            @if(isset($mother['id']))
               <a href="{{ route('pediree.showPedigree', ['id' => $mother['id']]) }}">{{ $mother['name'] ?? 'UNKNOWN' }}</a>
            @else
               <span>UNKNOWN</span>
            @endif
            </b>
         </td>
         <td rowspan="2"><b>
            @if(isset($motherFather['id']))
               <a href="{{ route('pediree.showPedigree', ['id' => $motherFather['id']]) }}">{{ $motherFather['name'] ?? 'UNKNOWN' }}</a>
            @else
            <span>UNKNOWN</span>
            @endif
            </b>
         </td>
         <td>
         @if(isset($bisabuelo3['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo3['id']]) }}">{{ $bisabuelo3['name'] ?? 'UNKNOWN' }}</a>
         @else
         <span>UNKNOWN</span>
         @endif
         </td>
      </tr>

      <tr>
         <td>
         @if(isset($bisabuela3['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela3['id']]) }}">{{ $bisabuela3['name'] ?? 'UNKNOWN' }}</a>
         @else
         <span>UNKNOWN</span>
         @endif
         </td>
      </tr>
      <tr>
         <td rowspan="2"><b>
         @if(isset($motherMother['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $motherMother['id']]) }}">{{ $motherMother['name'] ?? 'UNKNOWN' }}</a>
         @else
         <span>UNKNOWN</span>
         @endif
         </b>
      </td>
         <td>
         @if(isset($bisabuelo4['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo4['id']]) }}">{{ $bisabuelo4['name'] ?? 'UNKNOWN' }}</a>
         @else
         <span>UNKNOWN</span>
         @endif
         </td>
      </tr>
      <tr>
         <td>
         @if(isset($bisabuela4['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela4['id']]) }}">{{ $bisabuela4['name'] ?? 'UNKNOWN' }}</a>
         @else
         <span>UNKNOWN</span>
         @endif
         </td>
      </tr>
   </table>
</div>


@endif