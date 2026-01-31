<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hash driver that is used to hash passwords
    | for your application. By default, the bcrypt driver is used; however, you
    | may change this to any other driver that is supported by Laravel.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options that should be used when
    | the bcrypt hashing algorithm is used. Accurately tuning these options
    | allows you to verify hashed values at the fastest speed possible.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options that should be used when
    | the argon hashing algorithm is used. These options allow you to control
    | the duration of time that it takes to verify the argon hash values.
    |
    */

    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 1,
    ],

];
