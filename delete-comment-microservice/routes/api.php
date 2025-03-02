<?php

use FastRoute\RouteCollector;
use DeleteCommentMicroservice\Controllers\DeleteCommentController;
use DeleteCommentMicroservice\Middlewares\AuthMiddleware;

return [
    ['DELETE', '/comments/{id}', [DeleteCommentController::class, 'delete']],
];