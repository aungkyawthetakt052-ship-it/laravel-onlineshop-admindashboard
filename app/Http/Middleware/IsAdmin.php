<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
         if (!Auth::check() || !in_array(Auth::user()->user_type, ['admin', 'superadmin'])) {
            Auth::logout();

            return redirect()->route('admin.loginpage')
                ->withErrors(['email' => 'Please login as admin.']);
        }

        return $next($request);
    }
}
