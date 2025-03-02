<?php

namespace CreateCommentMicroservice\Services;

use CreateCommentMicroservice\Repositories\CommentRepository;
use CreateCommentMicroservice\Models\Comment;

class CommentService
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function createComment(int $userId, int $recipeId, string $content): Comment
    {
        return $this->commentRepository->createComment($userId, $recipeId, $content);
    }
}