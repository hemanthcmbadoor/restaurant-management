<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserAccountStatus
{
    /**
     * Handle an incoming request.
     * Checking Users Account Status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if($user->role == 'user' && $user->status == 'false'){
            return response()->json(['message' => 'Your account is deactive.'],401); 
        }

        return $next($request);
    }
}
