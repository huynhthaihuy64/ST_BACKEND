<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Response::macro('success', function ($data = []) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        });

        Response::macro('error', function (string $message = '') {
            return response()->json([
                'status' => 400,
                'message' => $message
            ]);
        });

        Response::macro('unauthenticated', function () {
            return response()->json([], 401);
        });
    }
}
