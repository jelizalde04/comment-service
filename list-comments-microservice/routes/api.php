<?php

use FastRoute\RouteCollector;
use ListCommentsMicroservice\Controllers\ListCommentsController;

return [
    ['GET', '/recipes/{recipe_id}/comments', [ListCommentsController::class, 'list']],
];