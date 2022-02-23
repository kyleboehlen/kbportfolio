<?php

return [
    'user' => [
        'name' => env('ADMIN_USER_NAME'),
        'email' => env('ADMIN_USER_EMAIL'),
        'phone_number' => env('ADMIN_USER_PHONE'),
    ],
    'spotify' => [
        'user_id' => env('SPOTIFY_USER_ID', '1230933361'),
    ],
];
