<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleManagementMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle( Request $request, Closure $next, ...$roles ): Response {
        if (  ( in_array( $request->header( 'role' ), $roles ) && in_array( 'admin', $roles ) ) || ( in_array( $request->header( 'role' ), $roles ) && in_array( 'manager', $roles ) ) || ( in_array( $request->header( 'role' ), $roles ) && in_array( 'seller', $roles ) ) ) {
            return $next( $request );
        }
        return response()->json( ['status' => 'failed', 'message' => 'You are not able to do this action'], 200 );
    }
}