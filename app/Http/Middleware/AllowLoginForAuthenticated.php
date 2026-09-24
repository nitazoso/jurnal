<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowLoginForAuthenticated extends RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if ($request->is('login')) {
            return $next($request);
        }

        return parent::handle($request, $next, ...$guards);
    }
}