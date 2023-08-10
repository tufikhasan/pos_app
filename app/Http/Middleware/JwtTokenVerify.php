<?php

namespace App\Http\Middleware;

use App\Helper\Jwt_token;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtTokenVerify {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle( Request $request, Closure $next ): Response{
        $token = $request->cookie( 'token' );
        $verify = Jwt_token::verify_token( $token );

        if ( 'unauthorized' == $verify ) {
            return redirect()->route( 'signin.page' );
        } else {
            $request->headers->set( 'id', $verify->id );
            $request->headers->set( 'shop_id', $verify->shop_id );
            $request->headers->set( 'email', $verify->email );
            $request->headers->set( 'role', $verify->role );
            return $next( $request );
        }
    }
}
