<?php

return [

    'defaults' => [
        'guard' => 'web',          // boleh 'web' atau 'api'; 'web' oke untuk halaman Blade
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',     // <-- harus ada provider 'users' di bawah
        ],
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',     // boleh juga 'tb_users' kalau kamu tambahkan
        ],
    ],

    'providers' => [
        // Provider 'users' menunjuk ke model TbUser
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\TbUser::class,
        ],

        // (opsional) Provider alias 'tb_users' kalau kamu ingin pakai nama ini
        'tb_users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\TbUser::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
