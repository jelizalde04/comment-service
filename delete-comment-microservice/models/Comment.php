<?php

namespace DeleteCommentMicroservice\Models;

class Comment
{
    public $id;
    public $recipe_id;
    public $user_id;
    public $content;
    public $created_at;
}