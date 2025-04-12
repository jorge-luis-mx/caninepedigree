<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Certificado de Pedigrí</title>
  <style>
    body {
      font-family: "Georgia", serif;
      color: #333;
    }

    .certificado {
      border: 6px double #000;
      padding: 40px;
      max-width: 1100px;
      margin: auto;
      background: #fff;
    }

    .titulo {
      text-align: center;
      font-size: 32px;
      font-weight: bold;
      text-transform: uppercase;
      margin-bottom: 10px;
    }

    .subtitulo {
      text-align: center;
      font-size: 20px;
      margin-bottom: 40px;
    }

    table.info-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 40px;
      font-size: 16px;
    }

    .info-table td {
      padding: 6px 8px;
      vertical-align: top;
    }

    .table-container {
      margin-top: 30px;
      display: flex;
      justify-content: center;

    }

    .table td {
      padding: 8px;
      padding-left: 101px;
    }

    .table a {
      text-decoration: none;
      color: #333;
      font-weight: bold;
      padding: 4px 0;
      display: inline-block;
      border-bottom: 1px solid #ddd;
    }

    .table a:hover {
      color: #007BFF;
    }

    table td[rowspan="4"] {
      vertical-align: middle;
      text-align: center;
    }

    .footer {
      margin-top: 60px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .firma {
      text-align: center;
      width: 45%;
    }

    .firma .linea {
      margin-top: 40px;
      border-top: 1px solid #000;
      width: 100%;
    }

    .registro {
      font-size: 14px;
      line-height: 25px;
      width: 30%;
    }
  </style>
</head>
<body>
  <div class="certificado">
    <div class="titulo">Pedigree Certificate</div>
    <div class="subtitulo">Asociación Nacional de Criadores</div>

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

@endif
    <table class="info-table">
      <tr>
        <td><strong>Name:</strong></td>
        <td>{{$dog['name']}}</td>
        <td><strong>Breed:</strong></td>
        <td>{{$dog['breed']}}</td>
      </tr>
      <tr>
        <td><strong>Sex:</strong></td>
        <td>{{$dog['sex']=='M'?'Male':'Female' }}</td>
        <td><strong>Color:</strong></td>
        <td>{{$dog['color']}}</td>
      </tr>
      <tr>
        <td><strong>Born:</strong></td>
        <td>{{ \Carbon\Carbon::parse($dog['birthdate'])->format('F d, Y') }}</td>
        <td><strong>Breeder:</strong></td>
        <td>Luis Hernández</td>
      </tr>
      <tr>
        <td><strong>Owner:</strong></td>
        <td>{{$dog['owner']['name']}}</td>
        <td><strong>Country:</strong></td>
        <td>Mexico</td>
      </tr>
      <tr>
        <td><strong>State:</strong></td>
        <td>Quintana Roo</td>
        <td><strong>City:</strong></td>
        <td>Cancún</td>
      </tr>
      <tr>
        <td><strong>Address:</strong></td>
        <td colspan="3">Sm 20 lt 8 call 02</td>
      </tr>
    </table>

    <div class="table-container">
      <table class="table">
        <tbody>
          <tr>
            <td rowspan="4"><b>(Sire) {{ $father['name'] ?? 'UNKNOWN' }}</b></td>
            <td rowspan="2"><b>{{ $fatherFather['name'] ?? 'UNKNOWN' }}</b></td>
            <td><b>{{ $bisabuelo1['name'] ?? 'UNKNOWN' }}</b></td>
          </tr>
          <tr>
            <td><b>{{ $bisabuela1['name'] ?? 'UNKNOWN' }}</b></td>
          </tr>
          <tr>
            <td rowspan="2"><b>{{ $fatherMother['name'] ?? 'UNKNOWN' }}</b></td>
            <td><b>{{ $bisabuelo2['name'] ?? 'UNKNOWN' }}</b></td>
          </tr>
          <tr>
            <td><b>{{ $bisabuela2['name'] ?? 'UNKNOWN' }}</b></td>
          </tr>
          <tr>
            <td rowspan="4"><b>(Dam) {{ $mother['name'] ?? 'UNKNOWN' }}</b></td>
            <td rowspan="2"><b>{{ $motherFather['name'] ?? 'UNKNOWN' }}</b></td>
            <td><b>{{ $bisabuelo3['name'] ?? 'UNKNOWN' }}</b></td>
          </tr>
          <tr>
            <td><b>{{ $bisabuela3['name'] ?? 'UNKNOWN' }}</b></td>
          </tr>
          <tr>
            <td rowspan="2"><b>{{ $motherMother['name'] ?? 'UNKNOWN' }}</b></td>
            <td><b>{{ $bisabuelo4['name'] ?? 'UNKNOWN' }}</b></td>
          </tr>
          <tr>
            <td><b>{{ $bisabuela4['name'] ?? 'UNKNOWN' }}</b></td>
          </tr>
        </tbody>
      </table>
    </div>

    <table class="footer-table" style="width: 100%; margin-top: 60px; font-size: 14px;">
      <tr>
        <td style="width: 50%; text-align: left;">
          <div style="width: 80%; margin-bottom: 5px; border-top: 1px solid #000; margin-left: 0;"></div>
          <div style="text-align: center; width: 80%;">Signed</div>
        </td>
        <td style="width: 50%; text-align: right;">
          Register Number: {{$dog['number']}}<br>
          Date: {{ \Carbon\Carbon::parse($dog['date'])->format('F d, Y') }}
        </td>
      </tr>
    </table>


  </div>
</body>
</html>
