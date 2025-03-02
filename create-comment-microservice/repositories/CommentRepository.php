<?php

namespace CreateCommentMicroservice\Repositories;

use PDO;
use CreateCommentMicroservice\Models\Comment;

class CommentRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function createComment(int $userId, int $recipeId, string $content): Comment
    {
        $stmt = $this->db->prepare("
            INSERT INTO comments (recipe_id, user_id, content, created_at)
            VALUES (:recipe_id, :user_id, :content, NOW())
        ");
        $stmt->execute([
            ':recipe_id' => $recipeId,
            ':user_id' => $userId,
            ':content' => $content,
        ]);

        $comment = new Comment();
        $comment->id = $this->db->lastInsertId();
        $comment->recipe_id = $recipeId;
        $comment->user_id = $userId;
        $comment->content = $content;
        $comment->created_at = date('Y-m-d H:i:s');

        return $comment;
    }
}