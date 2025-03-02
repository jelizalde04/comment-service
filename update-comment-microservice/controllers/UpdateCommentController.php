<?php

namespace UpdateCommentMicroservice\Controllers;

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use UpdateCommentMicroservice\Services\CommentService;
use UpdateCommentMicroservice\Middlewares\AuthMiddleware;

class UpdateCommentController
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function __invoke(ServerRequestInterface $request, array $vars): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);

        // Validate input
        if (empty($data['comment_id']) || empty($data['content'])) {
            return new Response(400, ['Content-Type' => 'application/json'], json_encode(['error' => 'Invalid input']));
        }

        // Get user ID from JWT
        $userId = AuthMiddleware::getUserIdFromToken($request);
        if (!$userId) {
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(['error' => 'Unauthorized']));
        }

        // Update comment
        $comment = $this->commentService->updateComment($data['comment_id'], $userId, $data['content']);

        return new Response(200, ['Content-Type' => 'application/json'], json_encode($comment));
    }
}