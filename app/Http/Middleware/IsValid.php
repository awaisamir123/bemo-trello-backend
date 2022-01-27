<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsValid
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
        if(isset($_GET['access_token']) && $_GET['access_token'] == '42gA1S5') {
            return $next($request);
        }

        return \response([
            "statusCode" => Response::HTTP_BAD_GATEWAY,
            'message' => 'Invalid Token',
            'error' => 'Not valid access. Please correct your access token.'
        ]);
    }
}
