<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'student') {
            return $next($request);
        }

        abort(403, 'Brak uprawnień. Dostęp do tej sekcji mają tylko kursanci.');
    }
}
