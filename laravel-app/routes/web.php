<?php

use Illuminate\Support\Facades\Route;

// API は api.php に任せる
// それ以外のURLは Vue SPA 用にキャッチする
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*');
