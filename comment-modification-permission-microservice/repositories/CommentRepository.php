<?php

namespace CommentPermissionMicroservice\Repositories;

use PDO;

class CommentRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function canModifyComment(int $commentId, int $userId): bool
    {
        $stmt = $this->db->prepare("
            SELECT user_id
            FROM comments
            WHERE id = :comment_id
        ");
        $stmt->execute([':comment_id' => $commentId]);

        $comment = $stmt->fetch(PDO::FETCH_ASSOC);

        return $comment && $comment['user_id'] === $userId;
    }
}