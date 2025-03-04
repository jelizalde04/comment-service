<?php

namespace ListCommentsMicroservice\Middlewares;

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use CommentModificationPermissionMicroservice\Utils\JWTHandler;

class AuthMiddleware
{
    private static $failedAttempts = [];
    private static $blockedIPs = [];
    private static $rateLimit = [];
    private const MAX_ATTEMPTS = 3;
    private const BLOCK_TIME = 900; // 15 minutes
    private const RATE_LIMIT_MAX = 100;
    private const RATE_LIMIT_WINDOW = 900; // 15 minutes

    public static function getUserIdFromToken(ServerRequestInterface $request): ?int
    {
        $ip = $request->getServerParams()['REMOTE_ADDR'];

        if (isset(self::$blockedIPs[$ip]) && time() - self::$blockedIPs[$ip] < self::BLOCK_TIME) {
            return null;
        }

        $token = $request->getHeaderLine('Authorization');
        if (empty($token)) {
            self::recordFailedAttempt($ip);
            return null;
        }

        $token = str_replace('Bearer ', '', $token);
        $payload = JWTHandler::validateJWT($token);

        if (!$payload || !isset($payload['user_id'])) {
            self::recordFailedAttempt($ip);
            return null;
        }

        return $payload['user_id'];
    }

    public static function handle(ServerRequestInterface $request, callable $next): Response
    {
        $ip = $request->getServerParams()['REMOTE_ADDR'];
        self::checkRateLimit($ip);

        $userId = self::getUserIdFromToken($request);
        if (!$userId) {
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(['error' => 'Unauthorized']));
        }

        return $next($request);
    }

    private static function recordFailedAttempt(string $ip): void
    {
        if (!isset(self::$failedAttempts[$ip])) {
            self::$failedAttempts[$ip] = 1;
        } else {
            self::$failedAttempts[$ip]++;
        }

        if (self::$failedAttempts[$ip] >= self::MAX_ATTEMPTS) {
            self::$blockedIPs[$ip] = time();
            self::logSuspiciousActivity($ip);
        }
    }

    private static function checkRateLimit(string $ip): void
    {
        $currentTime = time();

        if (!isset(self::$rateLimit[$ip])) {
            self::$rateLimit[$ip] = ['count' => 1, 'startTime' => $currentTime];
        } else {
            $elapsedTime = $currentTime - self::$rateLimit[$ip]['startTime'];

            if ($elapsedTime > self::RATE_LIMIT_WINDOW) {
                self::$rateLimit[$ip] = ['count' => 1, 'startTime' => $currentTime];
            } else {
                self::$rateLimit[$ip]['count']++;
            }

            if (self::$rateLimit[$ip]['count'] > self::RATE_LIMIT_MAX) {
                self::logSuspiciousActivity($ip);
                exit(json_encode(['error' => 'Too many requests'], JSON_PRETTY_PRINT));
            }
        }
    }

    private static function logSuspiciousActivity(string $ip): void
    {
        $logFile = __DIR__ . '/../logs/ddos_attempts.log';
        $logEntry = json_encode(['ip' => $ip, 'timestamp' => date('Y-m-d H:i:s')]) . PHP_EOL;

        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
