<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;

class EmailVerification
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, $next)
    {
        if ($request->get('email')) {
            $request->setUserResolver(function() use ($request) {
                return User::where('email', $request->get('email'))->first();
            });
        }

        return $next($request);
    }
}
