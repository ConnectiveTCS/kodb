<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is an admin
        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

        return redirect('/')->with('error', 'You do not have admin access');
    }
}
