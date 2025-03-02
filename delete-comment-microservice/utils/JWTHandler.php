<?php

namespace DeleteCommentMicroservice\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    private static $key;

    public static function setKey(string $key): void
    {
        self::$key = $key;
    }

    public static function generateJWT(int $userId): string
    {
        $payload = [
            'user_id' => $userId,
            'exp' => time() + 3600, // Token expires in 1 hour
        ];

        return JWT::encode($payload, self::$key, 'HS256');
    }

    public static function validateJWT(string $token): array
    {
        try {
            return (array) JWT::decode($token, new Key(self::$key, 'HS256'));
        } catch (\Exception $e) {
            return [];
        }
    }
}