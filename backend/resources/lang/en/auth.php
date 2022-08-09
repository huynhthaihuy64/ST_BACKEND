<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'login' => [
        'success' => "Successfully logged in",
        'fail' => "Login fail"
    ],

    'profile' => [
        'success' => "Successfully",
        'fail' => "Fail",
    ],

    'refreshToken' => [
        'success' => 'Token refresh success'
    ],

    'logout' => [
        'success' => "Successfully logged out",
    ],

    'validate' => [
        'password.required' => "Password email is required",
        'email.required' => "The email is required",
        'email.email' => "Email not be empty",
    ],
];
