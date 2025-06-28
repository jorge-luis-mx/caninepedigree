<?php


return [

    'side' => [
        'dashboard' => 'Bienvenido',
        'profile' => 'Perfil',
        'dogs'=>'Mascotas',
        'breeding'=>'Solicitar Monta',
        'pedigree'=>'Pedigrí'
    ],
    'nav' => [
        'dogs' => 'Mascotas',
        'pedidree' => 'Pedigrí',
        'profile'=>[
            'dashboard' => 'Bienvenido',
            'profile' => 'Perfil',
            'logOut'=>'Cerrar sesión'
        ]
    ],
    'main'=>[
        'dashboard' => 'Bienvenido',

        'dogs'=>[
            'title'=>'Mascotas',
            'btn'=>'Agregar un Perro',
            'search'=>'Ingresa el nombre para buscar perros registrados',
            'placeholder'=>'Escribe un nombre para buscar...',
            'previous' => 'Anterior',
            'next' => 'Siguiente'
        ],

        'dogDetails'=>[
            'name'=>'Nombre',
            'title'=>'Información del perro',
            'btnRegistration'=>'Certificado de Registro',
            'btnPedigree'=>'Certificado de Pedigrí',
            'owner'=>'Dueño',
            'regNo'=>'N.º de Registro',
            'breed'=>'Raza',
            'color'=>'Color',
            'sex'=>'Sexo',
            'date'=>'Fecha de Nacimiento',
            'registered'=>'Registrado'
        ],

        'headerTable'=>[
            'Nombre',

        ],
        'formDog'=>[
            'title' => 'Registrar tu perro',
            'name' => 'Nombre',
            'breed' => 'Raza',
            'color' => 'Color',
            'sex' => 'Sexo',
            'selectedMale'=>'Macho',
            'slectedFemale'=>'Hembra',
            'date' => 'Fecha de nacimiento',
            'sire' =>"Ingresa el número IDDR o el nombre del perro (padre)",
            'dam' => "Ingresa el número IDDR o el nombre de la perra (madre)",
            'sireEmail' => 'Correo electrónico del padre',
            'placeholderSireEmail' => 'Ingresa el correo electrónico del padre',
            'noteSire' => 'Notas adicionales',
            'noteSirePlaceholder' => 'Ingresa detalles adicionales...',
            'damEmail' => 'Correo electrónico de la madre',
            'placeholderDamEmail' => 'Ingresa el correo electrónico de la madre',
            'noteDam' => 'Notas adicionales',
            'noteDamPlaceholder' => 'Ingresa detalles adicionales...',
            'btnSaveDog'=>'Guardar Perro'
        ],


        'profile' => [
            'title'=>'Perfil',
            'subtitle'=>'Editar Perfil',
            'first_name'=>'Nombre',
            'last_name'=>'Apellido Paterno',
            'middle_name'=>'Apellido Materno',
            'email'=>'Correo Electronico',
            'phone'=>'Numero de Telefono',
        ],



        'pedigree'=>[
            'title'=>'Pedigrí',
            'btnAddPedigree'=>'Agregar Pedigrí',
            'placeInput'=>'Buscar...',
            'btnSearch'=>'Buscar',
            'sire'=>'Padre',
            'dam'=>'Madre'
        ],

    ],
    'files'=>[
        'upload_photos' => 'Subir Fotos',
        'select_files' => 'Seleccionar Archivos',
        'no_files_selected' => 'Ningún archivo seleccionado',
    ]
];

