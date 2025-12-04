<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if a language is set in the session
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        }

        return $next($request);
    }
}