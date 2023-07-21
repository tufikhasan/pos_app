<?php

namespace App\Http\Middleware;

use App\Helper\JWT_TOKEN;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfTokenVerify {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle( Request $request, Closure $next ): Response{
        $token = $request->cookie( 'token' );
        $verify = JWT_TOKEN::verify_token( $token );

        if ( $token && $verify != 'unauthorized' ) {
            return redirect()->route( 'dashboard' ); // Replace 'dashboard' with your desired authenticated route
        } else {
            return $next( $request );
        }
    }
}
