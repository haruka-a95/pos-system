<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeskController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\LoginController;

// ログイン・ログアウト
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/login-status', [LoginController::class, 'loginStatus']);

// 管理者
Route::middleware(['auth:sanctum'])->group(function () {
    // 商品管理
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);

    // カテゴリ管理
    Route::apiResource('categories', CategoryController::class)->except(['index']);

    // テーブル管理
    Route::apiResource('desks', DeskController::class)->except(['index']);
});

// 商品取得
Route::get('products', [ProductController::class, 'index']);
// カテゴリ取得
Route::get('categories', [CategoryController::class, 'index']);
// テーブル取得
Route::apiResource('desks', DeskController::class)->only(['index']);

// 注文関連（ログイン不要）
Route::apiResource('orders', OrderController::class);
Route::apiResource('order-items', OrderItemController::class);
Route::get('orders/{deskId}/items', [OrderController::class, 'items']);

// 検索
Route::get('products/search', [ProductController::class, 'search']);
