<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DeskController;


// API は api.php に任せる
// それ以外のURLは Vue SPA 用にキャッチする
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*');



// 管理者用ページ（セッション認証）
// Route::middleware(['auth'])->group(function () {
//     // 商品管理
//     Route::post('products', [ProductController::class, 'store']);
//     Route::put('products/{product}', [ProductController::class, 'update']);
//     Route::delete('products/{product}', [ProductController::class, 'destroy']);

//     // カテゴリ管理
//     Route::apiResource('categories', CategoryController::class)->except(['index']);

//     // テーブル管理
//     Route::apiResource('desks', DeskController::class)->except(['index']);
// });
