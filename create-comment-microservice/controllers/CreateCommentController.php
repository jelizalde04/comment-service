<?php

namespace CreateCommentMicroservice\Controllers;

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use CreateCommentMicroservice\Services\CommentService;
use CreateCommentMicroservice\Middlewares\AuthMiddleware;

class CreateCommentController
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
        if (empty($data['recipe_id']) || empty($data['content'])) {
            return new Response(400, ['Content-Type' => 'application/json'], json_encode(['error' => 'Invalid input']));
        }

        // Get user ID from JWT
        $userId = AuthMiddleware::getUserIdFromToken($request);
        if (!$userId) {
            return new Response(401, ['Content-Type' => 'application/json'], json_encode(['error' => 'Unauthorized']));
        }

        // Create comment
        $comment = $this->commentService->createComment($userId, $data['recipe_id'], $data['content']);

        return new Response(201, ['Content-Type' => 'application/json'], json_encode($comment));
    }
}