<?php
return [
    'Requests_plugin' => [
        'owner' => [
            'class' => 'Users', // class table in the owner
            'userIndexValidator' => 'id' // field in Auth return used for authorized acess;
        ],
        'target' => [
            'class' => 'Users', // class table in the target
            'userIndexValidator' => 'id'
        ],
        'resources' => [
            'class' => 'resources' // class table in the resources
        ],
        'services' => [
            'class' => 'services' // class table in the service
        ],
        'admin' => [
            'active' => true, // system cotain admin
            'userIndexValidator' => 'id', // field in auth return used for inditify admin; obs admin = all acess;
            'value' => 100 // value the compair field indentify admin
        ],
        'relations' => [
            'belongsTo' => [
                // 'User.Users' => [
                // ],
                // 'Example.Class' => [
                //     'className' => 'Example.Class',
                //     'foreignKey' => 'class_id',
                //     'bindingKey' => 'id',
                //     'conditions' => ['Class.is_active' => true],
                //     'joinType' => 'INNER',
                //     'finder' => 'all'
                // ]
            ],
            'belongsToMany' => [
                // 'Example.Class' => [
                //     'className' => 'Example.Class',
                //     'joinTable' => 'addresses_classes',
                //     'foreignKey' => 'address_id',
                //     'targetForeignKey' => 'class_id',
                //     'conditions' => ['Class.is_active' => true],
                //     'through' => 'AddressesClasses',
                //     'dependent' => false,
                //     'cascadeCallbacks' => true,
                //     'propertyName' => 'class',
                //     'strategy' => 'select',
                //     'saveStrategy' => 'replace',
                //     'finder' => 'all'
                // ]
            ],
            'hasOne' => [
                // 'Example.Class' => [
                //     'className' => 'Example.Class',
                //     'foreignKey' => 'class_id',
                //     'bindingKey' => '',
                //     'conditions' => ['Class.is_active' => true],
                //     'joinType' => 'INNER',
                //     'dependent' => false,
                //     'cascadeCallbacks' => true,
                //     'propertyName' => 'class',
                //     'finder' => 'all'
                // ]
            ],
            'hasMany' => [
                // 'Example.Class' => [
                //     'className' => 'Example.Class',
                //     'foreignKey' => 'address_id',
                //     'bindingKey' => 'id',
                //     'conditions' => ['Class.is_active' => true],
                //     'sort' => ['Class.field' => 'ASC'],
                //     'joinType' => 'INNER',
                //     'dependent' => false,
                //     'cascadeCallbacks' => true,
                //     'propertyName' => 'classes',
                //     'strategy' => 'select',
                //     'finder' => 'all'
                // ]
            ]
        ],
        'rules' => [
            // 'ruleName' => [
            //  'keys' => [],
            //  'tableName' => ''
            // ],
            
            // 'existsIn' => [
            //  'keys' => [],
            //  'tableName' => []
            // ]
        ]
    ]
];
