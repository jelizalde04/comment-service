<?php

namespace ListCommentsMicroservice\Repositories;

use PDO;

class CommentRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function listComments(int $recipeId): array
    {
        $stmt = $this->db->prepare("
            SELECT id, recipe_id, user_id, content, created_at
            FROM comments
            WHERE recipe_id = :recipe_id
        ");
        $stmt->execute([':recipe_id' => $recipeId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}