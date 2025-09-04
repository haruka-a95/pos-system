<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\TodoController;

Route::apiResource('todos', TodoController::class);
// apiResourceを使用することで自動で CRUD の API エンドポイントを作成
// 例:
//   一覧取得   GET    /api/todos
//   新規作成   POST   /api/todos
//   詳細取得   GET    /api/todos/{id}
//   更新       PUT    /api/todos/{id}
//   削除       DELETE /api/todos/{id}