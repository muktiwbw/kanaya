<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Transaction;
use Carbon\Carbon;

class CheckCartExpiration
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
        $transactions = Transaction::where('status', '<=', 1)->where('cart_expiration', '<', Carbon::now()->setTimezone('Asia/Jakarta')->toDateTimeString());

        if($transactions) $transactions->delete();

        return $next($request);

    }
}
