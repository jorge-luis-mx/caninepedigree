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
            'title'=>'Dogs',
            'btn'=>'Add a Dog',
            'search'=>"Enter a name to search for registered dogs",
            'placeholder'=>'Type a name to search...',
            'previous' => 'Previous',
            'next' => 'Next'
        ],
        'dogDetails'=>[
            'name'=>'Name',
            'title'=>'Dog Information',
            'btnRegistration'=>'Registration Certificate',
            'btnPedigree'=>'Pedigree Certificate',
            'owner'=>'Owner',
            'regNo'=>'Reg. No',
            'breed'=>'Breed',
            'color'=>'Color',
            'sex'=>'Sex',
            'date'=>'Date of Birth',
            'registered'=>'Registered On'
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
            'kennel_name'=>'Kennel Name',

        ],


        'pedigree'=>[
            'title'=>'Pedidree',
            'btnAddPedigree'=>'Add Pedigree',
            'placeInput'=>'Search...',
            'btnSearch'=>'Search',
            'sire'=>'Sire',
            'dam'=>'Dam',
            'first'=>'First',
            'second'=>'Second',
            'third'=>'Third',
            'fourth'=>'Fourth',
            'btnSubmit'=>'Send'
        ],
        'swalPedigree'=>[
            'title'=>'Missing Fields',
            'text'=>'Please complete the required fields (name and color).'
        ]


    ],
    'files'=>[
        'upload_photos' => 'Upload Photos',
        'select_files' => 'Select Files',
        'no_files_selected' => 'No files selected',
    ]
];
