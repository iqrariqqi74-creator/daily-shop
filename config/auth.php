<?php

use App\Models\User;
use App\Models\Customer;

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [

        // =====================================================
        // NORMAL WEB
        // =====================================================
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // =====================================================
        // ADMIN API
        // =====================================================
        // IMPORTANT:
        // Existing admin authentication remains unchanged.
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],

        // =====================================================
        // CUSTOMER API
        // =====================================================
        'customer_api' => [
            'driver' => 'passport',
            'provider' => 'customers',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [

        // =====================================================
        // ADMIN USER
        // =====================================================
        'users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ],

        // =====================================================
        // FRONTEND CUSTOMER
        // =====================================================
        'customers' => [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Resetting
    |--------------------------------------------------------------------------
    */

    'passwords' => [

        'users' => [
            'provider' => 'users',
            'table' => env(
                'AUTH_PASSWORD_RESET_TOKEN_TABLE',
                'password_reset_tokens'
            ),
            'expire' => 60,
            'throttle' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env(
        'AUTH_PASSWORD_TIMEOUT',
        10800
    ),

];