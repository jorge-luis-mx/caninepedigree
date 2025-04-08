
@props(['pedigree'])

@if(isset($pedigree) && !empty($pedigree))


@php

    // Acceder al árbol genealógico completo desde el array
    $dog = $pedigree['dog'];

    $father = $pedigree['sire']['dog'] ?? null;  // Verifica si el padre existe
    $mother = $pedigree['dam']['dog'] ?? null;   // Verifica si la madre existe
    
    // Generación 3: Abuelos
    
    $fatherFather  = $pedigree['sire']['sire']['dog']?? null;
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


    dd($bisabuela4);
   
@endphp

<div class="container">

   <!-- 1ª Generación -->
   <!-- <div class="generation-label">1ª Generación</div>
      <div class="generation">
         <div class="main-animal">
            <img src="https://placedog.net/400/400?id=1" alt="Max" />
            <p><strong>Nombre:</strong> Max</p>
            <p><strong>Raza:</strong> Labrador</p>
            <p><strong>Color:</strong> Dorado</p>
            <p><strong>Fecha de nacimiento:</strong> 01/01/2020</p>
            <p><strong>Sexo:</strong> Macho</p>
         </div>
      </div> -->

   <!-- 2ª Generación -->
   <div class="generation-label">2ª Generación - Padres </div>
   <div class="generation">
      <div class="animal">
         <a href="{{ route('pediree.showPedigree', ['id' => $father['id']]) }}">
            <img src="https://placedog.net/300/200?id=2" alt="Rocky" />
         </a>
         <p><strong>Padre:</strong> <a href="{{ route('pediree.showPedigree', ['id' => $father['id']]) }}"> {{ $father['name'] ?? 'No disponible' }} </a></p>
      </div>
      <div class="animal">
         <a href="{{ route('pediree.showPedigree', ['id' => $mother['id']]) }}">
            <img src="https://placedog.net/300/200?id=3" alt="Luna" />
         </a>
         <p><strong>Madre:</strong> <a href="{{ route('pediree.showPedigree', ['id' => $mother['id']]) }}"> {{ $mother['name'] ?? 'No disponible' }} </a></p>
      </div>
   </div>

   <!-- 3ª Generación -->
   <div class="generation-label">3ª Generación - Abuelos</div>
   <div class="generation">
      <div class="animal">
         <a href="pedigree.html?nombre=Thor">
            <img src="https://placedog.net/300/200?id=4" alt="Thor" />
         </a>
         <p><strong>Abuelo Paterno:</strong> <a href="pedigree.html?nombre=Thor">Thor</a></p>
      </div>
      <div class="animal">
         <a href="pedigree.html?nombre=Bella">
            <img src="https://placedog.net/300/200?id=5" alt="Bella" />
         </a>
         <p><strong>Abuela Paterna:</strong> <a href="pedigree.html?nombre=Bella">Bella</a></p>
      </div>
      <div class="animal">
         <a href="pedigree.html?nombre=Simba">
            <img src="https://placedog.net/300/200?id=6" alt="Simba" />
         </a>
         <p><strong>Abuelo Materno:</strong> <a href="pedigree.html?nombre=Simba">Simba</a></p>
      </div>
      <div class="animal">
         <a href="pedigree.html?nombre=Lucy">
            <img src="https://placedog.net/300/200?id=7" alt="Lucy" />
         </a>
         <p><strong>Abuela Materna:</strong> <a href="pedigree.html?nombre=Lucy">Lucy</a></p>
      </div>
   </div>

   <!-- 4ª Generación -->
   <div class="generation-label">4ª Generación - Bisabuelos</div>
   <div class="generation">
      <div class="animal">
         <a href="pedigree.html?nombre=Zeus">
            <img src="https://placedog.net/300/200?id=8" alt="Zeus" />
         </a>
         <p><strong>Bisabuelo Paterno 1:</strong> <a href="pedigree.html?nombre=Zeus">Zeus</a></p>
      </div>
      <div class="animal">
         <a href="pedigree.html?nombre=Nina">
            <img src="https://placedog.net/300/200?id=9" alt="Nina" />
         </a>
         <p><strong>Bisabuela Paterna 1:</strong> <a href="pedigree.html?nombre=Nina">Nina</a></p>
      </div>
      <div class="animal">
         <a href="pedigree.html?nombre=Bruno">
            <img src="https://placedog.net/300/200?id=10" alt="Bruno" />
         </a>
         <p><strong>Bisabuelo Paterno 2:</strong> <a href="pedigree.html?nombre=Bruno">Bruno</a></p>
      </div>
      <div class="animal">
         <a href="pedigree.html?nombre=Lola">
            <img src="https://placedog.net/300/200?id=11" alt="Lola" />
         </a>
         <p><strong>Bisabuela Paterna 2:</strong> <a href="pedigree.html?nombre=Lola">Lola</a></p>
      </div>
      <div class="animal">
         <a href="pedigree.html?nombre=Rex">
            <img src="https://placedog.net/300/200?id=12" alt="Rex" />
         </a>
         <p><strong>Bisabuelo Materno 1:</strong> <a href="pedigree.html?nombre=Rex">Rex</a></p>
      </div>
      <div class="animal">
         <a href="pedigree.html?nombre=Canela">
            <img src="https://placedog.net/300/200?id=13" alt="Canela" />
         </a>
         <p><strong>Bisabuela Materna 1:</strong> <a href="pedigree.html?nombre=Canela">Canela</a></p>
      </div>
      <div class="animal">
         <a href="pedigree.html?nombre=Bobby">
            <img src="https://placedog.net/300/200?id=14" alt="Bobby" />
         </a>
         <p><strong>Bisabuelo Materno 2:</strong> <a href="pedigree.html?nombre=Bobby">Bobby</a></p>
      </div>
      <div class="animal">
         <a href="pedigree.html?nombre=Daisy">
            <img src="https://placedog.net/300/200?id=15" alt="Daisy" />
         </a>
         <p><strong>Bisabuela Materna 2:</strong> <a href="pedigree.html?nombre=Daisy">Daisy</a></p>
      </div>
   </div>
</div>

@endif