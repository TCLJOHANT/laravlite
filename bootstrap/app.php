<?php

use LaravLite\Config;
use LaravLite\Middleware\AuthMiddleware;
use LaravLite\Http\Response;
use LaravLite\Facades\Route;

require __DIR__ . '/helpers.php';
require __DIR__ . '/../routes/web.php';

Config::load(
    __DIR__ . '/../config',
    __DIR__ . '/cache/config.php'
);

Route::aliasMiddleware('auth', AuthMiddleware::class);
Route::group(['prefix' => 'api'], function () {
    require __DIR__ . '/../routes/api.php';
});

$response = Route::dispatch();

if ($response instanceof Response) {
    $response->send();
}
