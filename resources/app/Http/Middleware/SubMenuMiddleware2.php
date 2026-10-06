<?php

namespace App\Http\Middleware;

use App\Models\MenuRights;
use App\Models\RightsLevel3;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubMenuMiddleware2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $title)
    {
        if (isset($title)) {
            $menuRight = RightsLevel3::select('id')->where('title', $title)->first();
            $menuRightAccess = MenuRights::where('level_id', $menuRight->id)
                ->where('user_id', Auth::User()->id)
                ->where('type', 'Level 3')
                ->first();

            if ($menuRightAccess) {
                return $next($request);
            } else {
                return redirect('home')->with('access_granted', 'Insufficient Permission.');
            }
        } else {
            return redirect('home')->with('access_granted', 'Permission Denied.');
        }
    }
}