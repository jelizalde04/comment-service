<?php

use FastRoute\RouteCollector;
use CommentPermissionMicroservice\Controllers\CommentPermissionController;

return [
    ['GET', '/comments/{comment_id}/can-modify', [CommentPermissionController::class, 'canModify']],
];