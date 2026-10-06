<?php

namespace App\Http\Middleware;

use App\Models\VoucherRights;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherRightAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $voucherName, $option)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', $voucherName)
            ->where('right_name', $option)
            ->first();
        if ($voucherRight) {
            return $next($request);
        } else {
            return redirect('home')->with('access_granted', 'Insufficient Permission.');
        }
    }
}