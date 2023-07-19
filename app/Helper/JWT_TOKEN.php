<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWT_TOKEN {
    /**
     * Generate JWT Token
     * @param string $user_email
     * @return string
     */
    public static function create_token( $user_email ): string{
        $key = env( 'JWT_KEY' );
        $payload = [
            'iss' => 'pos-token',
            'iat' => time(),
            'exp' => time() + ( 60 * 60 * 2 ), // expired after 2 hours
            'user_email' => $user_email,
        ];
        return JWT::encode( $payload, $key, 'HS256' );
    }
    /**
     * Generate JWT Token
     * @param string $token
     * @return mixed
     */
    public static function verify_token( $token ) {
        try {
            $key = env( 'JWT_KEY' );
            $decode = JWT::decode( $token, new Key( $key, 'HS256' ) );
            return $decode->user_email;
        } catch ( \Throwable $th ) {
            return 'unauthorized';
        }

    }

    /**
     * Generate JWT Token for Reset password
     * @param string $user_email
     * @return string
     */
    public static function reset_token( $user_email ): string{
        $key = env( 'JWT_KEY' );
        $payload = [
            'iss' => 'pos-token',
            'iat' => time(),
            'exp' => time() + ( 60 * 5 ), // expired after 5 minutes
            'user_email' => $user_email,
        ];
        return JWT::encode( $payload, $key, 'HS256' );
    }
}