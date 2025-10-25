<?php

use LaravLite\Http\Request;
use LaravLite\Facades\Route;

Route::get('', function (Request $request) {
    return response()->json(["message" => "Welcome to API laravel Lite"]);
});
