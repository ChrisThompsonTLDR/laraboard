<?php

return [
    'route_prefix' => 'forum',
    'table_prefix' => 'laraboard_',
    'user' => [
        'route'        => '/user/',
        'slug'         => 'username',
        'display_name' => 'username', //  where in the user table their username is located
        'timezone'     => 'timezone', //  where in the user table their timezone is located; set to false or null if you don't want conversions
        'admin_role'   => 'admin',    //  Role for admins, will be used in the Gate logic
    ],
    'category' => [
        'slug_limit' => 50,  //  character length of slug
    ],
    'board' => [
        'limit' => 15,  //  boards per page
    ],
    'thread' => [
        'limit' => 15,  //  threads per page
    ],
    'view' => [
        'layout' => 'layouts.app',  //  blade to use for layouts
        'flash'  => 'laraboard::blocks.flash', //  blade to use for flash messages, if set to false or null, no blade will be used (assumes your site already renders these)
    ]
];