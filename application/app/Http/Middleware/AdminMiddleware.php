<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!isAdmin()) {
            session()->flash('toast', toastMessage('You are not allowed to view this page', 'error'));

            return redirect('/home');
        }

        return $next($request);
    }
}
