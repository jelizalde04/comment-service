<?php

use FastRoute\RouteCollector;
use UpdateCommentMicroservice\Controllers\UpdateCommentController;
use UpdateCommentMicroservice\Middlewares\AuthMiddleware;

return [
    ['PUT', '/comments/{id}', [UpdateCommentController::class, 'update']],
];