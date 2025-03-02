<?php

namespace UpdateCommentMicroservice\Services;

use UpdateCommentMicroservice\Repositories\CommentRepository;
use UpdateCommentMicroservice\Models\Comment;

class CommentService
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function updateComment(int $commentId, int $userId, string $content): ?Comment
    {
        return $this->commentRepository->updateComment($commentId, $userId, $content);
    }
}