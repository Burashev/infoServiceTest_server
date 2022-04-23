<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken()) {
            $user = User::query()->firstWhere('token', $request->bearerToken());
            if ($user) {
                Auth::login($user);
                return $next($request);
            }
        }

        $response = [
            'success' => false,
            'message' => 'Unauthorized',
        ];

        return response()->json($response, 401);
    }
}
