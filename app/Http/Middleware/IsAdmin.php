<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Wpuszczamy dalej
        }

        abort(403, 'Brak uprawnień. Dostęp do tej sekcji mają tylko administratorzy.');
    }
}
