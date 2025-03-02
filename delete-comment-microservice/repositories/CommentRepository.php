<?php

namespace DeleteCommentMicroservice\Repositories;

use PDO;

class CommentRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function deleteComment(int $commentId, int $userId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM comments
            WHERE id = :comment_id AND user_id = :user_id
        ");
        $stmt->execute([
            ':comment_id' => $commentId,
            ':user_id' => $userId,
        ]);

        return $stmt->rowCount() > 0;
    }
}