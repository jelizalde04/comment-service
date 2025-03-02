<?php

namespace CommentPermissionMicroservice\Services;

use CommentPermissionMicroservice\Repositories\CommentRepository;

class CommentPermissionService
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function canModifyComment(int $commentId, int $userId): bool
    {
        return $this->commentRepository->canModifyComment($commentId, $userId);
    }
}