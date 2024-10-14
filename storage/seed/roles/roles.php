<?php

return [
    'admin' => [
        'slug' => 'admin',
        'name' => 'Администратор',
        'permissions' => json_encode([
            'platform.index' => 1,
            'platform.systems.roles' => 1,
            'platform.systems.users' => 1,
            'platform.systems.attachment' => 1
        ]),
    ],
    'client' => [
        'slug' => 'client',
        'name' => 'Клиент',
        'permissions' => json_encode('')
    ]
];
