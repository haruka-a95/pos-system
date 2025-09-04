<?php

use App\Http\Controllers\Api\DeskController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::apiResource('todos', TodoController::class);
// apiResourceを使用することで自動で CRUD の API エンドポイントを作成
// 例:
//   一覧取得   GET    /api/todos
//   新規作成   POST   /api/todos
//   詳細取得   GET    /api/todos/{id}
//   更新       PUT    /api/todos/{id}
//   削除       DELETE /api/todos/{id}

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('desks', DeskController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('order-items', OrderItemController::class);
Route::get('orders/{deskId}/items', [OrderController::class, 'items']);