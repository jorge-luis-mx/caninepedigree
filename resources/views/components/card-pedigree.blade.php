
@props(['pedigree'])

@if(isset($pedigree) && !empty($pedigree))


@php

   // Acceder al árbol genealógico completo desde el array
   $dog = $pedigree['dog'];

   // Generación 2: 

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


   // Generación 5: Tatarabuelos (16 nodos)

  // Del lado paterno del padre (sire > sire > sire y dam)
  $tatarabuelo1  = $pedigree['sire']['sire']['sire']['sire']['dog'] ?? null; // Padre del bisabuelo1
  $tatarabuela1  = $pedigree['sire']['sire']['sire']['dam']['dog'] ?? null;  // Madre del bisabuelo1

  $tatarabuelo2  = $pedigree['sire']['sire']['dam']['sire']['dog'] ?? null;  // Padre de la bisabuela1
  $tatarabuela2  = $pedigree['sire']['sire']['dam']['dam']['dog'] ?? null;   // Madre de la bisabuela1

  // Del lado materno del padre (sire > dam > sire y dam)
  $tatarabuelo3  = $pedigree['sire']['dam']['sire']['sire']['dog'] ?? null;  // Padre del bisabuelo2
  $tatarabuela3  = $pedigree['sire']['dam']['sire']['dam']['dog'] ?? null;   // Madre del bisabuelo2

  $tatarabuelo4  = $pedigree['sire']['dam']['dam']['sire']['dog'] ?? null;   // Padre de la bisabuela2
  $tatarabuela4  = $pedigree['sire']['dam']['dam']['dam']['dog'] ?? null;    // Madre de la bisabuela2

  // Del lado paterno de la madre (dam > sire > sire y dam)
  $tatarabuelo5  = $pedigree['dam']['sire']['sire']['sire']['dog'] ?? null;  // Padre del bisabuelo3
  $tatarabuela5  = $pedigree['dam']['sire']['sire']['dam']['dog'] ?? null;   // Madre del bisabuelo3

  $tatarabuelo6  = $pedigree['dam']['sire']['dam']['sire']['dog'] ?? null;   // Padre de la bisabuela3
  $tatarabuela6  = $pedigree['dam']['sire']['dam']['dam']['dog'] ?? null;    // Madre de la bisabuela3

  // Del lado materno de la madre (dam > dam > sire y dam)
  $tatarabuelo7  = $pedigree['dam']['dam']['sire']['sire']['dog'] ?? null;   // Padre del bisabuelo4
  $tatarabuela7  = $pedigree['dam']['dam']['sire']['dam']['dog'] ?? null;    // Madre del bisabuelo4

  $tatarabuelo8  = $pedigree['dam']['dam']['dam']['sire']['dog'] ?? null;    // Padre de la bisabuela4
  $tatarabuela8  = $pedigree['dam']['dam']['dam']['dam']['dog'] ?? null;     // Madre de la bisabuela4


@endphp
      
      
      
      <div class="table-container table is-bordered is-striped is-narrow is-hoverable">
        <table class="table is-fullwidth">
          <tbody>
            <!-- Información del perro principal -->
            <tr>
              <td colspan="4">
                    <div class="dog-info">
                        <div class="container-imagen">
                          <img src="{{ asset('assets/img/dogs-image.jpg') }}" />
                        </div>
                        <div class="dog-details">
                          <p><label>{{__('messages.main.dogDetails.name')}}:</label> <span>{{$dog['name']}}</span></p>
                          <p><label>{{__('messages.main.dogDetails.owner')}}:</label> <span>{{$dog['owner']['name']}}</span></p>
                          <p><label>{{__('messages.main.dogDetails.regNo')}}:</label> <span>{{$dog['number']}}</span></p>
                          <p><label>{{__('messages.main.dogDetails.breed')}}:</label> <span>{{$dog['breed']}}</span></p>
                          <p><label>{{__('messages.main.dogDetails.color')}}:</label> <span>{{$dog['color']}}</span></p>
                          <p><label>{{__('messages.main.dogDetails.sex')}}:</label> <span>{{$dog['sex']=='M'?'Male':'Female' }}</span></p>
                          <p><label>{{__('messages.main.dogDetails.date')}}:</label> 
                              <span>{{ \Carbon\Carbon::parse($dog['birthdate'])->format('F d, Y') }}</span>
                          </p>
                          <p><label>{{__('messages.main.dogDetails.registered')}}:</label> 
                              <span>{{ \Carbon\Carbon::parse($dog['date'])->format('F d, Y') }}</span>
                          </p>
                        </div>
                    </div>
              </td>
            </tr>

            <!-- Árbol genealógico de 4 generaciones -->
            <tr>
              <td rowspan="8"><b>({{__('messages.main.pedigree.sire')}})</b><br>
                @if(isset($father['id']))
                  <a href="{{ route('pedigree.show', ['id' => $father['id']]) }}">{{ $father['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
              <td rowspan="4">
                @if(isset($fatherFather['id']))
                  <a href="{{ route('pedigree.show', ['id' => $fatherFather['id']]) }}">{{ $fatherFather['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
              <td rowspan="2">
                @if(isset($bisabuelo1['id']))
                  <a href="{{ route('pedigree.show', ['id' => $bisabuelo1['id']]) }}">{{ $bisabuelo1['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
              <td>
                @if(isset($tatarabuelo1['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuelo1['id']]) }}">{{ $tatarabuelo1['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td>
                @if(isset($tatarabuela1['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuela1['id']]) }}">{{ $tatarabuela1['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td rowspan="2">
                @if(isset($bisabuela1['id']))
                  <a href="{{ route('pedigree.show', ['id' => $bisabuela1['id']]) }}">{{ $bisabuela1['name'] ?? 'UNKNOWN' }}</a>
                @else
                <span>UNKNOWN</span>
                @endif
              </td>
              <td>
                @if(isset($tatarabuelo2['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuelo2['id']]) }}">{{ $tatarabuelo2['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td>
                @if(isset($tatarabuela2['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuela2['id']]) }}">{{ $tatarabuela2['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td rowspan="4">
                @if(isset($fatherMother['id']))
                  <a href="{{ route('pedigree.show', ['id' => $fatherMother['id']]) }}">{{ $fatherMother['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
              <td rowspan="2">
                @if(isset($bisabuelo2['id']))
                  <a href="{{ route('pedigree.show', ['id' => $bisabuelo2['id']]) }}">{{ $bisabuelo2['name'] ?? 'UNKNOWN' }}</a>
                @else
                <span>UNKNOWN</span>
                @endif
              </td>
              <td>
                @if(isset($tatarabuelo3['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuelo3['id']]) }}">{{ $tatarabuelo3['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td>
                @if(isset($tatarabuela3['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuela3['id']]) }}">{{ $tatarabuela3['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td rowspan="2">
                @if(isset($bisabuela2['id']))
                  <a href="{{ route('pedigree.show', ['id' => $bisabuela2['id']]) }}">{{ $bisabuela2['name'] ?? 'UNKNOWN' }}</a>
                @else
                <span>UNKNOWN</span>
                @endif
              </td>
              <td>
                @if(isset($tatarabuelo4['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuelo4['id']]) }}">{{ $tatarabuelo4['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td>
                @if(isset($tatarabuela4['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuela4['id']]) }}">{{ $tatarabuela4['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>

            <!-- DAM -->
            <tr>
              <td rowspan="8"><b>({{__('messages.main.pedigree.dam')}})</b><br>
                @if(isset($mother['id']))
                  <a href="{{ route('pedigree.show', ['id' => $mother['id']]) }}">{{ $mother['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
              <td rowspan="4">
                @if(isset($motherFather['id']))
                  <a href="{{ route('pedigree.show', ['id' => $motherFather['id']]) }}">{{ $motherFather['name'] ?? 'UNKNOWN' }}</a>
                @else
                <span>UNKNOWN</span>
                @endif
              </td>
              <td rowspan="2">
                @if(isset($bisabuelo3['id']))
                  <a href="{{ route('pedigree.show', ['id' => $bisabuelo3['id']]) }}">{{ $bisabuelo3['name'] ?? 'UNKNOWN' }}</a>
                @else
                <span>UNKNOWN</span>
                @endif
              </td>
              <td>
                @if(isset($tatarabuelo5['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuelo5['id']]) }}">{{ $tatarabuelo5['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td>
                @if(isset($tatarabuela5['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuela5['id']]) }}">{{ $tatarabuela5['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td rowspan="2">
                @if(isset($bisabuela3['id']))
                  <a href="{{ route('pedigree.show', ['id' => $bisabuela3['id']]) }}">{{ $bisabuela3['name'] ?? 'UNKNOWN' }}</a>
                @else
                <span>UNKNOWN</span>
                @endif
              </td>
              <td>
                @if(isset($tatarabuelo6['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuelo6['id']]) }}">{{ $tatarabuelo6['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td>
                @if(isset($tatarabuela6['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuela6['id']]) }}">{{ $tatarabuela6['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td rowspan="4">
                @if(isset($motherMother['id']))
                  <a href="{{ route('pedigree.show', ['id' => $motherMother['id']]) }}">{{ $motherMother['name'] ?? 'UNKNOWN' }}</a>
                @else
                <span>UNKNOWN</span>
                @endif
              </td>
              <td rowspan="2">
                @if(isset($bisabuelo4['id']))
                  <a href="{{ route('pedigree.show', ['id' => $bisabuelo4['id']]) }}">{{ $bisabuelo4['name'] ?? 'UNKNOWN' }}</a>
                @else
                <span>UNKNOWN</span>
                @endif
              </td>
              <td>
                @if(isset($tatarabuelo7['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuelo7['id']]) }}">{{ $tatarabuelo7['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td>
                @if(isset($tatarabuela7['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuela7['id']]) }}">{{ $tatarabuela7['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td rowspan="2">
                @if(isset($bisabuela4['id']))
                  <a href="{{ route('pedigree.show', ['id' => $bisabuela4['id']]) }}">{{ $bisabuela4['name'] ?? 'UNKNOWN' }}</a>
                @else
                <span>UNKNOWN</span>
                @endif
              </td>
              <td>
                @if(isset($tatarabuelo8['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuelo8['id']]) }}">{{ $tatarabuelo8['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>
            <tr>
              <td>
                @if(isset($tatarabuela8['id']))
                  <a href="{{ route('pedigree.show', ['id' => $tatarabuela8['id']]) }}">{{ $tatarabuela8['name'] ?? 'UNKNOWN' }}</a>
                @else
                  <span>UNKNOWN</span>
                @endif
              </td>
            </tr>

          </tbody>
        </table>
      </div>

@endif