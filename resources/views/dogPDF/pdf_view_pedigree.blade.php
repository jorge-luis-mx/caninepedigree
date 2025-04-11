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

    <table class="info-table">
      <tr>
        <td><strong>Name:</strong></td>
        <td>Club Booger 'Samo'</td>
        <td><strong>Breed:</strong></td>
        <td>American Pit Bull Terrier</td>
      </tr>
      <tr>
        <td><strong>Sex:</strong></td>
        <td>Male</td>
        <td><strong>Color:</strong></td>
        <td>Buckskin</td>
      </tr>
      <tr>
        <td><strong>Born:</strong></td>
        <td>January 10, 2020</td>
        <td><strong>Breeder:</strong></td>
        <td>Luis Hernández</td>
      </tr>
      <tr>
        <td><strong>Owner:</strong></td>
        <td>Jorge Luis Gomez</td>
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
            <td rowspan="4"><b>(Sire) <a href="#">Max</a></b></td>
            <td rowspan="2"><b><a href="#">Bruno</a></b></td>
            <td><a href="#">Rex</a></td>
          </tr>
          <tr>
            <td><a href="#">Canela</a></td>
          </tr>
          <tr>
            <td rowspan="2"><b><a href="#">Bella</a></b></td>
            <td><a href="#">Duke</a></td>
          </tr>
          <tr>
            <td><a href="#">Nala</a></td>
          </tr>
          <tr>
            <td rowspan="4"><b>(Dam) <a href="#">Luna</a></b></td>
            <td rowspan="2"><b><a href="#">Leo</a></b></td>
            <td><a href="#">Simba</a></td>
          </tr>
          <tr>
            <td><a href="#">Kira</a></td>
          </tr>
          <tr>
            <td rowspan="2"><b><a href="#">Maya</a></b></td>
            <td><a href="#">Zeus</a></td>
          </tr>
          <tr>
            <td><a href="#">Frida</a></td>
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
          Register Number: DOG-00055<br>
          Date: January 2, 2025
        </td>
      </tr>
    </table>


  </div>
</body>
</html>
