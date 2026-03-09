<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Koristimo Auth fasadu koja je čišća
    $user = \Illuminate\Support\Facades\Auth::user();

    // Provjeravamo postoji li korisnik i je li mu role_id 3 ili 4
    if ($user && in_array($user->role_id, [3, 4])) {
        return $next($request);
    }

    return response()->json(['message' => 'Pristup odbijen. Nemate administratorske ovlasti.'], 403);
    }
}
