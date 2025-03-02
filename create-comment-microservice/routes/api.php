<?php

use FastRoute\RouteCollector;
use CreateCommentMicroservice\Controllers\CreateCommentController;
use CreateCommentMicroservice\Middlewares\AuthMiddleware;

return [
    ['POST', '/comments', [CreateCommentController::class, 'create']],
];