<?php

namespace CommentPermissionMicroservice\Controllers;

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use CommentPermissionMicroservice\Services\CommentPermissionService;
use CommentPermissionMicroservice\Middlewares\AuthMiddleware;

class CommentPermissionController
{
    private $commentPermissionService;

    public function __construct(CommentPermissionService $commentPermissionService)
    {
        $this->commentPermissionService = $commentPermissionService;
    }

    public function __invoke(ServerRequestInterface $request, array $vars): Response
    {
        $commentId = $vars['comment_id'];

        // Get user ID from JWT
        $userId = AuthMiddleware::getUserIdFromToken($request);
        if (!$userId) {
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(['error' => 'Unauthorized']));
        }

        // Check permission
        $hasPermission = $this->commentPermissionService->canModifyComment($commentId, $userId);

        return new Response(200, ['Content-Type' => 'application/json'], json_encode(['can_modify' => $hasPermission]));
    }
}