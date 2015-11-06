<?php
return [
    'Requests' => [
        'owner' => [
            'class' => 'Users',
            'userIndexValidator' => 'id' // field in Auth return used for authorized acess;
        ],
        'target' => [
            'class' => 'Users',
            'userIndexValidator' => 'id'
        ],
        'resources' => [
            'class' => 'resources'
        ],
        'services' => [
            'class' => 'services'
        ],
        'admin' => [
            'active' => true, // system cotain admin
            'userIndexValidator' => 'id', // field in auth return used for inditify admin; obs admin = all acess;
            'value' => 100 // value the compair field indentify admin
        ]
    ]
];
