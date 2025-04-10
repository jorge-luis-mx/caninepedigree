
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
               <a href="{{ route('pediree.showPedigree', ['id' => $fatherFather['id']]) }}">{{ $fatherFather['name'] ?? 'No disponible' }}</a>
            @else
               <span>UNKNOWN</span>
            @endif
            </b>
         </td>
         <td>
            <a href="modules.php?name=Public&file=printPedigree&dog_id=306">PATRICK'S LITTLE TATER</a>
         </td>
      </tr>
      <tr>
         <td>
            <a href="modules.php?name=Public&file=printPedigree&dog_id=69411">PATRICKS RED LADY</a>
         </td>
      </tr>
      <tr>
         <td rowspan="2"><b>
            @if(isset($fatherMother['id']))
               <a href="{{ route('pediree.showPedigree', ['id' => $fatherMother['id']]) }}">{{ $fatherMother['name'] ?? 'No disponible' }}</a>
            @else
               <span>UNKNOWN</span>
            @endif
            </b>
         </td>
         <td>
            <a href="modules.php?name=Public&file=printPedigree&dog_id=756">ONELLO'S SHERMAN THE TANK</a>
         </td>
      </tr>
      <tr>
         <td>
            <a href="modules.php?name=Public&file=printPedigree&dog_id=757">ONELLO'S TWISTED SISTER</a>
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
            <a href="modules.php?name=Public&file=printPedigree&dog_id=388">CH CHAVIS' YELLOW JOHN (4XW) ROM</a>
         </td>
      </tr>

      <tr>
         <td>
            <a href="modules.php?name=Public&file=printPedigree&dog_id=396">TANT'S MISS JOCKO (2XW) ROM</a>
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
            <a href="modules.php?name=Public&file=printPedigree&dog_id=559">BROWNING'S YAZOO CODY</a>
         </td>
      </tr>
      <tr>
         <td>
            <a href="modules.php?name=Public&file=printPedigree&dog_id=14">PATRICK'S LADY IN RED</a>
         </td>
      </tr>
   </table>
</div>


<div class="container">

   <!-- 2ª Generación -->
   <div class="generation-label">2ª Generación - Padres</div>
   <div class="generation">
      <div class="animal">
         @if(isset($father['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $father['id']]) }}">
               <img src="https://placedog.net/300/200?id=2" alt="{{ $father['name'] ?? 'Padre' }}" />
            </a>
            <p><strong>Padre:</strong> 
               <a href="{{ route('pediree.showPedigree', ['id' => $father['id']]) }}">
                  {{ $father['name'] ?? 'No disponible' }}
               </a>
            </p>
         @else
            <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
            <p><strong>Padre:</strong> No disponible</p>
         @endif
      </div>

      <div class="animal">
         @if(isset($mother['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $mother['id']]) }}">
               <img src="https://placedog.net/300/200?id=3" alt="{{ $mother['name'] ?? 'Madre' }}" />
            </a>
            <p><strong>Madre:</strong> 
               <a href="{{ route('pediree.showPedigree', ['id' => $mother['id']]) }}">
                  {{ $mother['name'] ?? 'No disponible' }}
               </a>
            </p>
         @else
            <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
            <p><strong>Madre:</strong> No disponible</p>
         @endif
      </div>
   </div>

   <!-- 3ª Generación -->
   <div class="generation-label">3ª Generación - Abuelos</div>
   <div class="generation">
      <div class="animal">
         @if(isset($fatherFather['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $fatherFather['id']]) }}">
               <img src="https://placedog.net/300/200?id=4" alt="{{ $fatherFather['name'] ?? 'Abuelo Paterno' }}" />
            </a>
            <p><strong>Abuelo Paterno:</strong> 
               <a href="{{ route('pediree.showPedigree', ['id' => $fatherFather['id']]) }}">
                  {{ $fatherFather['name'] ?? 'No disponible' }}
               </a>
            </p>
         @else
            <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
            <p><strong>Abuelo Paterno:</strong> No disponible</p>
         @endif
      </div>

      <div class="animal">
         @if(isset($fatherMother['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $fatherMother['id']]) }}">
               <img src="https://placedog.net/300/200?id=5" alt="{{ $fatherMother['name'] ?? 'Abuela Paterna' }}" />
            </a>
            <p><strong>Abuela Paterna:</strong> 
               <a href="{{ route('pediree.showPedigree', ['id' => $fatherMother['id']]) }}">
                  {{ $fatherMother['name'] ?? 'No disponible' }}
               </a>
            </p>
         @else
            <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
            <p><strong>Abuela Paterna:</strong> No disponible</p>
         @endif
      </div>

      <div class="animal">
         @if(isset($motherFather['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $motherFather['id']]) }}">
               <img src="https://placedog.net/300/200?id=6" alt="{{ $motherFather['name'] ?? 'Abuelo Materno' }}" />
            </a>
            <p><strong>Abuelo Materno:</strong> 
               <a href="{{ route('pediree.showPedigree', ['id' => $motherFather['id']]) }}">
                  {{ $motherFather['name'] ?? 'No disponible' }}
               </a>
            </p>
         @else
            <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
            <p><strong>Abuelo Materno:</strong> No disponible</p>
         @endif
      </div>

      <div class="animal">
         @if(isset($motherMother['id']))
            <a href="{{ route('pediree.showPedigree', ['id' => $motherMother['id']]) }}">
               <img src="https://placedog.net/300/200?id=7" alt="{{ $motherMother['name'] ?? 'Abuela Materna' }}" />
            </a>
            <p><strong>Abuela Materna:</strong> 
               <a href="{{ route('pediree.showPedigree', ['id' => $motherMother['id']]) }}">
                  {{ $motherMother['name'] ?? 'No disponible' }}
               </a>
            </p>
         @else
            <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
            <p><strong>Abuela Materna:</strong> No disponible</p>
         @endif
      </div>
   </div>


<!-- 4ª Generación -->
<div class="generation-label">4ª Generación - Bisabuelos</div>
<div class="generation">
   <div class="animal">
      @if(isset($bisabuelo1['id']))
         <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo1['id']]) }}">
            <img src="https://placedog.net/300/200?id=8" alt="{{ $bisabuelo1['name'] ?? 'Bisabuelo Paterno 1' }}" />
         </a>
         <p><strong>Bisabuelo Paterno 1:</strong> 
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo1['id']]) }}">
               {{ $bisabuelo1['name'] ?? 'No disponible' }}
            </a>
         </p>
      @else
         <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
         <p><strong>Bisabuelo Paterno 1:</strong> No disponible</p>
      @endif
   </div>

   <div class="animal">
      @if(isset($bisabuela1['id']))
         <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela1['id']]) }}">
            <img src="https://placedog.net/300/200?id=9" alt="{{ $bisabuela1['name'] ?? 'Bisabuela Paterna 1' }}" />
         </a>
         <p><strong>Bisabuela Paterna 1:</strong> 
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela1['id']]) }}">
               {{ $bisabuela1['name'] ?? 'No disponible' }}
            </a>
         </p>
      @else
         <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
         <p><strong>Bisabuela Paterna 1:</strong> No disponible</p>
      @endif
   </div>

   <div class="animal">
      @if(isset($bisabuelo2['id']))
         <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo2['id']]) }}">
            <img src="https://placedog.net/300/200?id=10" alt="{{ $bisabuelo2['name'] ?? 'Bisabuelo Paterno 2' }}" />
         </a>
         <p><strong>Bisabuelo Paterno 2:</strong> 
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo2['id']]) }}">
               {{ $bisabuelo2['name'] ?? 'No disponible' }}
            </a>
         </p>
      @else
         <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
         <p><strong>Bisabuelo Paterno 2:</strong> No disponible</p>
      @endif
   </div>

   <div class="animal">
      @if(isset($bisabuela2['id']))
         <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela2['id']]) }}">
            <img src="https://placedog.net/300/200?id=11" alt="{{ $bisabuela2['name'] ?? 'Bisabuela Paterna 2' }}" />
         </a>
         <p><strong>Bisabuela Paterna 2:</strong> 
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela2['id']]) }}">
               {{ $bisabuela2['name'] ?? 'No disponible' }}
            </a>
         </p>
      @else
         <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
         <p><strong>Bisabuela Paterna 2:</strong> No disponible</p>
      @endif
   </div>

   <div class="animal">
      @if(isset($bisabuelo3['id']))
         <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo3['id']]) }}">
            <img src="https://placedog.net/300/200?id=12" alt="{{ $bisabuelo3['name'] ?? 'Bisabuelo Materno 1' }}" />
         </a>
         <p><strong>Bisabuelo Materno 1:</strong> 
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo3['id']]) }}">
               {{ $bisabuelo3['name'] ?? 'No disponible' }}
            </a>
         </p>
      @else
         <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
         <p><strong>Bisabuelo Materno 1:</strong> No disponible</p>
      @endif
   </div>

   <div class="animal">
      @if(isset($bisabuela3['id']))
         <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela3['id']]) }}">
            <img src="https://placedog.net/300/200?id=13" alt="{{ $bisabuela3['name'] ?? 'Bisabuela Materna 1' }}" />
         </a>
         <p><strong>Bisabuela Materna 1:</strong> 
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela3['id']]) }}">
               {{ $bisabuela3['name'] ?? 'No disponible' }}
            </a>
         </p>
      @else
         <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
         <p><strong>Bisabuela Materna 1:</strong> No disponible</p>
      @endif
   </div>

   <div class="animal">
      @if(isset($bisabuelo4['id']))
         <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo4['id']]) }}">
            <img src="https://placedog.net/300/200?id=14" alt="{{ $bisabuelo4['name'] ?? 'Bisabuelo Materno 2' }}" />
         </a>
         <p><strong>Bisabuelo Materno 2:</strong> 
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuelo4['id']]) }}">
               {{ $bisabuelo4['name'] ?? 'No disponible' }}
            </a>
         </p>
      @else
         <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
         <p><strong>Bisabuelo Materno 2:</strong> No disponible</p>
      @endif
   </div>

   <div class="animal">
      @if(isset($bisabuela4['id']))
         <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela4['id']]) }}">
            <img src="https://placedog.net/300/200?id=15" alt="{{ $bisabuela4['name'] ?? 'Bisabuela Materna 2' }}" />
         </a>
         <p><strong>Bisabuela Materna 2:</strong> 
            <a href="{{ route('pediree.showPedigree', ['id' => $bisabuela4['id']]) }}">
               {{ $bisabuela4['name'] ?? 'No disponible' }}
            </a>
         </p>
      @else
         <img src="https://via.placeholder.com/300x200.png?text=Sin+imagen" alt="No disponible" />
         <p><strong>Bisabuela Materna 2:</strong> No disponible</p>
      @endif
   </div>
</div>

</div>

@endif