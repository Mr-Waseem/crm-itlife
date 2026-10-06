<?php

namespace App\Http\Middleware;

use App\Models\MenuRights;
use App\Models\RightsLevel1;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMenuAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $actions = $request->route()->getAction();
        if (isset($actions)) {
            $menuRight = RightsLevel1::select('id')->where('title', $actions['menu'])->first();
            $menuRightAccess = MenuRights::where('level_id', $menuRight->id)
                ->where('user_id', Auth::User()->id)
                ->where('type', 'Level 1')
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