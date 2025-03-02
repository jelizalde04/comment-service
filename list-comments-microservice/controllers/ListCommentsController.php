<?php

namespace ListCommentsMicroservice\Controllers;

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use ListCommentsMicroservice\Services\CommentService;
use ListCommentsMicroservice\Middlewares\AuthMiddleware;

class ListCommentsController
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function __invoke(ServerRequestInterface $request, array $vars): Response
    {
        $recipeId = $vars['recipe_id'];

        // Get user ID from JWT (optional, depending on your requirements)
        $userId = AuthMiddleware::getUserIdFromToken($request);

        // List comments
        $comments = $this->commentService->listComments($recipeId);

        return new Response(200, ['Content-Type' => 'application/json'], json_encode($comments));
    }
}