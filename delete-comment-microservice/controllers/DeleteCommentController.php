<?php

namespace DeleteCommentMicroservice\Controllers;

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use DeleteCommentMicroservice\Services\CommentService;
use DeleteCommentMicroservice\Middlewares\AuthMiddleware;

class DeleteCommentController
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function __invoke(ServerRequestInterface $request, array $vars): Response
    {
        $commentId = $vars['id'];

        // Get user ID from JWT
        $userId = AuthMiddleware::getUserIdFromToken($request);
        if (!$userId) {
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(['error' => 'Unauthorized']));
        }

        // Delete comment
        $success = $this->commentService->deleteComment($commentId, $userId);

        if (!$success) {
            return new Response(404, ['Content-Type' => 'application/json'], json_encode(['error' => 'Comment not found']));
        }

        return new Response(200, ['Content-Type' => 'application/json'], json_encode(['message' => 'Comment deleted successfully']));
    }
}