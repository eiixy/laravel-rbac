<?php

return [
    'guard' => 'api',
    'middleware' => [
        'jwt.auth',
        'auth.api'
    ]
];
