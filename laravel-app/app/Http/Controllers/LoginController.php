<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !password_verify($request->password, $user->password)) {
           return response()->json(['message' => 'メールアドレスまたはパスワードが違います'], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json(['message' => '管理者のみログイン可能です'], 403);
        }

        // トークン発行
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['message' => 'ログイン成功', 'token' => $token,'user' => $user]);
    }

    public function logout(Request $request)
    {
        // トークン削除
        $request->user->currentAccessToken()->delete();
        return response()->json(['message' => 'ログアウトしました']);
    }

    // ログイン状態チェック
    public function loginStatus(Request $request)
    {
        return response()->json([
            'logged_in' => $request->user() ? true : false,
            'user' => $request->user,
        ]);
    }
}
