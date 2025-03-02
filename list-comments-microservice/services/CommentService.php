<?php

namespace ListCommentsMicroservice\Services;

use ListCommentsMicroservice\Repositories\CommentRepository;

class CommentService
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function listComments(int $recipeId): array
    {
        return $this->commentRepository->listComments($recipeId);
    }
}