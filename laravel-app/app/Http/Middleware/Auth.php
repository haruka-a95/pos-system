<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->session()->get('user_id');
        if (!$userId) return response()->json(['message'=>'ログインが必要です'], 401);

        $user = User::find($userId);
        if (!$user || $user->role !== 'admin') {
            return response()->json(['message'=>'閲覧権限がありません'], 403);
        }
        return $next($request);
    }
}
