<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->nivel !== 'admin') {
            return redirect('/dashboard')->with('error', 'Acesso negado! Apenas administradores.');
        }

        return $next($request);
    }
}