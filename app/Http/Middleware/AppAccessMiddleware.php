<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\App;

class AppAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $currentUrl = $request->url();

        // Jika admin, berikan akses
        if ($user->id === 1) {
            return $next($request);
        }

        // Cek apakah user memiliki akses ke aplikasi dengan URL tersebut
        $hasAccess = $user->apps()
            ->where('apps.data_status', 1)
            ->where('apps.app_url', $currentUrl)
            ->wherePivot('data_status', 1)
            ->exists();

        if ($hasAccess) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke aplikasi ini.');
    }
}