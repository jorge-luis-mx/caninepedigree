<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta id="csrf_token" data-csrf_token="{{ csrf_token() }}">
   <title>Pedigree - Luna</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
   <style>
      .dog-info {
         display: flex;
         align-items: flex-start;
         gap: 20px;
         padding: 10px;
      }

      .dog-info img {
         width: 100%;
         height: auto;
         object-fit: contain;
         box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
      }

      .dog-details {
         display: flex;
         flex-wrap: wrap;
         gap: 10px 30px;
         font-size: 14px;
         color: #333;
         margin-top: 20px;
      }

      .dog-details div {
         display: flex;
         gap: 5px;
         min-width: 200px;
      }

      .dog-details label {
         font-weight: normal;
         color: #8d8b8b;

      }

      .dog-details span {
         font-weight: 600 !important;
         color: black;
         font-size: 16px;
      }

      .table a {
         text-decoration: none;
         color: #333;
         font-weight: bold;
      }

      .table a:hover {
         color: #007BFF;
      }

      table td {
         vertical-align: middle !important;
         text-align: center;
      }

      /* Centrar contenido de celdas con rowspan 4 */
      table td[rowspan="4"] {
         vertical-align: middle;
         /* Centra verticalmente */
         text-align: center;
         /* Centra horizontalmente */
      }

      table td[rowspan="2"] {
         vertical-align: middle;
         /* Centra verticalmente */

      }

      .clickable-td {
         cursor: pointer;
         transition: color 0.3s;
      }

      .clickable-td:hover {
         color: blue;
      }
   </style>
</head>

<body>

<form id="miFormulario" method="POST" action="{{ route('admin.pedigree') }}">
   @csrf
   <section class="section">
      <div class="table-container table is-bordered is-striped is-narrow is-hoverable">
         <table class="table is-fullwidth">
            <tbody>

               <tr>
                  <td colspan="4">
                        <p><label>Name:</label><span>Samo</span></p>
                        <input type="text" id="dogOne" name="dogOne" class="input is-small mb-3" placeholder="Name" required >
                        <select name="dogOne_sex" required>
                           <option value="M">Male</option>
                           <option value="F">Female</option>
                        </select>
                        <input type="text" name="dogOne_color" class="input is-small mt-3" placeholder="Color" required>
                  </td>
               </tr>

               <!-- Árbol genealógico de 4 generaciones -->
               <tr>
                  <td rowspan="8"><b>(Sire)</b><br>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/8f85517967795eeef66c225f7883bdcb">Max</a>
                     <input type="text" id="father" name="father" class="input is-small mb-3" placeholder="Name" required>
                     <select name="father_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="father_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td rowspan="4">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/bf8229696f7a3bb4700cfddef19fa23f">Bruno</a>
                     <input type="text" id="fatherFather" name="fatherFather" class="input is-small mb-3" placeholder="Name" required>
                     <select name="fatherFather_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="fatherFather_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td rowspan="2">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/7e7757b1e12abcb736ab9a754ffb617a">Rex</a>
                     <input type="text" id="bisabuelo1" name="bisabuelo1" class="input is-small mb-3" placeholder="Name" required>
                     <select name="bisabuelo1_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="bisabuelo1_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/7ef605fc8dba5425d6965fbd4c8fbe1f">pongo</a>
                     <input type="text" id="tatarabuelo1" name="tatarabuelo1" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuelo1_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="tatarabuelo1_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/a8f15eda80c50adb0e71943adc8015cf">Lola</a>
                     <input type="text" id="tatarabuela1" name="tatarabuela1" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuela1_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="tatarabuela1_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td rowspan="2">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/5878a7ab84fb43402106c575658472fa">Canela</a>
                     <input type="text" id="bisabuela1" name="bisabuela1" class="input is-small mb-3" placeholder="Name" required>
                     <select name="bisabuela1_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="bisabuela1_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/37a749d808e46495a8da1e5352d03cae">Toto</a>
                     <input type="text" id="tatarabuelo2" name="tatarabuelo2" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuelo2_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="tatarabuelo2_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/b3e3e393c77e35a4a3f3cbd1e429b5dc">Mimi</a>
                     <input type="text" id="tatarabuela2" name="tatarabuela2" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuela2_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="tatarabuela2_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td rowspan="4">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/82161242827b703e6acf9c726942a1e4">Bella</a>
                     <input type="text" id="fatherMother" name="fatherMother" class="input is-small mb-3" placeholder="Name" required>
                     <select name="fatherMother_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="fatherMother_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td rowspan="2">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/006f52e9102a8d3be2fe5614f42ba989">Duke</a>
                     <input type="text" id="bisabuelo2" name="bisabuelo2" class="input is-small mb-3" placeholder="Name" required>
                     <select name="bisabuelo2_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="bisabuelo2_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/1d7f7abc18fcb43975065399b0d1e48e">Chip</a>
                     <input type="text" id="tatarabuelo3" name="tatarabuelo3" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuelo3_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="tatarabuelo3_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/2a79ea27c279e471f4d180b08d62b00a">Lucy</a>
                     <input type="text" id="tatarabuela3" name="tatarabuela3" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuela3_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="tatarabuela3_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td rowspan="2">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/3636638817772e42b59d74cff571fbb3">Nala</a>
                     <input type="text" id="bisabuela2" name="bisabuela2" class="input is-small mb-3" placeholder="Name" required>
                     <select name="bisabuela2_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="bisabuela2_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/1c9ac0159c94d8d0cbedc973445af2da">Fang</a>
                     <input type="text" id="tatarabuelo4" name="tatarabuelo4" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuelo4_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="tatarabuelo4_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/6c4b761a28b734fe93831e3fb400ce87">Daisy</a>
                     <input type="text" id="tatarabuela4" name="tatarabuela4" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuela4_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="tatarabuela4_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>

               <!-- DAM -->
               <tr>
                  <td rowspan="8"><b>(Dam)</b><br>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/8f53295a73878494e9bc8dd6c3c7104f">Luna</a>
                     <input type="text" id="mother" name="mother" class="input is-small mb-3" placeholder="Name" required>
                     <select name="mother_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="mother_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td rowspan="4">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/38af86134b65d0f10fe33d30dd76442e">Leo</a>
                     <input type="text" id="motherFather" name="motherFather" class="input is-small mb-3" placeholder="Name" required>
                     <select name="motherFather_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F" >Female</option>
                     </select>
                     <input type="text" name="motherFather_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td rowspan="2">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/149e9677a5989fd342ae44213df68868">simba</a>
                     <input type="text" id="bisabuelo3" name="bisabuelo3" class="input is-small mb-3" placeholder="Name" required>
                     <select name="bisabuelo3_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="bisabuelo3_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/06409663226af2f3114485aa4e0a23b4">Milo</a>
                     <input type="text" id="tatarabuelo5" name="tatarabuelo5" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuelo5_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="tatarabuelo5_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/140f6969d5213fd0ece03148e62e461e">Flor</a>
                     <input type="text" id="tatarabuela5" name="tatarabuela5" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuela5_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="tatarabuela5_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td rowspan="2">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/a4a042cf4fd6bfb47701cbc8a1653ada">Kira</a>
                     <input type="text" id="bisabuela3" name="bisabuela3" class="input is-small mb-3" placeholder="Name" required>
                     <select name="bisabuela3_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="bisabuela3_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/b73ce398c39f506af761d2277d853a92">Bingo</a>
                     <input type="text" id="tatarabuelo6" name="tatarabuelo6" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuelo6_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="tatarabuelo6_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/bd4c9ab730f5513206b999ec0d90d1fb">Frida</a>
                     <input type="text" id="tatarabuela6" name="tatarabuela6" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuela6_sex" required  class="is-hidden">
                        <option value="M" >Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="tatarabuela6_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td rowspan="4">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/96da2f590cd7246bbde0051047b0d6f7">Maya</a>
                     <input type="text" id="motherMother" name="motherMother" class="input is-small mb-3" placeholder="Name" required>
                     <select name="motherMother_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="motherMother_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td rowspan="2">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/1ff8a7b5dc7a7d1f0ed65aaa29c04b1e">Zeus</a>
                     <input type="text" id="bisabuelo4" name="bisabuelo4" class="input is-small mb-3" placeholder="Name" required>
                     <select name="bisabuelo4_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="bisabuelo4_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/82aa4b0af34c2313a562076992e50aa3">Truman</a>
                     <input type="text" id="tatarabuelo7" name="tatarabuelo7" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuelo7_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="tatarabuelo7_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/0777d5c17d4066b82ab86dff8a46af6f">Lluvia</a>
                     <input type="text" id="tatarabuela7" name="tatarabuela7" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuela7_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="tatarabuela7_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td rowspan="2">
                     <a href="http://www.caninepedigree-dev.com/pedigrees/f7e6c85504ce6e82442c770f7c8606f0">Frida</a>
                     <input type="text" id="bisabuela4" name="bisabuela4" class="input is-small mb-3" placeholder="Name" required>
                     <select name="bisabuela4_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="bisabuela4_color" class="input is-small" placeholder="Color" required>
                  </td>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/fa7cdfad1a5aaf8370ebeda47a1ff1c3">Odie</a>
                     <input type="text" id="tatarabuelo8" name="tatarabuelo8" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuelo8_sex" required  class="is-hidden">
                        <option value="M" selected>Male</option>
                        <option value="F">Female</option>
                     </select>
                     <input type="text" name="tatarabuelo8_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>
               <tr>
                  <td>
                     <a href="http://www.caninepedigree-dev.com/pedigrees/9766527f2b5d3e95d4a733fcfb77bd7e">Moka</a>
                     <input type="text" id="tatarabuela8" name="tatarabuela8" class="input is-small mb-3" placeholder="Name" required>
                     <select name="tatarabuela8_sex" required  class="is-hidden">
                        <option value="M">Male</option>
                        <option value="F" selected>Female</option>
                     </select>
                     <input type="text" name="tatarabuela8_color" class="input is-small" placeholder="Color" required>
                  </td>
               </tr>

            </tbody>
         </table>
      </div>
   </section>

   <button type="submit">Enviar</button>

</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
   const form = document.getElementById('miFormulario');
   const csrfToken = document.querySelector('#csrf_token')?.dataset.csrf_token;

   if (!form || !csrfToken) {
      alert("Formulario o token CSRF no encontrado.");
      return;
   }

   form.addEventListener('submit', function (e) {
      e.preventDefault();

      const data = new FormData(form);
      function obtenerPerro(campo) {
         return {
            name: data.get(campo),
            sex: data.get(`${campo}_sex`),
            color: data.get(`${campo}_color`)
         };
      }
      const generations = {
         1: {
            dogOne: obtenerPerro('dogOne')
         },
         2: {
            father: obtenerPerro('father'),
            mother: obtenerPerro('mother')
         },
         3: {
            fatherFather: obtenerPerro('fatherFather'),
            fatherMother: obtenerPerro('fatherMother'),
            motherFather: obtenerPerro('motherFather'),
            motherMother: obtenerPerro('motherMother'),
         },
         4: {
            bisabuelo1: obtenerPerro('bisabuelo1'),
            bisabuela1: obtenerPerro('bisabuela1'),
            bisabuelo2: obtenerPerro('bisabuelo2'),
            bisabuela2: obtenerPerro('bisabuela2'),
            bisabuelo3: obtenerPerro('bisabuelo3'),
            bisabuela3: obtenerPerro('bisabuela3'),
            bisabuelo4: obtenerPerro('bisabuelo4'),
            bisabuela4: obtenerPerro('bisabuela4')
         },
         5: {
            tatarabuelo1: obtenerPerro('tatarabuelo1'),
            tatarabuela1: obtenerPerro('tatarabuela1'),
            tatarabuelo2: obtenerPerro('tatarabuelo2'),
            tatarabuela2: obtenerPerro('tatarabuela2'),
            tatarabuelo3: obtenerPerro('tatarabuelo3'),
            tatarabuela3: obtenerPerro('tatarabuela3'),
            tatarabuelo4: obtenerPerro('tatarabuelo4'),
            tatarabuela4: obtenerPerro('tatarabuela4'),
            tatarabuelo5: obtenerPerro('tatarabuelo5'),
            tatarabuela5: obtenerPerro('tatarabuela5'),
            tatarabuelo6: obtenerPerro('tatarabuelo6'),
            tatarabuela6: obtenerPerro('tatarabuela6'),
            tatarabuelo7: obtenerPerro('tatarabuelo7'),
            tatarabuela7: obtenerPerro('tatarabuela7'),
            tatarabuelo8: obtenerPerro('tatarabuelo8'),
            tatarabuela8: obtenerPerro('tatarabuela8')
         }
      };

      enviarGeneraciones(generations, csrfToken);
   });

   async function enviarGeneraciones(generations, csrfToken) {
      try {
         const response = await fetch("/admin/pedigree", {
            method: "POST",
            headers: {
               "Content-Type": "application/json",
               "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ generations })
         });

         const data = await response.json();

         if (!response.ok) {
            console.error("Error en la respuesta:", data);
            alert(data?.message || "Error en el envío");
            return;
         }

         alert("Perros procesados correctamente.");
      } catch (error) {
         console.error("Error de red o excepción:", error);
         alert("No se pudo conectar con el servidor.");
      }
   }
});

</script>

</body>

</html>