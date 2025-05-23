<?php


return [

    'side' => [
        'dashboard' => 'Dashboard',
        'profile' => 'Profile',
        'dogs'=>'Dogs',
        'breeding'=>'Breeding request',
        'pedigree'=>'Pedigree'
    ],
    'nav' => [
        'dogs' => 'Dogs',
        'pedidree' => 'Pedidree',
        'profile'=>[
            'dashboard' => 'Dashboard',
            'profile' => 'Profile',
            'logOut'=>'Log Out'
        ]
    ],
    'main'=>[
        'dashboard' => 'Dashboard',

        'dogs'=>[
            'title'=>'',
            'btn'=>'Add a Dog',
            'search'=>"Enter a name to search for registered dogs",
            'placeholder'=>'Type a name to search...'
            
        ],

        'headerTable'=>[
            'Name',

        ],

        'formDog'=>[
            'title'=>'Register your dog',
            'name'=>'Name',
            'breed'=>'Breed',
            'color'=>'Color',
            'sex'=>'Sex',
            'selectedMale'=>'Male',
            'slectedFemale'=>'Female',
            'date'=>'Date of Birth',
            'sire'=>"Enter the IDDR number or the dog's name (sire)",
            'dam'=>"Enter the IDDR number or the dog's name (dam)",
            'sireEmail'=>'Sire Email',
            'placeholderSireEmail'=>'Enter Sire Email',
            'noteSire'=>'Additional Notes',
            'noteSirePlaceholder'=>'Enter additional details...',
            'damEmail'=>'Dam Email',
            'placeholderDamEmail'=>'Enter Dam Email',
            'noteDam'=>'Additional Notes',
            'noteDamPlaceholder'=>'Enter additional details...',
            'btnSaveDog'=>'Save Dog'
        ],

        'profile' => [
            'title'=>'Profile',
            'subtitle'=>'Edit Profile',
            'first_name'=>'First Name',
            'last_name'=>'Last Name',
            'middle_name'=>'Middle Name',
            'email'=>'Email',
            'phone'=>'Phone Number',

        ],

        'detailsDogs'=>[
            'title'=>'Dog Information',
        ],
        'pedigree'=>[
            'title'=>'Pedidree',
        ],

    ]
];
