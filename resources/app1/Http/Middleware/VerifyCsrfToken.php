<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next)
    {
        if($request->route()->named('logout')) {
            if (auth()->check()) {
                auth()->logout();
            }

            return redirect('/');
        }
        // return $request;
        return parent::handle($request, $next);
    }
}
