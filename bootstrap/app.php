<?php

use LaravLite\Config;
use LaravLite\Middleware\AuthMiddleware;
use LaravLite\Http\Response;
use LaravLite\Facades\Route;
use LaravLite\Middleware\CsrfMiddleware;
use LaravLite\Middleware\GlobalMiddleware;

require __DIR__ . '/helpers.php';
Route::aliasMiddleware('auth', AuthMiddleware::class);
Route::aliasMiddleware('global', GlobalMiddleware::class);
Route::aliasMiddleware('csrf', CsrfMiddleware::class);
//Route::aliasMiddleware('api', ApiMiddleware::class);

Route::group(['middleware' => 'csrf'], function () {
    require __DIR__ . '/../routes/web.php';
});

Config::load(
    __DIR__ . '/../config',
    __DIR__ . '/cache/config.php'
);

Route::group(['prefix' => 'api'], function () {
    require __DIR__ . '/../routes/api.php';
});

$response = Route::dispatch();

if ($response instanceof Response) {
    $response->send();
}
