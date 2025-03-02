<?php

namespace CreateCommentMicroservice\Middlewares;

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use CreateCommentMicroservice\Utils\JWTHandler;

class AuthMiddleware
{
    public static function getUserIdFromToken(ServerRequestInterface $request): ?int
    {
        $token = $request->getHeaderLine('Authorization');
        if (empty($token)) {
            return null;
        }

        $token = str_replace('Bearer ', '', $token);
        $payload = JWTHandler::validateJWT($token);

        return $payload['user_id'] ?? null;
    }

    public static function handle(ServerRequestInterface $request, callable $next): Response
    {
        $userId = self::getUserIdFromToken($request);
        if (!$userId) {
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(['error' => 'Unauthorized']));
        }

        return $next($request);
    }
}