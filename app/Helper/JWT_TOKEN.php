<?php
namespace App\Helper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Jwt_token {
    /**
     * Generate JWT Token
     * @param array $user
     * @param int $seconds
     * @return string
     */
    public static function generate_token( array $user, int $seconds = 7200 ): string{
        $key = env( 'JWT_KEY' );
        $payload = [
            'iss'  => 'inventory',
            'iat'  => time(),
            'exp'  => time() + $seconds,
            'user' => $user,
        ];
        return JWT::encode( $payload, $key, 'HS256' );
    }

    /**
     * Verify JWT Token
     * @param string|null $token
     */
    public static function verify_token( string | null $token ) {
        try {
            if ( $token == null ) {
                return 'unauthorized';
            }
            $key = env( 'JWT_KEY' );
            $decode = JWT::decode( $token, new Key( $key, 'HS256' ) );
            return $decode->user;
        } catch ( \Throwable $th ) {
            return 'unauthorized';
        }
    }
}