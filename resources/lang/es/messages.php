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
        'profile'=>[
            'dashboard' => 'Bienvenido',
            'profile' => 'Perfil',
            'logOut'=>'Cerrar sesión'
        ]
    ],
    'main'=>[
        'dashboard' => 'Bienvenido',

        'dogs'=>[
            'title'=>'',
            'btn'=>'Agregar un perro',
            'search'=>'Introduce el nombre del perro',
            'placeholder'=>'Introduce el nombre del perro...'
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

        'detailsDogs'=>[
            'title'=>'Información del perro',
        ],

        'pedigree'=>[
            'title'=>'Pedigrí',
        ],
    ]
];

