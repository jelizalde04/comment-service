<?php

namespace DeleteCommentMicroservice\Services;

use DeleteCommentMicroservice\Repositories\CommentRepository;

class CommentService
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function deleteComment(int $commentId, int $userId): bool
    {
        return $this->commentRepository->deleteComment($commentId, $userId);
    }
}