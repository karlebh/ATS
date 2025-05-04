<?php

namespace App\Http\Middleware;

use App\Constants\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlyAdminAllowed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user->role !== UserRole::ADMIN) {
            return response()->json(['message' => 'Only admins can make this action'], 403);
        }

        return $next($request);
    }
}
