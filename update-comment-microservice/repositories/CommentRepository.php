<?php

namespace UpdateCommentMicroservice\Repositories;

use PDO;
use UpdateCommentMicroservice\Models\Comment;

class CommentRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function updateComment(int $commentId, int $userId, string $content): ?Comment
    {
        $stmt = $this->db->prepare("
            UPDATE comments
            SET content = :content, updated_at = NOW()
            WHERE id = :comment_id AND user_id = :user_id
        ");
        $stmt->execute([
            ':comment_id' => $commentId,
            ':user_id' => $userId,
            ':content' => $content,
        ]);

        if ($stmt->rowCount() === 0) {
            return null;
        }

        $comment = new Comment();
        $comment->id = $commentId;
        $comment->user_id = $userId;
        $comment->content = $content;
        $comment->updated_at = date('Y-m-d H:i:s');

        return $comment;
    }
}